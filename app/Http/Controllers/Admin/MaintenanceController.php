<?php
// app/Http/Controllers/Admin/MaintenanceController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Intervention;
use App\Models\InterventionEvolution;
use App\Models\InterventionExpense;
use App\Models\Notification;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MaintenanceController extends Controller
{
    /**
     * Envoie une notification à un utilisateur spécifique
     */
    private function sendNotification($userId, $type, $title, $message, $url = null)
    {
        try {
            Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'is_read' => false
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('Erreur notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envoie une notification à tous les admins et super-admins
     */
    private function notifyAdmins($type, $title, $message, $url = null)
    {
        $admins = User::whereHas('roles', function($query) {
            $query->whereIn('name', ['super-admin', 'admin', 'support']);
        })->get();

        foreach ($admins as $admin) {
            $this->sendNotification($admin->id, $type, $title, $message, $url);
        }
    }

    /**
     * Dashboard principal de maintenance
     */
    public function dashboard(Request $request)
    {
        $stats = [
            'total_devices' => Device::count(),
            'active_devices' => Device::active()->count(),
            'under_maintenance' => Device::where('status', Device::STATUS_MAINTENANCE)->count(),
            'pending_interventions' => Intervention::pending()->count(),
            'in_progress_interventions' => Intervention::inProgress()->count(),
            'completed_this_month' => Intervention::completed()
                ->whereMonth('end_date', now()->month)
                ->whereYear('end_date', now()->year)
                ->count(),
            'total_cost_this_month' => Intervention::completed()
                ->whereMonth('end_date', now()->month)
                ->whereYear('end_date', now()->year)
                ->sum('actual_cost'),
            'avg_rating' => Intervention::where('client_rated', true)->avg('client_rating'),
            'urgent_interventions' => Intervention::highPriority()->where('status', '!=', Intervention::STATUS_COMPLETED)->count()
        ];

        $recentInterventions = Intervention::with(['device', 'client', 'technician'])
            ->latest()
            ->take(10)
            ->get();

        $technicians = User::role('technician')->withCount(['interventions' => function($q) {
            $q->whereIn('status', ['pending', 'in_progress']);
        }])->get();

        $devicesByStatus = Device::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.maintenance.dashboard', compact('stats', 'recentInterventions', 'technicians', 'devicesByStatus'));
    }

    /**
     * Gestion des appareils - Liste
     */
    public function devices(Request $request)
    {
        $query = Device::with('client', 'currentIntervention');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('reference', 'like', "%{$request->search}%")
                  ->orWhere('serial_number', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $devices = $query->ordered()->paginate(20);
        $clients = Client::active()->ordered()->get();
        $statuses = [
            Device::STATUS_OPERATIONAL => 'Opérationnel',
            Device::STATUS_MAINTENANCE => 'En maintenance',
            Device::STATUS_REPAIR => 'En réparation',
            Device::STATUS_OUT_OF_SERVICE => 'Hors service'
        ];
        $categories = [
            Device::CATEGORY_COMPUTER => 'Ordinateur',
            Device::CATEGORY_PRINTER => 'Imprimante',
            Device::CATEGORY_NETWORK => 'Réseau',
            Device::CATEGORY_PHONE => 'Téléphonie',
            Device::CATEGORY_OTHER => 'Autre'
        ];

        return view('admin.maintenance.devices.index', compact('devices', 'clients', 'statuses', 'categories'));
    }

    /**
     * Formulaire création appareil
     */
    public function createDevice()
    {
        $clients = Client::active()->ordered()->get();
        return view('admin.maintenance.devices.create', compact('clients'));
    }

    /**
     * Enregistrement appareil
     */
    public function storeDevice(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100|unique:devices',
            'category' => 'required|string|in:computer,printer,network,phone,other',
            'purchase_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:purchase_date',
            'status' => 'required|string|in:operational,maintenance,repair,out_of_service',
            'location' => 'nullable|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'technical_specs' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('devices', 'public');
        }

        $validated['technical_specs'] = $request->technical_specs ?? [];
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['reference'] = 'DEV-' . strtoupper(uniqid());

        $device = Device::create($validated);

        return redirect()->route('admin.maintenance.devices.show', $device)
            ->with('success', 'Appareil créé avec succès.');
    }

    /**
     * Détails d'un appareil
     */
    public function showDevice(Device $device)
    {
        $device->load(['client', 'interventions' => function($q) {
            $q->latest()->limit(20);
        }, 'interventions.technician']);

        $interventions = $device->interventions()
            ->with('technician')
            ->paginate(15);

        return view('admin.maintenance.devices.show', compact('device', 'interventions'));
    }

    /**
     * Formulaire modification appareil
     */
    public function editDevice(Device $device)
    {
        $clients = Client::active()->ordered()->get();
        return view('admin.maintenance.devices.edit', compact('device', 'clients'));
    }

    /**
     * Mise à jour appareil
     */
    public function updateDevice(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100|unique:devices,serial_number,' . $device->id,
            'category' => 'required|string|in:computer,printer,network,phone,other',
            'purchase_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date|after_or_equal:purchase_date',
            'status' => 'required|string|in:operational,maintenance,repair,out_of_service',
            'location' => 'nullable|string|max:255',
            'client_id' => 'nullable|exists:clients,id',
            'technical_specs' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            if ($device->image) {
                Storage::disk('public')->delete($device->image);
            }
            $validated['image'] = $request->file('image')->store('devices', 'public');
        }

        $validated['technical_specs'] = $request->technical_specs ?? [];
        $validated['is_active'] = $request->boolean('is_active', true);

        $device->update($validated);

        return redirect()->route('admin.maintenance.devices.show', $device)
            ->with('success', 'Appareil mis à jour avec succès.');
    }

    /**
     * Suppression appareil
     */
    public function destroyDevice(Device $device)
    {
        if ($device->image) {
            Storage::disk('public')->delete($device->image);
        }

        $device->delete();

        return redirect()->route('admin.maintenance.devices.index')
            ->with('success', 'Appareil supprimé avec succès.');
    }

    /**
     * Liste des interventions
     */
    public function interventions(Request $request)
    {
        $user = auth()->user();
        $isTechnician = $user->hasRole('technician');
        $isSupport = $user->hasRole('support');
        $isAdmin = $user->hasRole('admin');
        $isSuperAdmin = $user->hasRole('super-admin');

        $canViewAll = $isSuperAdmin || $isAdmin || $isSupport;

        $query = Intervention::with(['device', 'client', 'technician']);

        if ($isTechnician && !$canViewAll) {
            $query->where('technician_id', $user->id);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('intervention_number', 'like', "%{$request->search}%")
                  ->orWhere('title', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($canViewAll && $request->filled('technician_id')) {
            $query->where('technician_id', $request->technician_id);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $interventions = $query->orderByRaw("FIELD(priority, 'critical', 'urgent', 'high', 'medium', 'low')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        if ($isTechnician && !$canViewAll) {
            $stats = [
                'total' => Intervention::where('technician_id', $user->id)->count(),
                'pending' => Intervention::where('technician_id', $user->id)->pending()->count(),
                'in_progress' => Intervention::where('technician_id', $user->id)->inProgress()->count(),
                'completed_this_month' => Intervention::where('technician_id', $user->id)
                    ->completed()
                    ->whereMonth('end_date', now()->month)
                    ->whereYear('end_date', now()->year)
                    ->count(),
                'total_cost' => Intervention::where('technician_id', $user->id)
                    ->completed()
                    ->sum('actual_cost')
            ];
        } else {
            $stats = [
                'total' => Intervention::count(),
                'pending' => Intervention::pending()->count(),
                'in_progress' => Intervention::inProgress()->count(),
                'completed_this_month' => Intervention::completed()
                    ->whereMonth('end_date', now()->month)
                    ->whereYear('end_date', now()->year)
                    ->count(),
                'total_cost' => Intervention::completed()->sum('actual_cost')
            ];
        }

        $technicians = collect();
        if ($canViewAll) {
            $technicians = User::role('technician')->get();
        }

        $clients = Client::active()->ordered()->get();
        $statuses = [
            Intervention::STATUS_PENDING => 'En attente',
            Intervention::STATUS_APPROVED => 'Approuvée',
            Intervention::STATUS_IN_PROGRESS => 'En cours',
            Intervention::STATUS_COMPLETED => 'Terminée',
            Intervention::STATUS_CANCELLED => 'Annulée'
        ];
        $priorities = [
            Intervention::PRIORITY_LOW => 'Basse',
            Intervention::PRIORITY_MEDIUM => 'Moyenne',
            Intervention::PRIORITY_HIGH => 'Haute',
            Intervention::PRIORITY_URGENT => 'Urgente',
            Intervention::PRIORITY_CRITICAL => 'Critique'
        ];

        return view('admin.maintenance.interventions.index', compact(
            'interventions', 'technicians', 'clients', 'statuses', 'priorities', 'stats'
        ));
    }

    /**
     * Formulaire création intervention
     */
    public function createIntervention(Request $request)
    {
        $devices = Device::active()->with('client')->get();
        $technicians = User::role('technician')->get();
        $clients = Client::active()->ordered()->get();

        $selectedDevice = null;
        if ($request->filled('device_id')) {
            $selectedDevice = Device::find($request->device_id);
        }

        return view('admin.maintenance.interventions.create', compact('devices', 'technicians', 'clients', 'selectedDevice'));
    }

    /**
     * Enregistrement intervention
     */
    public function storeIntervention(Request $request)
    {
        $user = auth()->user();
        $isTechnician = $user->hasRole('technician');
        $isAdmin = $user->hasRole('admin') || $user->hasRole('super-admin') || $user->hasRole('support');

        $deviceIds = explode(',', $request->device_ids);
        $deviceIds = array_filter($deviceIds);

        if (empty($deviceIds)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['device_ids' => 'Veuillez sélectionner au moins un appareil']);
        }

        $firstDevice = Device::find($deviceIds[0]);

        $validated = $request->validate([
            'device_ids' => 'required|string',
            'title' => 'required|string|max:255',
            'problem_type' => 'nullable|string|in:hardware,software,network,electrical,mechanical,other',
            'problem_description' => 'nullable|string',
            'priority' => 'required|string|in:low,medium,high,urgent,critical',
            'evolution_level' => 'required|integer|min:1|max:5',
            'estimated_cost' => 'nullable|numeric|min:0',
            'scheduled_date' => 'nullable|date',
            'notes' => 'nullable|string'
        ]);

        $status = Intervention::STATUS_APPROVED;
        $technicianId = null;

        if ($isTechnician) {
            $technicianId = $user->id;
        }

        $intervention = Intervention::create([
            'device_id' => $firstDevice->id,
            'client_id' => $firstDevice->client_id,
            'technician_id' => $technicianId,
            'title' => $validated['title'],
            'problem_type' => $validated['problem_type'] ?? null,
            'problem_description' => $validated['problem_description'] ?? null,
            'priority' => $validated['priority'],
            'evolution_level' => $validated['evolution_level'],
            'estimated_cost' => $validated['estimated_cost'] ?? 0,
            'scheduled_date' => $validated['scheduled_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => $status,
            'actual_cost' => 0
        ]);

        if (count($deviceIds) > 1) {
            $parentId = $intervention->id;
            for ($i = 1; $i < count($deviceIds); $i++) {
                $device = Device::find($deviceIds[$i]);
                Intervention::create([
                    'device_id' => $device->id,
                    'client_id' => $device->client_id,
                    'technician_id' => $technicianId,
                    'title' => $validated['title'],
                    'problem_type' => $validated['problem_type'] ?? null,
                    'problem_description' => $validated['problem_description'] ?? null,
                    'priority' => $validated['priority'],
                    'evolution_level' => $validated['evolution_level'],
                    'estimated_cost' => $validated['estimated_cost'] ?? 0,
                    'scheduled_date' => $validated['scheduled_date'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                    'status' => $status,
                    'actual_cost' => 0,
                    'parent_intervention_id' => $parentId
                ]);
            }
        }

        if ($firstDevice && $firstDevice->status !== Device::STATUS_OUT_OF_SERVICE) {
            $firstDevice->update(['status' => Device::STATUS_MAINTENANCE]);
        }

        if ($isAdmin) {
            $this->notifyAdmins(
                'intervention',
                'Nouvelle intervention creee',
                "L'intervention {$intervention->intervention_number} a ete creee par " . $user->name,
                route('admin.maintenance.interventions.show', $intervention)
            );

            if ($technicianId) {
                $technician = User::find($technicianId);
                $this->sendNotification(
                    $technicianId,
                    'intervention',
                    'Nouvelle intervention assignee',
                    "Vous avez ete assigne a l'intervention {$intervention->intervention_number}",
                    route('admin.maintenance.interventions.show', $intervention)
                );
            }
        }

        if ($isTechnician) {
            $this->notifyAdmins(
                'intervention',
                'Nouvelle intervention creee par un technicien',
                "Le technicien {$user->name} a cree l'intervention {$intervention->intervention_number} et s'y est auto-assigne",
                route('admin.maintenance.interventions.show', $intervention)
            );

            $this->sendNotification(
                $user->id,
                'intervention',
                'Intervention creee avec succes',
                "Vous avez cree et ete assigne a l'intervention {$intervention->intervention_number}",
                route('admin.maintenance.interventions.show', $intervention)
            );
        }

        $successMessage = $isTechnician
            ? "Intervention creee avec succes. Vous etes automatiquement assigne a cette intervention."
            : "Intervention creee avec succes.";

        return redirect()->route('admin.maintenance.interventions.show', $intervention)
            ->with('success', $successMessage);
    }

    /**
     * Détails d'une intervention
     */
    public function showIntervention(Intervention $intervention)
    {
        $intervention->load(['device', 'client', 'technician', 'evolutionHistory.user', 'expenses']);

        $evolutionLevels = [
            1 => 'Niveau 1 - Diagnostic',
            2 => 'Niveau 2 - Intervention simple',
            3 => 'Niveau 3 - Intervention complexe',
            4 => 'Niveau 4 - Intervention majeure',
            5 => 'Niveau 5 - Intervention critique'
        ];

        $statuses = [
            Intervention::STATUS_PENDING => 'En attente',
            Intervention::STATUS_APPROVED => 'Approuvee',
            Intervention::STATUS_IN_PROGRESS => 'En cours',
            Intervention::STATUS_COMPLETED => 'Terminee',
            Intervention::STATUS_CANCELLED => 'Annulee'
        ];

        return view('admin.maintenance.interventions.show', compact('intervention', 'evolutionLevels', 'statuses'));
    }

    /**
     * Formulaire modification intervention
     */
    public function editIntervention(Intervention $intervention)
    {
        $devices = Device::active()->with('client')->get();
        $technicians = User::role('technician')->get();
        $clients = Client::active()->ordered()->get();

        return view('admin.maintenance.interventions.edit', compact('intervention', 'devices', 'technicians', 'clients'));
    }

    /**
     * Mise à jour intervention
     */
    public function updateIntervention(Request $request, Intervention $intervention)
    {
        $oldStatus = $intervention->status;
        $oldTechnicianId = $intervention->technician_id;
        $oldEvolutionLevel = $intervention->evolution_level;

        $validated = $request->validate([
            'device_id' => 'required|exists:devices,id',
            'client_id' => 'required|exists:clients,id',
            'technician_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'problem_description' => 'nullable|string',
            'solution' => 'nullable|string',
            'priority' => 'required|string|in:low,medium,high,urgent,critical',
            'estimated_cost' => 'nullable|numeric|min:0',
            'actual_cost' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'scheduled_date' => 'nullable|date',
            'duration_minutes' => 'nullable|integer|min:0',
            'parts_used' => 'nullable|array',
            'notes' => 'nullable|string'
        ]);

        if ($request->has('evolution_level') && $request->evolution_level != $intervention->evolution_level) {
            $this->updateEvolutionLevel($intervention, $request->evolution_level, $request->evolution_reason);
            $validated['evolution_level'] = $request->evolution_level;

            if ($intervention->technician_id) {
                $this->sendNotification(
                    $intervention->technician_id,
                    'intervention',
                    'Changement de niveau - Intervention ' . $intervention->intervention_number,
                    "Le niveau d'evolution est passe de {$oldEvolutionLevel} a {$request->evolution_level}",
                    route('admin.maintenance.interventions.show', $intervention)
                );
            }

            $this->notifyAdmins(
                'intervention',
                'Changement de niveau - Intervention ' . $intervention->intervention_number,
                "Le niveau d'evolution est passe de {$oldEvolutionLevel} a {$request->evolution_level}",
                route('admin.maintenance.interventions.show', $intervention)
            );
        }

        $intervention->update($validated);

        if ($oldStatus !== $intervention->status) {
            if ($intervention->technician_id) {
                $this->sendNotification(
                    $intervention->technician_id,
                    'intervention',
                    'Statut modifie - Intervention ' . $intervention->intervention_number,
                    "Le statut est passe de " . $this->getStatusLabel($oldStatus) . " a " . $intervention->status_label,
                    route('admin.maintenance.interventions.show', $intervention)
                );
            }

            $this->notifyAdmins(
                'intervention',
                'Changement de statut - Intervention ' . $intervention->intervention_number,
                "Le statut est passe de " . $this->getStatusLabel($oldStatus) . " a " . $intervention->status_label,
                route('admin.maintenance.interventions.show', $intervention)
            );
        }

        if ($oldTechnicianId !== $intervention->technician_id && $intervention->technician_id) {
            $technician = User::find($intervention->technician_id);

            $this->sendNotification(
                $intervention->technician_id,
                'intervention',
                'Nouvelle intervention assignee',
                "Vous avez ete assigne a l'intervention {$intervention->intervention_number}",
                route('admin.maintenance.interventions.show', $intervention)
            );

            $this->notifyAdmins(
                'intervention',
                'Technicien assigne - Intervention ' . $intervention->intervention_number,
                "Le technicien {$technician->name} a ete assigne a l'intervention",
                route('admin.maintenance.interventions.show', $intervention)
            );

            if ($oldTechnicianId) {
                $oldTechnician = User::find($oldTechnicianId);
                $this->sendNotification(
                    $oldTechnicianId,
                    'intervention',
                    'Desassignation - Intervention ' . $intervention->intervention_number,
                    "Vous n'etes plus assigne a l'intervention {$intervention->intervention_number}",
                    route('admin.maintenance.interventions.show', $intervention)
                );
            }
        }

        if ($intervention->status === Intervention::STATUS_COMPLETED && $intervention->device) {
            if ($intervention->device->status === Device::STATUS_MAINTENANCE) {
                $intervention->device->update(['status' => Device::STATUS_OPERATIONAL]);
            }

            if ($intervention->technician_id) {
                $this->sendNotification(
                    $intervention->technician_id,
                    'intervention',
                    'Intervention terminee - ' . $intervention->intervention_number,
                    "L'intervention a ete marquee comme terminee",
                    route('admin.maintenance.interventions.show', $intervention)
                );
            }

            $this->notifyAdmins(
                'intervention',
                'Intervention terminee - ' . $intervention->intervention_number,
                "L'intervention a ete marquee comme terminee",
                route('admin.maintenance.interventions.show', $intervention)
            );
        }

        return redirect()->route('admin.maintenance.interventions.show', $intervention)
            ->with('success', 'Intervention mise a jour avec succes.');
    }

    /**
     * Changement de statut d'une intervention
     */
    public function changeInterventionStatus(Request $request, Intervention $intervention)
    {
        $request->validate([
            'status' => 'required|string|in:pending,approved,in_progress,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $oldStatus = $intervention->status;
        $newStatus = $request->status;

        $updateData = [
            'status' => $newStatus,
            'notes' => $request->notes ? $intervention->notes . "\n\n" . $request->notes : $intervention->notes
        ];

        if ($newStatus === Intervention::STATUS_COMPLETED && !$intervention->end_date) {
            $updateData['end_date'] = now();

            if ($intervention->start_date) {
                $updateData['duration_minutes'] = now()->diffInMinutes($intervention->start_date);
            }
        }

        if ($newStatus === Intervention::STATUS_IN_PROGRESS && !$intervention->start_date) {
            $updateData['start_date'] = now();
        }

        $intervention->update($updateData);

        $statusMessages = [
            'pending' => 'En attente',
            'approved' => 'Approuvee',
            'in_progress' => 'En cours',
            'completed' => 'Terminee',
            'cancelled' => 'Annulee'
        ];

        $newStatusLabel = $statusMessages[$newStatus] ?? $newStatus;
        $oldStatusLabel = $this->getStatusLabel($oldStatus);

        if ($intervention->technician_id) {
            $this->sendNotification(
                $intervention->technician_id,
                'intervention',
                'Statut modifie - Intervention ' . $intervention->intervention_number,
                "Le statut est passe de {$oldStatusLabel} a {$newStatusLabel}",
                route('admin.maintenance.interventions.show', $intervention)
            );
        }

        $this->notifyAdmins(
            'intervention',
            'Changement de statut - Intervention ' . $intervention->intervention_number,
            "Le statut est passe de {$oldStatusLabel} a {$newStatusLabel} par " . auth()->user()->name,
            route('admin.maintenance.interventions.show', $intervention)
        );

        if ($newStatus === Intervention::STATUS_COMPLETED && $intervention->device) {
            if ($intervention->device->status === Device::STATUS_MAINTENANCE) {
                $intervention->device->update(['status' => Device::STATUS_OPERATIONAL]);
            }

            $this->notifyAdmins(
                'intervention',
                'Intervention terminee - ' . $intervention->intervention_number,
                "L'intervention a ete marquee comme terminee par " . auth()->user()->name,
                route('admin.maintenance.interventions.show', $intervention)
            );
        }

        if ($newStatus === Intervention::STATUS_CANCELLED && $intervention->device) {
            if ($intervention->device->status === Device::STATUS_MAINTENANCE) {
                $intervention->device->update(['status' => Device::STATUS_OPERATIONAL]);
            }

            $this->notifyAdmins(
                'intervention',
                'Intervention annulee - ' . $intervention->intervention_number,
                "L'intervention a ete annulee par " . auth()->user()->name,
                route('admin.maintenance.interventions.show', $intervention)
            );
        }

        return redirect()->route('admin.maintenance.interventions.show', $intervention)
            ->with('success', 'Statut mis a jour avec succes : ' . $intervention->status_label);
    }

    /**
     * Récupère le libellé du statut
     */
    private function getStatusLabel($status)
    {
        return match($status) {
            Intervention::STATUS_PENDING => 'En attente',
            Intervention::STATUS_APPROVED => 'Approuvee',
            Intervention::STATUS_IN_PROGRESS => 'En cours',
            Intervention::STATUS_COMPLETED => 'Terminee',
            Intervention::STATUS_CANCELLED => 'Annulee',
            default => 'Inconnu'
        };
    }

    /**
     * Mise à jour du niveau d'évolution
     */
    protected function updateEvolutionLevel(Intervention $intervention, $newLevel, $reason = null)
    {
        $oldLevel = $intervention->evolution_level;

        InterventionEvolution::create([
            'intervention_id' => $intervention->id,
            'user_id' => auth()->id(),
            'previous_level' => $oldLevel,
            'new_level' => $newLevel,
            'reason' => $reason,
            'notes' => "Changement de niveau d'intervention"
        ]);

        $intervention->update(['evolution_level' => $newLevel]);
    }

    /**
     * Ajout d'une dépense
     */
    public function addExpense(Request $request, Intervention $intervention)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'reference' => 'nullable|string|max:100',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        if ($request->hasFile('invoice_file')) {
            $validated['invoice_file'] = $request->file('invoice_file')->store('intervention-invoices', 'public');
        }

        $expense = $intervention->expenses()->create($validated);

        $totalExpenses = $intervention->expenses()->sum('total');
        $intervention->update(['actual_cost' => $intervention->estimated_cost + $totalExpenses]);

        $this->notifyAdmins(
            'expense',
            'Nouvelle depense - Intervention ' . $intervention->intervention_number,
            "Une depense de {$validated['amount']} FCFA a ete ajoutee par " . auth()->user()->name,
            route('admin.maintenance.interventions.show', $intervention)
        );

        return redirect()->back()->with('success', 'Depense ajoutee avec succes.');
    }

    /**
     * Suppression d'une dépense
     */
    public function deleteExpense(Intervention $intervention, InterventionExpense $expense)
    {
        if ($expense->invoice_file) {
            Storage::disk('public')->delete($expense->invoice_file);
        }

        $expense->delete();

        $totalExpenses = $intervention->expenses()->sum('total');
        $intervention->update(['actual_cost' => $intervention->estimated_cost + $totalExpenses]);

        return redirect()->back()->with('success', 'Depense supprimee avec succes.');
    }

    /**
     * Affectation d'un technicien
     */
    public function assignTechnician(Request $request, Intervention $intervention)
    {
        $request->validate([
            'technician_id' => 'required|exists:users,id'
        ]);

        $technician = User::find($request->technician_id);
        $oldTechnicianId = $intervention->technician_id;

        $intervention->update([
            'technician_id' => $technician->id,
            'status' => Intervention::STATUS_APPROVED
        ]);

        $this->sendNotification(
            $technician->id,
            'intervention',
            'Nouvelle intervention assignee',
            "Vous avez ete assigne a l'intervention {$intervention->intervention_number}",
            route('admin.maintenance.interventions.show', $intervention)
        );

        $this->notifyAdmins(
            'intervention',
            'Technicien assigne - Intervention ' . $intervention->intervention_number,
            "Le technicien {$technician->name} a ete assigne a l'intervention par " . auth()->user()->name,
            route('admin.maintenance.interventions.show', $intervention)
        );

        if ($oldTechnicianId && $oldTechnicianId != $technician->id) {
            $oldTechnician = User::find($oldTechnicianId);
            $this->sendNotification(
                $oldTechnicianId,
                'intervention',
                'Desassignation - Intervention ' . $intervention->intervention_number,
                "Vous n'etes plus assigne a l'intervention {$intervention->intervention_number}",
                route('admin.maintenance.interventions.show', $intervention)
            );
        }

        return redirect()->route('admin.maintenance.interventions.show', $intervention)
            ->with('success', 'Technicien ' . $technician->name . ' affecte avec succes a l\'intervention ' . $intervention->intervention_number);
    }

    /**
     * Évaluation client
     */
    public function clientRating(Request $request, Intervention $intervention)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000'
        ]);

        $intervention->update([
            'client_rated' => true,
            'client_rating' => $request->rating,
            'client_feedback' => $request->feedback
        ]);

        if ($intervention->technician_id) {
            $this->sendNotification(
                $intervention->technician_id,
                'rating',
                'Nouvelle evaluation - Intervention ' . $intervention->intervention_number,
                "Le client a note l'intervention {$request->rating}/5",
                route('admin.maintenance.interventions.show', $intervention)
            );
        }

        $this->notifyAdmins(
            'rating',
            'Nouvelle evaluation - Intervention ' . $intervention->intervention_number,
            "Le client a note l'intervention {$request->rating}/5",
            route('admin.maintenance.interventions.show', $intervention)
        );

        return response()->json([
            'success' => true,
            'message' => 'Merci pour votre evaluation !'
        ]);
    }

    /**
     * Statistiques détaillées
     */
    public function statistics(Request $request)
    {
        $year = $request->get('year', now()->year);

        $interventionsByMonth = Intervention::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            )
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get()
            ->keyBy('month');

        $costsByMonth = Intervention::select(
                DB::raw('MONTH(end_date) as month'),
                DB::raw('SUM(estimated_cost) as estimated'),
                DB::raw('SUM(actual_cost) as actual')
            )
            ->where('status', Intervention::STATUS_COMPLETED)
            ->whereYear('end_date', $year)
            ->groupBy(DB::raw('MONTH(end_date)'))
            ->get()
            ->keyBy('month');

        $topTechnicians = User::role('technician')
            ->withCount(['interventions' => function($q) {
                $q->where('status', Intervention::STATUS_COMPLETED);
            }])
            ->withSum(['interventions' => function($q) {
                $q->where('status', Intervention::STATUS_COMPLETED);
            }], 'duration_minutes')
            ->having('interventions_count', '>', 0)
            ->orderBy('interventions_count', 'desc')
            ->limit(5)
            ->get();

        $commonProblems = Intervention::select('problem_type', DB::raw('COUNT(*) as total'))
            ->whereNotNull('problem_type')
            ->groupBy('problem_type')
            ->orderBy('total', 'desc')
            ->get();

        $avgDurationByProblem = Intervention::select('problem_type', DB::raw('AVG(duration_minutes) as avg_duration'))
            ->whereNotNull('problem_type')
            ->whereNotNull('duration_minutes')
            ->where('status', Intervention::STATUS_COMPLETED)
            ->groupBy('problem_type')
            ->get();

        return view('admin.maintenance.statistics', compact(
            'year', 'interventionsByMonth', 'costsByMonth',
            'topTechnicians', 'commonProblems', 'avgDurationByProblem'
        ));
    }

    /**
     * Export des interventions
     */
    public function exportInterventions(Request $request)
    {
        $query = Intervention::with(['device', 'client', 'technician']);

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $interventions = $query->get();

        $filename = 'interventions_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($interventions) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'N° Intervention', 'Appareil', 'Client', 'Technicien', 'Statut',
                'Priorite', 'Niveau', 'Cout estime', 'Cout reel', 'Date creation', 'Date fin'
            ]);

            foreach ($interventions as $intervention) {
                fputcsv($file, [
                    $intervention->intervention_number,
                    $intervention->device->name ?? 'N/A',
                    $intervention->client->name ?? 'N/A',
                    $intervention->technician->name ?? 'Non assigne',
                    $intervention->status_label,
                    $intervention->priority_label,
                    $intervention->evolution_level,
                    $intervention->estimated_cost,
                    $intervention->actual_cost,
                    $intervention->created_at->format('d/m/Y'),
                    $intervention->end_date?->format('d/m/Y') ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
