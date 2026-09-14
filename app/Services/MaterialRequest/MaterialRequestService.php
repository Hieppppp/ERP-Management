<?php
namespace App\Services\MaterialRequest;
use App\Enums\ActionLogEnum;
use App\Enums\MaterialRequestStatusEnum;
use App\Models\InventoryLog;
use App\Models\MaterialIssue;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\ProductLocation;
use App\Repositories\BaseRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class MaterialRequestService extends BaseService implements MaterialRequestServiceInterface
{
 public function __construct(BaseRepository $repository) { parent::__construct($repository); }
 public function create(array $data): MaterialRequest { return DB::transaction(function () use ($data) { $request = MaterialRequest::create(['code' => 'MR-' . now()->format('Ymd') . '-' . str_pad((string)(MaterialRequest::max('id') + 1), 5, '0', STR_PAD_LEFT),'department_id'=>$data['department_id'],'requested_by'=>Auth::id(),'status'=>MaterialRequestStatusEnum::DRAFT,'needed_at'=>$data['needed_at'] ?? null,'note'=>$data['note'] ?? null]); foreach ($data['items'] as $item) $request->items()->create(['product_id'=>$item['product_id'],'requested_quantity'=>$item['quantity'],'note'=>$item['note'] ?? null]); return $request->load('items.product','department'); }); }
 public function submit(int $id): MaterialRequest { return $this->transition($id, MaterialRequestStatusEnum::DRAFT, MaterialRequestStatusEnum::PENDING); }
 public function approve(int $id, array $data): MaterialRequest { $request=$this->transition($id, MaterialRequestStatusEnum::PENDING, MaterialRequestStatusEnum::APPROVED); $request->update(['approved_by'=>Auth::id(),'approved_at'=>now(),'approval_note'=>$data['note'] ?? null]); return $request; }
 public function reject(int $id, array $data): MaterialRequest { $request=$this->transition($id, MaterialRequestStatusEnum::PENDING, MaterialRequestStatusEnum::REJECTED); $request->update(['approved_by'=>Auth::id(),'approved_at'=>now(),'approval_note'=>$data['note'] ?? null]); return $request; }
 public function issue(int $id, array $data): MaterialIssue { return DB::transaction(function () use ($id,$data) { $request=MaterialRequest::lockForUpdate()->findOrFail($id); if (!in_array($request->status,[MaterialRequestStatusEnum::APPROVED,MaterialRequestStatusEnum::PARTIALLY_ISSUED],true)) throw ValidationException::withMessages(['status'=>[__('message.materialRequest.invalidStatus')]]); $issue=MaterialIssue::create(['material_request_id'=>$id,'issued_by'=>Auth::id(),'note'=>$data['note'] ?? null]); foreach ($data['allocations'] as $allocation) { $item=MaterialRequestItem::lockForUpdate()->findOrFail($allocation['item_id']); $location=ProductLocation::lockForUpdate()->findOrFail($allocation['product_location_id']); if ($item->material_request_id !== $request->id || $item->product_id !== $location->product_id) throw ValidationException::withMessages(['allocations'=>[__('message.materialRequest.invalidAllocation')]]); if ($allocation['quantity'] > ($item->requested_quantity-$item->issued_quantity) || $allocation['quantity'] > $location->quantity) throw ValidationException::withMessages(['allocations'=>[__('message.materialRequest.insufficientStock')]]); $before=$location->quantity; $location->decrement('quantity',$allocation['quantity']); $item->increment('issued_quantity',$allocation['quantity']); $issue->lines()->create(['material_request_item_id'=>$item->id,'product_location_id'=>$location->id,'quantity'=>$allocation['quantity']]); InventoryLog::create(['user_id'=>Auth::id(),'product_location_id'=>$location->id,'product_id'=>$location->product_id,'before_quantity'=>$before,'after_quantity'=>$before-$allocation['quantity'],'action'=>ActionLogEnum::UPDATED,'note'=>'Material request '.$request->code]); } $request->refresh(); $complete=$request->items()->whereColumn('issued_quantity','<','requested_quantity')->doesntExist(); $request->update(['status'=>$complete ? MaterialRequestStatusEnum::ISSUED : MaterialRequestStatusEnum::PARTIALLY_ISSUED]); return $issue->load('lines'); }); }
 private function transition(int $id, string $from, string $to): MaterialRequest { $request=MaterialRequest::findOrFail($id); if ($request->status !== $from) throw ValidationException::withMessages(['status'=>[__('message.materialRequest.invalidStatus')]]); $request->update(['status'=>$to]); return $request; }
}
