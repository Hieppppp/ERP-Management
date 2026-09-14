<?php
namespace App\Http\Controllers\API\v1\MaterialRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\MaterialRequest\MaterialIssueCreateRequest;
use App\Http\Requests\MaterialRequest\MaterialRequestCreateRequest;
use App\Http\Requests\MaterialRequest\MaterialRequestDecisionRequest;
use App\Services\MaterialRequest\MaterialRequestServiceInterface;
use Illuminate\Http\JsonResponse;
class MaterialRequestController extends Controller
{
 public function __construct(private MaterialRequestServiceInterface $service) {}
 public function store(MaterialRequestCreateRequest $request): JsonResponse { return $this->responseSuccess($this->service->create($request->validated()), __('message.createSuccess'), 201); }
 public function submit(int $id): JsonResponse { return $this->responseSuccess($this->service->submit($id)); }
 public function approve(MaterialRequestDecisionRequest $request,int $id): JsonResponse { return $this->responseSuccess($this->service->approve($id,$request->validated())); }
 public function reject(MaterialRequestDecisionRequest $request,int $id): JsonResponse { return $this->responseSuccess($this->service->reject($id,$request->validated())); }
 public function issue(MaterialIssueCreateRequest $request,int $id): JsonResponse { return $this->responseSuccess($this->service->issue($id,$request->validated()), __('message.materialRequest.issued'), 201); }
}
