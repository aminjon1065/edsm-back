<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendMail($documentId, $arrTo)
    {
//dd($arrTop);
        foreach ($arrTo as $key => $to) {
            Mail::create([
                'to' => $to,
                'from' => auth()->user()->id,
                'send_date' => now(),
                'document_id'=>$documentId
            ]);
        }
        return response()->json('success', 200);
    }

    public function getInboxMails()
    {
        $mails = Mail::where('to', auth()->user()->id)->with('document')->paginate(20);
        return response()->json($mails, 200);
    }

}
