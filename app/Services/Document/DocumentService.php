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
        $customFileName = request('custom_file_name') ?: $file->getClientOriginalName();
        $documentType = request('document_type');

        if (!in_array($documentType, \App\Enums\DocumentTypeEnum::getValues())) {
            return response()->json(['message' => 'Invalid document type'], 422);
        }
        $data = $this->client->post($this->urlDocument, [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($file->path(), 'r'),
                    'filename' => $file->getClientOriginalName(),
                ]
            ]
        ]);

        $body = json_decode($data->getBody(), true);
        $hash = $body['Hash'];

        return $this->store([
            'file_name' => $customFileName,
            'ipfs_hash' => $hash,
            'document_type' => $documentType,
        ]);
    }

    public function store(array $data)
    {
        $existingFile = Document::where('ipfs_hash', $data['ipfs_hash'])->first();

        if ($existingFile) {
            return response()->json([
                'message' => 'File already exists in IPFS',
                'cid' => $existingFile->ipfs_hash
            ], 200);
        }

        $data['uploaded_by'] = auth()->id();
        $document = parent::create($data);
        return response()->json([
            'message' => 'File uploaded successfully',
            'cid' => $document->ipfs_hash
        ], 201);
    }

}
