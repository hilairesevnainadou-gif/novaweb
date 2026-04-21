<?php
// app/Models/Client.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        // Type de client
        'client_type',

        // Champs entreprise
        'company_name',
        'tax_number',
        'website',

        // Champs communs
        'name',
        'email',
        'phone',
        'gender',
        'address',
        'city',
        'country',
        'logo',
        'contact_name',
        'contact_position',

        // Facturation
        'invoice_by_email',
        'billing_cycle',

        // Statut
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'invoice_by_email' => 'boolean',
        'order' => 'integer',
        'client_type' => 'string'
    ];

    // ===== SCOPES =====

    /**
     * Filtrer les clients actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Filtrer les entreprises
     */
    public function scopeCompanies($query)
    {
        return $query->where('client_type', 'company');
    }

    /**
     * Filtrer les particuliers
     */
    public function scopeIndividuals($query)
    {
        return $query->where('client_type', 'individual');
    }

    /**
     * Tri par ordre puis par nom
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('name');
    }

    // ===== ACCESSORS =====

    /**
     * Nom affiché du client (entreprise ou particulier)
     */
    public function getDisplayNameAttribute()
    {
        if ($this->client_type === 'company') {
            return $this->company_name . ' (' . $this->name . ')';
        }
        return $this->name;
    }

    /**
     * Nom court du client
     */
    public function getShortNameAttribute()
    {
        if ($this->client_type === 'company') {
            return $this->company_name;
        }
        return $this->name;
    }

    /**
     * URL du logo
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    /**
     * Adresse complète formatée
     */
    public function getFullAddressAttribute()
    {
        $address = [];
        if ($this->address) $address[] = $this->address;
        if ($this->city) $address[] = $this->city;
        if ($this->country) $address[] = $this->country;
        return implode(', ', $address);
    }

    /**
     * Libellé du type de client
     */
    public function getClientTypeLabelAttribute()
    {
        return $this->client_type === 'company' ? 'Entreprise' : 'Particulier';
    }

    /**
     * Icône du type de client
     */
    public function getClientTypeIconAttribute()
    {
        return $this->client_type === 'company' ? 'fas fa-building' : 'fas fa-user';
    }

    /**
     * Genre formaté
     */
    public function getGenderLabelAttribute()
    {
        return match($this->gender) {
            'M' => 'Homme',
            'F' => 'Femme',
            default => 'Non spécifié'
        };
    }

    // ===== RELATIONS =====

    /**
     * Services souscrits par le client
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'client_service')
                    ->withPivot('id', 'service_name', 'price', 'start_date', 'end_date', 'status', 'notes')
                    ->withTimestamps();
    }

    /**
     * Services clients (pivot)
     */
    public function clientServices()
    {
        return $this->hasMany(ClientService::class);
    }

    /**
     * Factures du client
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Paiements du client
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ===== ACCESSEURS CALCULÉS =====

    /**
     * Total facturé
     */
    public function getTotalInvoicedAttribute()
    {
        return $this->invoices()->sum('total');
    }

    /**
     * Total payé
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    /**
     * Solde restant
     */
    public function getBalanceAttribute()
    {
        return $this->total_invoiced - $this->total_paid;
    }

    /**
     * Factures impayées
     */
    public function getOutstandingInvoicesAttribute()
    {
        return $this->invoices()->whereIn('status', ['sent', 'pending', 'partially_paid'])->sum('remaining_amount');
    }
}
