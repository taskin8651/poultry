<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Setting;
use App\Services\NotificationService;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    protected NotificationService $notifier;

    public function __construct(NotificationService $notifier)
    {
        $this->notifier = $notifier;
    }

    // 📄 Contact Page
    public function index()
    {
        $setting = Setting::first();

        return view('custom.contact', compact('setting'));
    }

    // 📩 Store Message
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:20',
            'message' => 'required|string|max:1000',
        ]);

        // 🔥 Save data
        Contact::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'message' => $request->message,
        ]);

        $this->notifier->toAdmins(
            'New contact message',
            "{$request->name} sent a message: " . Str::limit($request->message, 80),
            'info',
            route('admin.contacts.index')
        );

        return redirect()
            ->back()
            ->with('success', '✅ Your message has been sent successfully!');
    }
}