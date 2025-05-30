<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ContactMessage::orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm)
                    ->orWhere('message', 'like', $searchTerm);
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'read') {
                $query->where('is_read', true);
            } elseif ($request->status == 'unread') {
                $query->where('is_read', false);
            }
        }

        $contactMessages = $query->paginate(15)->withQueryString();
        return view('admin.contact_messages.index', compact('contactMessages'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $contactMessage)
    {
        if (!$contactMessage->is_read) {
            $contactMessage->is_read = true;
            $contactMessage->save();
        }
        return view('admin.contact_messages.show', compact('contactMessage'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();
        Alert::success('Pesan Dihapus!', 'Pesan kontak telah berhasil dihapus.');
        return redirect()->route('admin.contact-messages.index');
    }
}
