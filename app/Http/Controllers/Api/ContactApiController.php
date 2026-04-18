<?php
// app/Http/Controllers/Api/ContactApiController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactApiController extends Controller
{
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $contact = Contact::create($request->all());

        // Envoyer un email de notification (optionnel)
        // Mail::to(config('mail.admin_email'))->send(new ContactNotification($contact));

        return response()->json([
            'success' => true,
            'message' => 'Votre message a été envoyé avec succès',
            'data' => $contact
        ]);
    }
}
