<?php
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number',
        'client_id',
        'invoice_id',
        'client_service_id',
        'amount',
        'remaining_balance',
        'payment_type',
        'payment_method',
        'reference',
        'receipt_number',
        'payment_date',
        'status',
        'notes',
        'bank_details',
        'proof_file',
        'email_sent_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'payment_date' => 'date',
        'email_sent_at' => 'datetime'
    ];

    // ⚠️ SUPPRIMEZ cette méthode boot() :
    // protected static function boot() { ... }

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function clientService()
    {
        return $this->belongsTo(ClientService::class);
    }

    // Accesseurs
    public function getIsDepositAttribute()
    {
        return $this->payment_type === 'deposit';
    }

    public function getReceiptUrlAttribute()
    {
        return $this->proof_file ? asset('storage/' . $this->proof_file) : null;
    }
}
