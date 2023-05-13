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
    public function index()
    {
        //
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
        $document = Document::create([
            'title_document' => $request->input('title_document'),
            'description_document' => $request->input('description_document'),
            'content' => $request->input('content'),
            'region' => auth()->user()->region,
            'status' => $request->input('status'),
            'created_user_id' => auth()->user()->id,
            'created_date' => now(),
        ]);
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                // Сохраняем файл
                $originalName = $filename = str_replace(' ', '_', $file->getClientOriginalName());
//                $dateValidation = str_replace(' ', '_', date('d-m-Y-H-i-s'));
                $filename = auth()->user()->first_name . '_' . auth()->user()->last_name . '_' . auth()->user()->region . '_' . date('d-m-Y-H-i-s') . '_' . $originalName;
                $file->storeAs('public/documents/' . auth()->user()->region, $filename);

                // Создаем новый объект файла, связанный с документом
                $document->file()->create([
                    'name_file' => $filename,
                    'extension_file' => $file->getClientOriginalExtension(),
                    'document_id' => $document->id,
                    'created_user_id' => auth()->user()->id,
                    'created_date' => date(now())
                ]);
            }
        }
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

        $document->save();

        $files = $request->file('files');

        if ($files) {
            // Удаление предыдущих файлов
            foreach ($document->files as $file) {
                Storage::delete('public/documents/' . $file->name_file);
                $file->delete();
            }

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $originalName = $filename = str_replace(' ', '_', $file->getClientOriginalName());
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
}
