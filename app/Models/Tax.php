<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany, HasOne, MorphMany, MorphTo, MorphOne};


class Tax extends BaseModel
{
    protected ?string $moduleKey = 'pricing';

    protected $fillable = ['name','rate','type','is_inclusive','extra_attributes'];
    protected $casts = ['rate'=>'decimal:4','is_inclusive'=>'bool'];
}
