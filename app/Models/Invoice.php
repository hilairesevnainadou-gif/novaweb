<?php
// app/Models/Invoice.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'client_id',
        'service_id',
        'client_service_id',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount',
        'total',
        'paid_amount',
        'remaining_amount',
        'issue_date',
        'due_date',
        'paid_date',
        'status',
        'type',
        'description',
        'notes',
        'terms',
        'bank_name',
        'bank_account',
        'bank_iban',
        'email_sent_at',
        'email_retries'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'email_sent_at' => 'datetime'
    ];

    // Génération automatique du numéro de facture
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (!$invoice->invoice_number) {
                $year = date('Y');
                $month = date('m');
                $lastInvoice = static::whereYear('created_at', $year)->count();
                $invoice->invoice_number = 'INV-' . $year . $month . '-' . str_pad($lastInvoice + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Scopes
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['sent', 'pending', 'partially_paid']);
    }

    public function scopeOverdue($query)
    {
        return $query->whereIn('status', ['sent', 'pending', 'partially_paid'])
                     ->where('due_date', '<', now());
    }

    // Relations
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function clientService()
    {
        return $this->belongsTo(ClientService::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Accesseurs
    public function getIsPaidAttribute()
    {
        return $this->status === 'paid';
    }

    public function getIsOverdueAttribute()
    {
        return $this->due_date < now() && !$this->is_paid;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->total <= 0) return 0;
        return round(($this->paid_amount / $this->total) * 100);
    }
}
