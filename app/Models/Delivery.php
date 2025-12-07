<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany, HasOne, MorphMany, MorphTo, MorphOne};


class Delivery extends BaseModel
{
    protected ?string $moduleKey = 'sales';

    protected $fillable = ['sale_id','delivered_at','delivered_by','status','notes','extra_attributes'];
    protected $casts = ['delivered_at'=>'datetime'];
    public function sale(): BelongsTo { return $this->belongsTo(Sale::class); }
}
