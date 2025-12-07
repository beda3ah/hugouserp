<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany, HasOne, MorphMany, MorphTo, MorphOne};


class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','title','body','data','read_at'];
    protected $casts = ['data'=>'array','read_at'=>'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
