<?php
// app/Http/Controllers/Admin/ContactController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(15);
        $unreadCount = Contact::where('is_read', false)->count();

        return view('admin.contacts.index', compact('contacts', 'unreadCount'));
    }

    public function show(Contact $contact)
    {
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Message supprimé avec succès');
    }

    public function markAsRead(Contact $contact)
    {
        $contact->update(['is_read' => true]);

        // Vérifier si la requête est AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message marqué comme lu'
            ]);
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Message marqué comme lu');
    }

    public function markAsUnread(Contact $contact)
    {
        $contact->update(['is_read' => false]);

        // Vérifier si la requête est AJAX
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message marqué comme non lu'
            ]);
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Message marqué comme non lu');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array'
        ]);

        Contact::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true]);
    }
}
