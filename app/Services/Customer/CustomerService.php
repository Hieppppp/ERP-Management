<?php

namespace App\Services\Customer;

use App\Helpers\FileHelper;
use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Customer\CustomerRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class CustomerService extends BaseService implements CustomerServiceInterface
{

    protected CustomerRepositoryInterface $customerRepository;
    /**
     * @param CustomerRepository $repository
     *
     * @return void
     */
    public function __construct(
        BaseRepository $repository,
        CustomerRepositoryInterface $customerRepository
    ) {
        parent::__construct($repository);
        $this->customerRepository = $customerRepository;
    }

    /**
     * create
     *
     * @param  array $params
     * @return Model
     */
    public function create(array $params): Model
    {
        if (!empty($params['avatar'])) {
            $avatar = FileHelper::uploadFile($params['avatar'], 'customers');
            $params['avatar'] = $avatar['fileName'];
        }
        $customer = parent::create($params);
        $customer->code = $this->codeCreate($customer->id);
        $customer->disableLogging()->save();
        return $customer;
    }

    /**
     * update
     *
     * @param  array $params
     * @param  int $id
     * @return Model
     */
    public function update(array $params, int $id): Model
    {
        if (!empty($params['avatar'])) {
            $customer = $this->findById($id);
            $oldAvatar = $customer->avatar;
            $avatar = FileHelper::uploadFile($params['avatar'], 'customers');
            if ($oldAvatar) {
                FileHelper::deleteFile($oldAvatar, 'customers');
            }
            $params['avatar'] = $avatar['fileName'];
        }
        return parent::update($params, $id);
    }

    /**
     * Create code
     *
     * @param  int $customerId
     * @return string
     */
    protected function codeCreate(int $customerId): string
    {
        return 'CUS' . str_pad($customerId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * search
     *
     * @param  array $params
     * @return LengthAwarePaginator
     */
    public function search(array $params): LengthAwarePaginator
    {
        return $this->repository->search($params);
    }

    public function analyzeCustomerBehavior()
    {
        $customers = $this->customerRepository->getCustomerBehaviorData();
        $classifiedCustomers = [
            'vip'       => [],
            'frequent'  => [],
            'others'    => [],
        ];

        foreach ($customers as $customer) {
            $totalSpending = $customer->sale_orders_sum_total_amount ?? 0;
            $orderCount = $customer->sale_orders_count ?? 0;

            if ($totalSpending > 50000000) {
                $classifiedCustomers['vip'][] = $customer;
            } elseif ($orderCount > 10) {
                $classifiedCustomers['frequent'][] = $customer;
            } else {
                $classifiedCustomers['others'][] = $customer;
            }
        }
        return $classifiedCustomers;
    }
}
