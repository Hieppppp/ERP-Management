<?php

namespace App\Repositories\Document;

use App\Common\Entity\DatatableParams;
use App\Repositories\BaseRepository;
use App\Models\Document;
use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DocumentRepository extends BaseRepository implements DocumentRepositoryInterface
{
    public function __construct()
    {
        parent::__construct(Document::class);
    }

    public function paginate(DatatableParams $params): Paginator|LengthAwarePaginator
    {
        $query = $this->getModel()->select([
            'documents.*',
            DB::raw("CONCAT(users.first_name, '',users.last_name) as uploaded_by_name")
        ])->leftJoin('users','users.id', '=', 'documents.uploaded_by');
        if ($params->search) {
            $query = $query->where(function($query) use ($params) {
                $query->where('documents.file_name', 'like', "%{$params->search}%")
                    ->orWhere('documents.ipfs_hash', 'like', "%{$params->search}%")
                    ->orWhere('documents.document_type', 'like', "%{$params->search}%")
                    ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', "%{$params->search}%")
                    ->orWhere('documents.created_at', 'like', "%{$params->search}%");
            });
        }

        if ($params->order)
        {
            $orderType = $params->order['type'] ?? 'asc';
            $orderBy = $params->order['field'];

            if ($orderBy === 'uploaded_by_name') {
                $query = $query->orderBy(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), $orderType);
            } else {
                $query = $query->orderBy($orderBy, $orderType);
            }
        }

        return $query->paginate($params->length, ['*'], 'page', $params->page);
    }

    public function store(array $data)
    {
        return Document::create($data);
    }


}
