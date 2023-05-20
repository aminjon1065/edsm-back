<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {
        dd(json_encode($request->input('to')));
//        dd($request->to[0]);
        foreach ($request['to'] as $to) {
            Mail::create([
                'to' => $to,
                'from' => auth()->user()->id,
                'send_date' => now(),
                (new DocumentController())->store($request),
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
