<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashSession extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'cashier_session_id');
    }

    public function getTotalSalesAttribute()
    {
        return $this->sales->sum(function ($sale) {
            return $sale->total_amount ?? 0;
        });
    }

    public function getExpectedBalanceAttribute()
    {
        return $this->opening_balance + $this->total_sales;
    }

    public function getDifferenceAttribute()
    {
        if (is_null($this->closing_balance)) {
            return 0;
        }

        return $this->closing_balance - $this->expected_balance;
    }
}
