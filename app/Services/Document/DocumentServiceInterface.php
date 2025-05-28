<?php

namespace App\Services\Document;

use App\Services\BaseServiceInterface;

interface DocumentServiceInterface extends BaseServiceInterface
{
    public function uploadFileDocument($file);
}
