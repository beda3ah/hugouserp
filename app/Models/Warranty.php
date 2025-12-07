<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany, HasOne, MorphMany, MorphTo, MorphOne};


class Warranty extends BaseModel
{
    protected ?string $moduleKey = 'vehicles';

    protected $fillable = ['vehicle_id','provider','start_date','end_date','notes','extra_attributes'];
    protected $casts = ['start_date'=>'date','end_date'=>'date'];
    public function vehicle(): BelongsTo { return $this->belongsTo(Vehicle::class); }
}
