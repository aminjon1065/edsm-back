<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use App\Models\OpenedMail;
use Illuminate\Http\Request;

class OpenedMailController extends Controller
{
    public function showed($id):void
    {
        OpenedMail::where('id', $id)->update(['opened' => true]);
    }
}
