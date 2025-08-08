<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'order_id',
        'order_type',
        'ipfs_hash',
        'file_name',
        'document_type',
        'uploaded_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function order()
    {
        return $this->morphTo(null, 'order_type', 'order_id');
    }
}
