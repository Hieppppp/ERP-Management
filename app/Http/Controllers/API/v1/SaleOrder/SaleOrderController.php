<?php

namespace App\Http\Controllers\API\v1\SaleOrder;

use App\Helpers\SaleOrderHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaleOrder\InvoiceDatatable;
use App\Http\Requests\SaleOrder\ReceiptOfGoodValidateRequest;
use App\Http\Requests\SaleOrder\RegisterPaymentRequest;
use App\Http\Requests\SaleOrder\SaleOrderAddStockRequest;
use App\Http\Requests\SaleOrder\SaleOrderCreateRequest;
use App\Http\Requests\SaleOrder\SaleOrderDatatableRequest;
use App\Http\Requests\SaleOrder\SaleOrderUpdateRequest;
use App\Http\Requests\SaleOrder\SaleOrderUpdateStatusRequest;
use App\Models\RegisterPayment;
use App\Models\SaleOrder;
use App\Services\SaleOrder\SaleOrderServiceInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SaleOrderController extends Controller
{
    protected SaleOrderServiceInterface $saleOrderService;

    public function __construct(
        SaleOrderServiceInterface $saleOrderService
    ) {
        $this->saleOrderService = $saleOrderService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  SaleOrderDatatableRequest $request
     * @return JsonResponse
     */
    public function index(SaleOrderDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $saleOrders = $this->saleOrderService->paginate($params);
        return $this->responseSuccessDatatable($saleOrders, $params->draw);
    }

    /**
     * create resource.
     *
     * @param  SaleOrderCreateRequest $request
     * @return JsonResponse
     */
    public function store(SaleOrderCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $saleOrders = $this->saleOrderService->create($params);
        return $this->responseSuccess($saleOrders);
    }

    /**
     * update resource.
     *
     * @param  SaleOrderUpdateRequest $request
     * @return JsonResponse
     */
    public function update(SaleOrderUpdateRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        $saleOrders = $this->saleOrderService->update($params, $id);
        return $this->responseSuccess($saleOrders);
    }

    /**
     * Register Payment
     *
     * @param  RegisterPaymentRequest $request
     * @param  string $id
     * @return JsonResponse
     */
    public function registerPayment(RegisterPaymentRequest $request, string $id): JsonResponse
    {
        $params = $request->validated();
        try {
            $this->saleOrderService->registerPayment($params, $id);
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
        return $this->responseSuccess();
    }

    /**
     * Get Invoice List
     *
     * @param  InvoiceDatatable $request
     * @return JsonResponse
     */
    public function getInvoiceList(InvoiceDatatable $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $invoices = $this->saleOrderService->getInvoiceList($params);
        return $this->responseSuccessDatatable($invoices, $params->draw);
    }

    /**
     * destroy
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $saleOrders = $this->saleOrderService->delete($id);
        return $this->responseSuccess($saleOrders);
    }


    /**
     * update status.
     *
     * @param  SaleOrderUpdateRequest $request
     * @return JsonResponse
     */
    public function updateStatus(SaleOrderUpdateStatusRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $params = $request->validated();
            $saleOrders = $this->saleOrderService->updateStatus($params, $id);
            DB::commit();
            return $this->responseSuccess($saleOrders);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFail($e->getMessage());
        }
    }

    /**
     * validateROG
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function validateROG(ReceiptOfGoodValidateRequest $request, int $id): JsonResponse
    {
        DB::beginTransaction();
        $params = $request->validated();
        try {
            $saleOrders = $this->saleOrderService->validateROG($id, $params);
            DB::commit();
            return $this->responseSuccess($saleOrders);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->responseFail($e->getMessage());
        }
    }

    public function addStock(SaleOrderAddStockRequest $request, int $orderId, int $productId): JsonResponse
    {
        $params = $request->validated();
        $this->saleOrderService->addStock($params, $orderId, $productId);
        return $this->responseSuccess();
    }

    /**
     * Get Invoice PDF
     *
     * @return JsonResponse|View|Factory|Response
     */
    public function getInvoicePDF(string $id): JsonResponse|View|Factory|Response
    {
        $type = request()->input('type');
        $data = $this->saleOrderService->getInvoicePDF($id);
        if ($type == 'print') {
            $pdf = Pdf::loadView('pages.invoice.pdf', $data);
            $fileName = 'Invoice' . now()->format('d-m-Y') . '.pdf';
            return $pdf->stream($fileName)->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="document.pdf"');
        }
        if ($type == 'send') {
            $this->saleOrderService->sendInvoicePDF($id);
        }
        $data['viewPdf'] = true;
        return view('pages.invoice.pdf', $data);
    }
}
