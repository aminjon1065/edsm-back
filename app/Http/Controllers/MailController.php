<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function sendMail(Request $request)
    {
//        dd(json_encode($request->input('to')));

        $toArr[] = $request['to'];
        $toArrs = [...$toArr[0]];
//        return response()->json($toArrs);
//        if (!empty($toArr) && isset($toArr) && is_array($toArr)) {
        foreach ($toArrs as $key => $item) {
            $sendTo[$key] = $item;
        }
        foreach ($sendTo as $key => $to) {
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
