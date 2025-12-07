<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany, HasOne, MorphMany, MorphTo, MorphOne};


class PriceGroup extends BaseModel
{
    protected ?string $moduleKey = 'pricing';

    protected $fillable = ['name','description','extra_attributes'];

    protected $casts = [
        'extra_attributes' => 'array',
    ];

    public function customers(): HasMany { return $this->hasMany(Customer::class); }
}
