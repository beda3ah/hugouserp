<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany, HasOne, MorphMany, MorphTo, MorphOne};


class RentalInvoice extends BaseModel
{
    protected ?string $moduleKey = 'rentals';

    protected $fillable = ['contract_id','code','period','due_date','amount','status','extra_attributes'];
    protected $casts = ['amount'=>'decimal:2','due_date'=>'date'];
    public function contract(): BelongsTo { return $this->belongsTo(RentalContract::class, 'contract_id'); }
    public function payments(): HasMany { return $this->hasMany(RentalPayment::class, 'invoice_id'); }
}
