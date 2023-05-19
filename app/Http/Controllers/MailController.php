<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function senMail(Request $request): void
    {
        foreach ($request['to'] as $to) {
            Mail::create([
                'to' => $to,
                'from' => auth()->user()->id,
                'send_date' => now()
            ]);
        }
    }

    public function getInboxMails()
    {
        $mails = Mail::where('to', auth()->user()->id)->with('document')->paginate(20);
        return response()->json($mails, 200);
    }
}
