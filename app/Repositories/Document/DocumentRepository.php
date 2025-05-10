<?php

namespace App\Repositories\Document;

use App\Repositories\BaseRepository;
use App\Models\Document;

class DocumentRepository extends BaseRepository implements DocumentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Document::class);
    }

    public function store(array $data)
    {
        return Document::create($data);
    }


}
