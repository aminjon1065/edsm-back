<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function getInboxMails()
    {
//        $mails = Mail::where('from', auth()->user()->id)->with('document')->paginate(20);
//        $mails = Mail::with('document')->where('from', auth()->user()->id)->get();
        $mails = Mail::with('document')->get();
        return response()->json(['data' => $mails], 200);
    }

    public function showMail($id)
    {
        $item = Mail::with('document')->with('file')->where('id', $id)->get();

        return $item;
    }

}
