<?php

namespace App\Models;

use App\Enums\ImageTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'min_quantity',
        'max_quantity',
        'unit_price',
        'unit_id',
        'category_id',
        'sku',
        'code',
    ];

    protected $appends = [
        'qr_url'
    ];




    /**
     * Get the images for product
     *
     * @return HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class, 'entity_id')->where('type', ImageTypeEnum::PRODUCT);
    }

    public function parentProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_relations', 'child_product_id', 'parent_product_id');
    }

    public function childProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_relations', 'parent_product_id', 'child_product_id');
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers')->withPivot(['unit_cost', 'sku']);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class)->withTrashed();
    }

    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : null;
    }

    public function getUnitNameAttribute()
    {
        return $this->unit ? $this->unit->name . '(' .  $this->unit->symbol . ')' : null;
    }

    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class, 'product_id')->whereNotNull('action')->orderBy('created_at', 'desc');
    }

    public function getQRUrlAttribute()
    {
        return $this->qr_code_path ? asset(Storage::url("product/{$this->qr_code_path}")) : null;
    }
}
