<?php

namespace App\Services\Product;

use App\Common\Entity\DatatableParams;
use App\Enums\ActionLogEnum;
use App\Enums\ImageTypeEnum;
use App\Enums\StorageFolderEnum;
use App\Exceptions\ProductDeleteException;
use App\Helpers\FileHelper;
use App\Models\ActivityLogs;
use App\Models\Product;
use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Image\ImageRepositoryInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductService extends BaseService implements ProductServiceInterface
{
    protected ImageRepositoryInterface $imageRepository;
    /**
     * ProductRepositoryInterface
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository,
        ImageRepositoryInterface $imageRepository
    ) {
        parent::__construct($repository);
        $this->imageRepository = $imageRepository;
    }

    /**
     * create
     *
     * @param  array $params
     * @return Model
     */
    public function create(array $params): Model
    {
        $params['sku'] = $this->generateSKU();
        $product = parent::create($params);
        if (!empty($params['image'])) {
            $imageData = [];
            foreach ($params['image'] as $image) {
                $image = FileHelper::uploadFile($image, StorageFolderEnum::PRODUCT);
                $imageData[] = [
                    'name' => $image['fileName'],
                    'folder' => StorageFolderEnum::PRODUCT,
                    'type' => ImageTypeEnum::PRODUCT,
                    'entity_id' => $product->id
                ];
            }
            $this->imageRepository->insertMultiple($imageData);
        }

        if ($product) {
            if (!empty($params['child_products'])) {
                $product->childProducts()->attach($params['child_products']);
            }
            if (!empty($params['parent_products'])) {
                $product->parentProducts()->attach($params['parent_products']);
            }
            if (!empty($params['suppliers'])) {
                $dataSupplier = [];
                foreach ($params['suppliers'] as $supplier) {
                    $dataSupplier[$supplier['id']] = [
                        'unit_cost' => $supplier['price']
                    ];
                }
                if ($dataSupplier) {
                    $product->suppliers()->attach($dataSupplier);
                }
            }
        }
        $product->code = $this->createCode($product->id);
        $this->generateQRCode($product);
        $product->save();
        activity('default')
            ->performedOn($product)
            ->event(ActionLogEnum::CREATED)
            ->log(ActionLogEnum::CREATED);
        return $product;
    }

    /**
     * findById
     *
     * @param  string|int $id
     * @return Model
     */
    public function findById(string|int $id): Model
    {
        $product = parent::findById($id);
        $product->parentProducts->each(function ($parentProduct) {
            $parentProduct->append(['category_name', 'unit_name']);
        });
        $product->childProducts->each(function ($childProduct) {
            $childProduct->append(['category_name', 'unit_name']);
        });
        $product->suppliers->each(function ($supplier) {
            $supplier->append('address');
        });
        return $product;
    }

    public function update(array $params, int $id): Model
    {
        $oldProduct = $this->repository->findById($id)->toArray();
        $product = parent::update($params, $id);

        if (!empty($params['image'])) {
            //delete image
            $oldImageData = $product->images;
            $product->images()->delete();
            //update image
            $imageData = [];
            foreach ($params['image'] as $image) {
                $image = FileHelper::uploadFile($image, StorageFolderEnum::PRODUCT);
                $imageData[] = [
                    'name' => $image['fileName'],
                    'folder' => StorageFolderEnum::PRODUCT,
                    'type' => ImageTypeEnum::PRODUCT,
                    'entity_id' => $product->id
                ];
            }
            $this->imageRepository->insertMultiple($imageData);
            if ($oldImageData) {
                foreach ($oldImageData as $image) {
                    FileHelper::deleteFile($image->name, $image->folder);
                }
            }
        }

        if ($product) {
            $product->childProducts()->sync($params['child_products'] ?? []);
            $product->parentProducts()->sync($params['parent_products'] ?? []);
            $dataSupplier = [];
            if (!empty($params['suppliers'])) {
                foreach ($params['suppliers'] as $supplier) {
                    $dataSupplier[$supplier['id']] = [
                        'unit_cost' => $supplier['price']
                    ];
                }
            }
            $product->suppliers()->sync($dataSupplier);
        }

        $product = $this->repository->findById($id);
        $attributes = $product->toArray();
        activity('default')
            ->performedOn($product)
            ->event(ActionLogEnum::UPDATED)
            ->withProperties([
                'attributes' => $attributes,
                'old' => $oldProduct
            ])
            ->log(ActionLogEnum::UPDATED);
        return $product;
    }

    /**
     * Get Activity Product
     *
     * @param  string $id
     * @return array
     */
    public function getActivityProduct(string $id): array
    {
        $activityLogs = ActivityLogs::select(['*'])
            ->with(['user' => function ($query) {
                $query->withTrashed();
            }])
            ->where('subject_id', $id)
            ->where('subject_type', Product::class)
            ->orderBy('id', 'desc')
            ->get()->toArray();
        foreach ($activityLogs as $key => $activityLog) {
            if ($activityLog['event'] === ActionLogEnum::UPDATED) {
                $activityLogs[$key]['change'] = $this->getDifference($activityLog['properties']['old'], $activityLog['properties']['attributes']);
            }
        }
        return $activityLogs;
    }

    /**
     * getDifference
     *
     * @param  array $oldData
     * @param  array $newData
     * @return array
     */
    protected function getDifference(array $oldData, array $newData): array
    {
        $result = [];

        foreach ($oldData as $key => $oldValue) {
            if (array_key_exists($key, $newData)) {
                $newValue = $newData[$key];

                if (is_array($oldValue) && is_array($newValue)) {
                    if (!in_array($key, ['parent_products', 'suppliers'])) {
                        continue;
                    }
                    $result[$key] = [
                        'created' => [],
                        'updated' => [],
                        'deleted' => []
                    ];
                    $oldValueIds = array_map(fn($element) => $element['id'], $oldValue);
                    $newValueIds = array_map(fn($element) => $element['id'], $newValue);

                    $deletedData = array_diff($oldValueIds, $newValueIds);
                    if ($deletedData) {
                        foreach ($deletedData as $index => $value) {
                            $result[$key]['deleted'][] = $oldValue[$index];
                        }
                    }

                    $createdData = array_diff($newValueIds, $oldValueIds);
                    if ($createdData) {
                        foreach ($createdData as $index => $value) {
                            $result[$key]['created'][] = $newValue[$index];
                        }
                    }

                    if ($key !== 'suppliers') {
                        continue;
                    }

                    $updatedData = array_intersect($oldValueIds, $newValueIds);
                    if ($updatedData) {
                        foreach ($updatedData as $index => $value) {
                            $newValueData = array_values(array_filter($newValue, fn($item) => $item['id'] == $value))[0];
                            if ($oldValue[$index]['pivot']['unit_cost'] !== $newValueData['pivot']['unit_cost']) {
                                $result[$key]['updated'][] = [
                                    'old' => $oldValue[$index],
                                    'new' => $newValueData
                                ];
                            }
                        }
                    }
                } elseif ($oldValue !== $newValue) {
                    $result[$key] = [
                        'old' => $oldValue,
                        'new' => $newValue
                    ];
                }
            }
        }
        return $result;
    }

    /**
     * Generate SKU
     *
     * @return string
     */
    public function generateSKU(): string
    {
        do {
            $sku = rand(1, 9);
            for ($i = 0; $i < 7; $i++) {
                $sku .= rand(0, 9);
            }
        } while (Product::where('sku', $sku)->exists());

        return $sku;
    }

    /**
     * inventory
     *
     * @param  DatatableParams $params
     * @param  int $id
     * @return Paginator|LengthAwarePaginator
     */
    public function inventory(DatatableParams $params, int $id): Paginator|LengthAwarePaginator
    {
        return $this->repository->inventory($params, $id);
    }

    /**
     * createCode
     *
     * @param  int $productId
     * @return string
     */
    protected function createCode(int $productId): string
    {
        return 'PRD' . str_pad($productId, 3, '0', STR_PAD_LEFT);
    }

    /**
     * delete
     *
     * @param  int $id
     * @return bool|null
     */
    public function delete(int $id): bool|null
    {
        $totalProduct = $this->findById($id);
        if ($totalProduct->quantity > 0) {
            throw new ProductDeleteException(__('message.notDeleteProduct'));
        }
        activity('default')
            ->performedOn($this->repository->getModel()->findOrFail($id))
            ->event(ActionLogEnum::DELETED)
            ->log(ActionLogEnum::DELETED);
        return Product::find($id)->delete();
    }

    public function generateQRCode(Product $product)
    {
        // 1. Tạo QR code
        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
            'scale' => 11,
        ]);

        $data = route('product.show', $product->id);
        $qrImage = (new QRCode($options))->render($data);

        // 2. Tạo tên file duy nhất
        $fileName = 'qr-' . $product->id . '-' . time() . '.png';
        $filePath = "product/{$fileName}";  // Lưu vào thư mục product trong storage/app/public

        // 3. Lưu vào thư mục storage/app/public/product
        Storage::disk('public')->put($filePath, $qrImage);  // Lưu vào đúng thư mục public/storage/product

        // 4. Gán đường dẫn vào product (đường dẫn bắt đầu từ public)
        $product->qr_code_path = $fileName;
        $product->save();
    }



}
