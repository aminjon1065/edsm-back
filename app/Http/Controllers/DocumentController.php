<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $documents = Document::where('created_user_id', $user->id)->with('file')->paginate(25);
        return response()->json($documents, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->input('to')) {
            return response()->json('addres not found', 200);
        };
//        Создаём документ
        $document = Document::create([
            'uuid' => (string)\Str::uuid(),
            'title_document' => $request['title_document'],
            'description_document' => $request['description_document'],
            'content' => $request['content'],
            'region' => auth()->user()->region,
            'status' => $request['status'],
            'importance' => $request['importance'],
            'created_user_id' => auth()->user()->id,
            'created_date' => now(),
        ]);
        //Проверяем есть ли файлы и создаём и загружаем файлы
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                // Сохраняем файл
                $originalName = $filename = str_replace(' ', '_', $file->getClientOriginalName());
                $filename = auth()->user()->first_name . '_' . auth()->user()->last_name . '_' . auth()->user()->region . '_' . uniqid() . '_' . $originalName;
                $file->storeAs('public/documents/' . auth()->user()->region, $filename);
                // Создаем новый объект файла, связанный с документом
                $document->file()->create([
                    'name_file' => $filename,
                    'extension_file' => $file->getClientOriginalExtension(),
                    'document_id' => $document->id,
                    'created_user_id' => auth()->user()->id,
                    'created_date' => now(),
                ]);
            }
        }
        //Проверяем адреса
        $arrTo = $request->input('to');
        foreach ($arrTo as $item) {
            $document->mail()->create([
                'to' => $item,
                'from' => auth()->user()->id,
                'from_user_name' => auth()->user()->full_name,
                'document_id' => $document->id
            ]);
        }
        foreach ($arrTo as $key => $item) {
            $document->openedMail()->create([
                'mail_id' => $document->mail[$key]['id'],
                'user_id' => $item,
                'opened' => false
            ]);
        }
        return response()->json($document->openedMail, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title_document' => 'string',
            'description_document' => 'string',
            'region' => 'string',
            'status' => 'string',
            'files' => 'array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->toArray(),
            ], 422);
        }

        $document = Document::findOrFail($id);
        $document->title_document = $request->input('title_document', $document->title_document);
        $document->description_document = $request->input('description_document', $document->description_document);
        $document->content = $request->input('content', $document->content);
        $document->status = $request->input('status', $document->status);
        $document->updated_date = date(now());
        $document->updated_user_id = auth()->user()->id;
        $document->save();
        $files = $request->file('files');
        if ($files) {
//            $document->file()->delete();
            // Удаление предыдущих файлов
            foreach ($document->file as $file) {
//                dd($file->document->region);
                Storage::move('public/documents/' . $file->document->region . '/' . $file->name_file, 'public/trashed/' . date('d-m-Y-H-i-s') . '_' . $file->document->region . '_' . $file->name_file);
                $file->delete();
            }
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originalName = str_replace(' ', '_', $file->getClientOriginalName());
//                $dateValidation = str_replace(' ', '_', date('d-m-Y-H-i-s'));
                    $filename = auth()->user()->first_name . '_' . auth()->user()->last_name . '_' . auth()->user()->region . '_' . date('d-m-Y-H-i-s') . '_' . $originalName;
                    $file->storeAs('public/documents/' . auth()->user()->region, $filename);
                    $newFile = new File([
                        'name_file' => $filename,
                        'extension_file' => $file->getClientOriginalExtension(),
                        'document_id' => $document->id,
                        'created_user_id' => $document->created_user_id,
                        'updated_user_id' => auth()->user()->id,
                        'created_date' => $document->created_date,
                        'updated_date' => date(now()),
                    ]);
                    $newFile->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Документ успешно обновлен',
            'data' => $document
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $documents = Document::where(function ($queryBuilder) use ($query, $startDate, $endDate) {
            if ($query) {
                $queryBuilder->where(function ($queryBuilder) use ($query) {
                    $queryBuilder->where('title_document', 'LIKE', "%{$query}%")
                        ->orWhere('description_document', 'LIKE', "%{$query}%")
                        ->orWhere('content', 'LIKE', "%{$query}%")
                        ->orWhere('region', 'LIKE', "%{$query}%");
                });
            }

            if ($startDate && $endDate) {
                $queryBuilder->whereBetween('created_date', [$startDate, $endDate]);
            }
        })->get();

        return response()->json($documents);
    }
}
