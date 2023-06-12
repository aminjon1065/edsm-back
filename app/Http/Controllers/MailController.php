<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MailController extends Controller
{
    public function getInboxMails(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        ['query' => $searchQuery, 'startDate' => $startDate, 'endDate' => $endDate] = $request->all();
        $userId = auth()->user()->id;
        $mails = Mail::where('to', $userId)
            ->whereHas('document', function ($query) use ($searchQuery) {
                $query->where('title_document', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('description_document', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('region', 'LIKE', '%' . $searchQuery . '%');
            });
        if ($startDate && $endDate) {
            $startDateFormatted = Carbon::parse($startDate)->startOfDay();
            $endDateFormatted = Carbon::parse($endDate)->endOfDay();
            $mails->whereBetween('created_at', [$startDateFormatted, $endDateFormatted]);
        }
        $documents = $mails->with('document')->with('openedMail')->paginate(20);
        return response()->json($documents, 200);
    }

    public function showMail($uuid)
    {
        return Mail::with(['document', 'openedMail'])->where('uuid', $uuid)->first();
    }

    public function search(Request $request)
    {

    }

    public function redirectMail(Request $request): void
    {
        $uuid = $request->input('uuid');
        $mail = Mail::where('uuid', $uuid)->first();
        $newMail = new Mail();
        foreach ($request->input('to') as $item) {
            $newMail->to = $item;
        }
        $newMail->from = auth()->user()->id;
        $newMail->from_user_name = auth()->user()->full_name;
        foreach ($mail->documents as $document) {
            $newMail->documents()->attach($document->id);
        }
        $newMail->save();
    }
}
