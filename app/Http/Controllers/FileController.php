<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function __construct()
    {

    }

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
    public function store($file, $documentId, $userId, $createdDate)
    {
//        dd($documentId);

        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '(' . auth()->user()->region . '_' . auth()->user()->department . ')' . '.' . $file->getClientOriginalExtension();
        $fileExtension = $file->getClientOriginalExtension();
        $size = $file->getSize();
        File::create([
            'name_file' => $fileName,
            'size'=>$size,
            'extension_file' => $fileExtension,
            'document_id' => $documentId,
            'created_user_id' => $userId,
            'created_date' => $createdDate
        ]);

        return 'test';
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        //
    }
}
