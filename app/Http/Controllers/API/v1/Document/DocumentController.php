<?php

namespace App\Http\Controllers\API\v1\Document;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\DocumentDataTableRequest;
use App\Services\Document\DocumentServiceInterface;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    protected DocumentServiceInterface $documentServiceInterface;

    public function __construct(DocumentServiceInterface $documentServiceInterface)
    {
        $this->documentServiceInterface = $documentServiceInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(DocumentDataTableRequest $request)
    {
        $params = $request->validatedDatatable();
        $data = $this->documentServiceInterface->paginate($params);
        return $this->responseSuccessDatatable($data, $params->draw);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120|mimes:jpg,jpeg,png,pdf,doc,docx,txt',
        ]);
        $file = $request->file('file');
        $fileDocument = $this->documentServiceInterface->uploadFileDocument($file);
        return $this->responseSuccess($fileDocument);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
