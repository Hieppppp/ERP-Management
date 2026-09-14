<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MaterialIssueLine extends Model { protected $fillable = ['material_issue_id','material_request_item_id','product_location_id','quantity']; }
