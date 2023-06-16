<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Mail;
use App\Models\ReplyTo;
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
            })->whereDoesntHave('document.mail', function ($query) {
                $query->where('reply', true);
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
        return Mail::with(['document', 'openedMail', 'mailReply'])->where('uuid', $uuid)->first();
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

    public function replyTo(Request $request, $uuid)
    {
        $mailReply = Mail::where('uuid', $uuid)->first();

        if (!$mailReply) {
            return response()->json(['error' => 'Mail not found'], 404);
        }

        $authUser = auth()->user();
        $region = $authUser->region;
        $department = $authUser->department;
        $createdUserId = $authUser->id;
        $fullName = $authUser->full_name;

        $titleDocument = $request->input('title_document');
        $descriptionDocument = $request->input('description_document');
        $content = $request->input('content');
        $type = $request->input('type');
        $importance = $request->input('importance');

        $document = Document::create([
            'uuid' => (string)\Str::uuid(),
            'title_document' => $titleDocument,
            'description_document' => $descriptionDocument,
            'content' => $content,
            'region' => $region,
            'department' => $department,
            'status' => 'pending',
            'type' => $type,
            'importance' => $importance,
            'created_user_id' => $createdUserId,
            'created_date' => now(),
        ]);

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                // Сохраняем файл
                $originalName = $filename = str_replace(' ', '_', $file->getClientOriginalName());
                $filename = $fullName . '_' . $region . '_' . uniqid() . '_' . $originalName;
                $file->storeAs('public/documents/' . $region, $filename);
                // Создаем новый объект файла, связанный с документом
                $document->file()->create([
                    'name_file' => $filename,
                    'size' => round($file->getSize() / 1024 / 1024, 2),
                    'extension_file' => $file->getClientOriginalExtension(),
                    'document_id' => $document->id,
                    'created_user_id' => $createdUserId,
                    'created_date' => now(),
                ]);
            }
        }

        $createdMail = $document->mail()->create([
            'uuid' => (string)\Str::uuid(),
            'to' => $request['to'],
            'reply' => true,
            'from' => $authUser->id,
            'from_user_name' => $fullName,
            'document_id' => $document->id
        ]);

        $createdDate = now();

        $document->history()->create([
            'document_id' => $document->id,
            'sender_id' => $authUser->id,
            'recipient_id' => $request['to'],
            'status' => 'pending',
            'created_date' => $createdDate,
        ]);

        $document->openedMail()->create([
            'mail_id' => $createdMail->id,
            'user_id' => $request['to'],
            'opened' => false
        ]);

        $replied = ReplyTo::create([
            'from' => $mailReply->to,
            'reply_to' => $mailReply->from,
            'mail_reply_id' => $createdMail->id,
            'mail_id' => $mailReply->id,
            'document_id' => $document->id
        ]);

        return response($replied, 201);
    }

}
