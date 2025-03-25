<?php

namespace App\Http\Controllers\API\v1\Log;

use App\Http\Controllers\Controller;
use App\Http\Requests\Log\LogDatatableRequest;
use App\Services\Log\LogServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogController extends Controller
{
    protected LogServiceInterface $logService;

    public function __construct(
        LogServiceInterface $logService
    ) {
        $this->logService = $logService;
    }

    public function index(LogDatatableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $logs = $this->logService->paginate($params);
        return $this->responseSuccessDatatable($logs, $params->draw);
    }
}
