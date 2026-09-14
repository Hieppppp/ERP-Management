<?php
namespace App\Repositories\MaterialRequest;
use App\Models\MaterialRequest;
use App\Repositories\BaseRepository;
class MaterialRequestRepository extends BaseRepository implements MaterialRequestRepositoryInterface { public function __construct() { parent::__construct(MaterialRequest::class); } }
