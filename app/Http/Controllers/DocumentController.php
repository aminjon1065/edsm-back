<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\File;
use Illuminate\Http\Request;
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

        $result = Document::create([
            'title_document' => $request['title_document'],
            'description_document' => $request['description_document'],
            'content' => $request['content'],
            'region' => $request['region'],
            'status' => $request['status'],
            'created_user_id' => auth()->user()->id,
            'created_date' => date(now())
        ]);
        if ($result) {
            if ($request->hasFile('files')) {

            }
        }
        return 'test 2';
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
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }
}
