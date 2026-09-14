<?php
use App\Http\Requests\MaterialRequest\MaterialIssueCreateRequest;
use App\Http\Requests\MaterialRequest\MaterialRequestCreateRequest;
use App\Models\Department;
use App\Models\MaterialRequest;
use App\Models\MaterialRequestItem;
use App\Models\ProductLocation;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

function materialRequestValidationFixture(): array
{
    $user = User::query()->firstOrFail();
    $location = ProductLocation::query()->firstOrFail();
    $department = Department::firstOrCreate(['name' => 'Test Materials'], ['code' => 'TEST-MAT']);
    $request = MaterialRequest::create([
        'code' => 'TEST-MR-' . uniqid(),
        'department_id' => $department->id,
        'requested_by' => $user->id,
        'status' => 'draft',
    ]);
    $item = MaterialRequestItem::create([
        'material_request_id' => $request->id,
        'product_id' => $location->product_id,
        'requested_quantity' => 2,
    ]);

    return [$department, $item, $location];
}

it('validates a material request payload', function () {
    [$department, $item] = materialRequestValidationFixture();
    $data=['department_id'=>$department->id,'needed_at'=>'2026-10-01','items'=>[['product_id'=>$item->product_id,'quantity'=>2,'note'=>'For maintenance']]];
    expect(Validator::make($data,(new MaterialRequestCreateRequest())->rules())->fails())->toBeFalse();
});
it('rejects invalid material request quantities and duplicate products', function () {
    $data=['department_id'=>1,'items'=>[['product_id'=>1,'quantity'=>0],['product_id'=>1,'quantity'=>2]]];
    $validator=Validator::make($data,(new MaterialRequestCreateRequest())->rules());
    expect($validator->fails())->toBeTrue()->and($validator->errors()->has('items.0.quantity'))->toBeTrue()->and($validator->errors()->has('items.1.product_id'))->toBeTrue();
});
it('validates material issue allocations', function () {
    [, $item, $location] = materialRequestValidationFixture();
    $data=['allocations'=>[['item_id'=>$item->id,'product_location_id'=>$location->id,'quantity'=>1.5]]];
    expect(Validator::make($data,(new MaterialIssueCreateRequest())->rules())->fails())->toBeFalse();
});
it('rejects a material issue without allocations', function () {
    $validator=Validator::make(['allocations'=>[]],(new MaterialIssueCreateRequest())->rules());
    expect($validator->fails())->toBeTrue()->and($validator->errors()->has('allocations'))->toBeTrue();
});
