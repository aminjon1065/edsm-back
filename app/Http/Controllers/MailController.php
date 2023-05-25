<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Mail;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function getInboxMails()
    {
        $mails = Mail::where('to', auth()->user()->id)->with('openedMail')->with('document')->paginate(20);
        return response()->json(['data' => $mails], 200);
    }

    public function showMail($id)
    {
        $item = Mail::with('document')->with('file')->where('id', $id)->get();
        return $item;
    }
}
