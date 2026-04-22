<?php
// app/Models/InterventionExpense.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterventionExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'intervention_id',
        'description',
        'amount',
        'quantity',
        'total',
        'reference',
        'invoice_file'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'quantity' => 'integer',
        'total' => 'decimal:2'
    ];

    // Relations
    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }

    // Boot
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($expense) {
            $expense->total = $expense->amount * $expense->quantity;
        });
    }

    // Accesseur
    public function getInvoiceUrlAttribute()
    {
        return $this->invoice_file ? asset('storage/' . $this->invoice_file) : null;
    }
}
