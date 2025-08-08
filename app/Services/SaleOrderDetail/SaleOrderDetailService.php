<?php

namespace App\Services\SaleOrderDetail;

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\SaleOrderDetail\SaleOrderDetailRepository;
use App\Repositories\SaleOrderDetail\SaleOrderDetailRepositoryInterface;
use Rubix\ML\BootstrapAggregator;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\PersistentModel;
use Rubix\ML\Persisters\Filesystem;
use Rubix\ML\Regressors\RegressionTree;

class SaleOrderDetailService extends BaseService implements SaleOrderDetailServiceInterface
{
    /**
     * @param SaleOrderDetailRepository $repository
     *
     * @return void
     */
    public function __construct(
        BaseRepository $repository,
    ) {
        parent::__construct($repository);
    }

    public function getHistoricalSales($productId)
    {
        $historicalSales = $this->repository->getHistoricalSales($productId);

        if (empty($historicalSales)) {
            return ['error', 'Không đủ dữ liệu để dự đoán'];
        }

        // Chuẩn bị dữ liệu huấn luyện
        $samples = [];
        $targets = [];
        $days = array_keys($historicalSales);
        $quantities = array_values($historicalSales);

        for ($i = 0; $i < count($days); $i++) {
            $samples[] = [$i]; // Sử dụng chỉ số ngày làm đầu vào
            $targets[] = $quantities[$i];
        }

        $dataset = new Labeled($samples, $targets);

        // Tạo thư mục chứa mô hình nếu chưa có
        $directory = storage_path('app/models');
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Đường dẫn file lưu model
        $modelPath = $directory . '/demand_forest_' . $productId . '.rbc';
        $persister = new Filesystem($modelPath);

        // Tạo mô hình và train
        $estimator = new BootstrapAggregator(new RegressionTree(5), 10);
        $model = new PersistentModel($estimator, $persister);

        // Train + lưu mô hình
        $model->train($dataset);
        $model->save();

        // Dự đoán 30 ngày tiếp theo
        $forecast = [];
        $startDate = now()->addDay();

        for ($i = 0; $i < 30; $i++) {
            $inputIndex = $i + count($days); // Tiếp tục từ ngày hiện tại
            $prediction = $model->predict(new Unlabeled([[$inputIndex]]))[0];
            $forecast[$startDate->copy()->addDays($i)->toDateString()] = max(0, (int) $prediction); // Không âm
        }

        // Tính xu hướng dự báo so với tháng trước
        $lastMonthSales = array_sum($quantities);
        $currentMonthPrediction = array_sum(array_values($forecast));
        $trendPercentage = $lastMonthSales > 0
            ? (($currentMonthPrediction - $lastMonthSales) / $lastMonthSales * 100)
            : 0;

        // Gợi ý nhập hàng (tăng 20% nếu xu hướng tăng)
        $recommendedQuantity = $trendPercentage > 0
            ? (int)($currentMonthPrediction * 1.2)
            : (int) $currentMonthPrediction;

        return [
            'forecast' => $forecast,
            'trend_percentage' => $trendPercentage,
            'recommended_quantity' => $recommendedQuantity,
        ];
    }

}
