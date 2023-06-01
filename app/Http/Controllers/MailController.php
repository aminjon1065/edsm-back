<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function getInboxMails()
    {
        $mails = Mail::where('to', auth()->user()->id)
            ->with(['openedMail' => function ($query) {
                $query->where('user_id', auth()->user()->id);
            }])
//            ->with('openedMail')
            ->with('document')
            ->paginate(20);
        return response()->json($mails, 200);
    }

    public function showMail($id)
    {
        return Mail::with('document')->where('id', $id)->get();
    }
}
