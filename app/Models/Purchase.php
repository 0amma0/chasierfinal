<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id', 'invoice_number', 'purchase_date',
        'total_amount', 'paid_amount', 'status', 'note', 'user_id',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRemainingDebtAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }
}
