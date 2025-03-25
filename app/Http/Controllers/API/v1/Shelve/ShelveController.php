<?php

namespace App\Http\Controllers\API\v1\Shelve;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shelve\ShelveCreateRequest;
use App\Http\Requests\Shelve\ShelveDatatableRequest;
use App\Http\Requests\Shelve\ShelveUpdateRequest;
use App\Services\Shelve\ShelveServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ShelveController extends Controller
{
    protected ShelveServiceInterface $shelveService;

    public function __construct(
        ShelveServiceInterface $shelveService
    ) {
        $this->shelveService = $shelveService;
    }

    /**
     * list user
     *
     * @return JsonResponse
     */
    public function index(ShelveDatatableRequest $shelveDatatable): JsonResponse
    {
        $params = $shelveDatatable->validatedDatatable();
        $user = $this->shelveService->paginate($params);
        return $this->responseSuccessDatatable($user, $params->draw);
    }

    /**
     * delete
     *
     * @param  int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->shelveService->delete($id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }

    /**
     * store
     *
     * @param  ShelveCreateRequest $request
     * @return JsonResponse
     */
    public function store(ShelveCreateRequest $request): JsonResponse
    {
        $params = $request->validated();
        $this->shelveService->create($params);
        return $this->responseSuccess();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ShelveUpdateRequest $request
     * @param  int $id
     * @return JsonResponse
     */
    public function update(ShelveUpdateRequest $request, int $id): JsonResponse
    {
        $params = $request->validated();
        try {
            $this->shelveService->update($params, $id);
            return $this->responseSuccess();
        } catch (ModelNotFoundException $e) {
            return $this->responseFail(trans('message.modal_not_found'));
        }
    }
}
