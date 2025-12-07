<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany, HasOne, MorphMany, MorphTo, MorphOne};


class HREmployee extends BaseModel
{
    protected ?string $moduleKey = 'hr';

    protected $fillable = ['branch_id','user_id','code','name','position','salary','is_active','extra_attributes'];
    protected $casts = ['salary'=>'decimal:2','is_active'=>'bool'];

    public function branch(): BelongsTo { return $this->belongsTo(Branch::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function attendances(): HasMany { return $this->hasMany(Attendance::class, 'employee_id'); }
    public function payrolls(): HasMany { return $this->hasMany(Payroll::class, 'employee_id'); }
}
