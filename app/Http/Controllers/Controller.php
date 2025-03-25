<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use stdClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * Format of response success with the result type is an array
     * @param array|object|int|null $results data response
     * @param int $code response code
     * @param string|array|null $message the success message
     * @return JsonResponse, include keys: code, message, results
     */
    public function responseSuccess(array|object|int $results = null, string|array $message = null, Int $code = 200): JsonResponse
    {
        $data = [
            "status" => true,
            "message" => is_string($message) ? [$message] : $message,
            "data" => $results,
        ];
        return response()->json($data, $code);
    }

    /**
     * Format of response success with the result type is an array
     * @param array|object|null $results data response
     * @param int $code response code
     * @param string|array|null $message the success message
     * @return JsonResponse, include keys: code, message, results
     */
    public function responseSuccessPagination(array|object $results = null, string|array $message = null, int $code = 200): JsonResponse
    {
        $data = [
            "status" => true,
            "message" => is_string($message) ? [$message] : $message,
            "pagination" => [
                "total" => $results->total(),
                "per_page" => $results->perPage(),
                "current_page" => $results->currentPage(),
                "last_page" => $results->lastPage(),
                "from" => $results->firstItem(),
                "to" => $results->lastItem(),
            ],
            "data" => $results->items(),
        ];
        return response()->json($data, $code);
    }

    /**
     * Format of response fail
     * @param string|array|null $message string response
     * @param int $code response code
     * @return JsonResponse, include keys: code, message, result
     */
    public function responseFail(string|array $message = null, Int $code = 400, ?array $content = []): JsonResponse
    {
        $data = [
            "status" => false,
            "message" => is_string($message) ? [$message] : $message,
            "data" => $content
        ];
        return response()->json($data, $code);
    }

    /**
     * Response Success Datatable
     *
     * @param  array|object $results
     * @param  string|array|int $draw
     * @param  int $code
     * @return JsonResponse
     */
    public function responseSuccessDatatable(array|object $results = null, string|array|int $draw = null, int $code = 200): JsonResponse
    {
        if (!$draw) {
            throw new \Exception("Draw is required");
        }
        $data = [
            "draw" => (int)$draw,
            "recordsTotal" => (int)$results->perPage(),
            "recordsFiltered" => (int)$results->total(),
            "data" => $results->items(),
        ];
        return response()->json($data, $code);
    }

    /**
     * responseSuccessForSelect
     *
     * @param  array $results
     * @param  int $current_page
     * @param  int $code
     * @return JsonResponse
     */
    public function responseSuccessForSelect(array $results = null, Int $current_page = 1, Int $code = 200): JsonResponse
    {
        $pagination = new stdClass();
        $pagination->more = $results['pagination']['lastPage'] > $current_page;
        $data = [
            "pagination" => $pagination,
            "results" => $results['data'],
        ];
        return response()->json($data, $code);
    }
}
