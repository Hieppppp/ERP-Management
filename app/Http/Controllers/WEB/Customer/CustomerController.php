<?php

namespace App\Http\Controllers\WEB\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerCreateRequest;
use App\Http\Requests\Customer\CustomerEditRequest;
use App\Services\Customer\CustomerServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{
    protected CustomerServiceInterface $customerService;

    public function __construct(
        CustomerServiceInterface $customerService
    ) {
        $this->customerService = $customerService;
    }
    /**
     * Display a listing of the resource.
     * 
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/customer/index');
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        return view('pages/customer/create');
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param CustomerCreateRequest $request
     * @return Redirector|RedirectResponse
     */
    public function store(CustomerCreateRequest $request): Redirector|RedirectResponse
    {
        $params = $request->validated();
        $this->customerService->create($params);
        session()->flash('success', __('message.success'));
        return redirect()->to('/customer');
    }

    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return View|Factory
     */
    public function show(int $id): View|Factory
    {
        $customer = $this->customerService->findById($id);
        $activities = $customer->activities()->with(['causer' => function ($query) {
            $query->withTrashed();
        }])->orderBy('id', 'desc')->get();
        return view('pages/customer/detail', ['customer' => $customer, 'activities' => $activities]);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param int $id
     * @return View|Factory
     */
    public function edit(int $id): View|Factory
    {
        $customer = $this->customerService->findById($id);
        $data['customer'] = $customer;
        $data['activities'] = $customer->activities()->with(['causer' => function ($query) {
            $query->withTrashed();
        }])->orderBy('id', 'desc')->get();
        return view('pages/customer/edit', $data);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param CustomerEditRequest $request
     * @param int $id
     * @return Redirector|RedirectResponse
     */
    public function update(CustomerEditRequest $request, int $id): Redirector|RedirectResponse
    {
        $params = $request->validated();
        $this->customerService->update($params, $id);
        session()->flash('success', __('message.success'));
        return redirect()->to('/customer');
    }

        /**
     * select inventory view
     *
     * @param  int $id
     * @return View|Factory
     */
    public function select(): View|Factory
    {
        return view('pages/customer/select');
    }
}
