<?php

namespace Routes\Web;

use App\Enums\Permission;
use App\Http\Controllers\WEB\Document\DocumentController;
use Illuminate\Support\Facades\Route;

class DocumentRoutes
{
    public static function routes()
    {
        return Route::resource('document', DocumentController::class)->middleware(['auth', "permission:" . implode(",", [Permission::DOCUMENT])]);
    }
}
