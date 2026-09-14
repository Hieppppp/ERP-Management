<?php
namespace App\Services\MaterialRequest;
use App\Models\MaterialIssue;
use App\Models\MaterialRequest;
interface MaterialRequestServiceInterface { public function create(array $data): MaterialRequest; public function submit(int $id): MaterialRequest; public function approve(int $id, array $data): MaterialRequest; public function reject(int $id, array $data): MaterialRequest; public function issue(int $id, array $data): MaterialIssue; }
