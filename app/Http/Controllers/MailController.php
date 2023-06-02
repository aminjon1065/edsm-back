<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
    public function search(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        ['query' => $searchQuery, 'startDate' => $startDate, 'endDate' => $endDate] = $request->all();
        $userId = auth()->user()->id;
        $startDateFormatted = Carbon::parse($startDate)->startOfDay();
        $endDateFormatted = Carbon::parse($endDate)->endOfDay();
        $mails = Mail::where('to', $userId)
            ->whereHas('document', function ($query) use ($searchQuery, $startDateFormatted, $endDateFormatted) {
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
}
