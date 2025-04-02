<?php

namespace App\Http\Controllers\API\v1\Position;

use App\Http\Controllers\Controller;
use App\Http\Requests\Position\PositionCreateRequest;
use App\Http\Requests\Position\PositionDataTableRequest;
use App\Http\Requests\Position\PositionSearchRequest;
use App\Http\Requests\Position\PositionUpdateRequest;
use App\Http\Resources\Position\PositionSelectCollection;
use App\Services\Position\PositionServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected PositionServiceInterface $positionService;

    public function __construct(
        PositionServiceInterface $positionService
    )
    {
        $this->positionService = $positionService;
    }
   
    public function index(PositionDataTableRequest $request): JsonResponse
    {
        $params = $request->validatedDatatable();
        $position = $this->positionService->paginate($params);
        return $this->responseSuccessDatatable($position, $params->draw);
    }

        
    /**
     * store
     *
     * @param  PositionCreateRequest $request
     * @return JsonResponse
     */
    public function store(PositionCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->positionService->create($params);
        return $this->responseSuccess();
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(PositionUpdateRequest $request, string $id)
    {
        $params = $request->validated();
        try {
            $this->positionService->update($params, $id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->positionService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    public function search(PositionSearchRequest $request): JsonResponse
    {
        $params = $request->validated();
        $positions = $this->positionService->search($params);
        $positions = new PositionSelectCollection($positions);
        return $this->responseSuccessForSelect($positions->transformer($request), $params['page']);
    }
}
