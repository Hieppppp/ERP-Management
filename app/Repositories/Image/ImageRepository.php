<?php

namespace App\Repositories\Image;

use App\Repositories\BaseRepository;
use App\Models\Image;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Image::class);
    }
}
