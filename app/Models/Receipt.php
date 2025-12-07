<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany, HasOne, MorphMany, MorphTo, MorphOne};


class Receipt extends BaseModel
{
    protected ?string $moduleKey = 'finance';

    protected $fillable = ['branch_id','purchase_id','sale_id','method','amount','reference','paid_at','created_by','extra_attributes'];
    protected $casts = ['amount'=>'decimal:2','paid_at'=>'datetime'];

    public function purchase(): BelongsTo { return $this->belongsTo(Purchase::class); }
    public function sale(): BelongsTo { return $this->belongsTo(Sale::class); }
}
