<?php

namespace App\Services\Document;

use App\Models\Document;
use App\Services\BaseService;
use App\Repositories\BaseRepository;
use App\Repositories\Document\DocumentRepositoryInterface;
use GuzzleHttp\Client;

class DocumentService extends BaseService implements DocumentServiceInterface
{
    protected $urlDocument;
    protected $client;
    /**
     * DocumentRepositoryInterface
     *
     * ?return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
        $this->urlDocument = config('services.ipfs.url');
        $this->client = new Client();
    }

    public function uploadFileDocument($file)
    {
        $data = $this->client->post($this->urlDocument,[
            'multipart' => [
                'name' => 'file',
                'contents' => fopen($file->path(), 'r'),
            ]
        ]);

        $body = json_decode($data->getBody(), true);
        $hash = $body['Hash'];
        return $this->store([
            'file_name' => $file->getClientOriginalName(),
            'ipfs_hash' => $hash,
        ]);
    }

    public function store(array $data)
    {
        $existingFile = Document::where(['ipfs_hash' => $data['ipfs_hash']])->first();

        if ($existingFile) {
            return response()->json([
                'message' => 'File existed IPFS',
                'ipfs_hash' => $existingFile->ipfs_hash
            ], 409);
        }
        return parent::create($data);
    }

}
