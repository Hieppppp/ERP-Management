<?php
use App\Http\Requests\MaterialRequest\MaterialIssueCreateRequest;
use App\Http\Requests\MaterialRequest\MaterialRequestCreateRequest;
use Illuminate\Support\Facades\Validator;

it('validates a material request payload', function () {
    $data=['department_id'=>1,'needed_at'=>'2026-10-01','items'=>[['product_id'=>1,'quantity'=>2,'note'=>'For maintenance']]];
    expect(Validator::make($data,(new MaterialRequestCreateRequest())->rules())->fails())->toBeFalse();
});
it('rejects invalid material request quantities and duplicate products', function () {
    $data=['department_id'=>1,'items'=>[['product_id'=>1,'quantity'=>0],['product_id'=>1,'quantity'=>2]]];
    $validator=Validator::make($data,(new MaterialRequestCreateRequest())->rules());
    expect($validator->fails())->toBeTrue()->and($validator->errors()->has('items.0.quantity'))->toBeTrue()->and($validator->errors()->has('items.1.product_id'))->toBeTrue();
});
it('validates material issue allocations', function () {
    $data=['allocations'=>[['item_id'=>1,'product_location_id'=>1,'quantity'=>1.5]]];
    expect(Validator::make($data,(new MaterialIssueCreateRequest())->rules())->fails())->toBeFalse();
});
it('rejects a material issue without allocations', function () {
    $validator=Validator::make(['allocations'=>[]],(new MaterialIssueCreateRequest())->rules());
    expect($validator->fails())->toBeTrue()->and($validator->errors()->has('allocations'))->toBeTrue();
});
