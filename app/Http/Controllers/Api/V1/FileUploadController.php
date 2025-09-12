<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\File;
use G4T\Swagger\Attributes\SwaggerSection;
use Illuminate\Http\Request;

#[SwaggerSection('File Upload Controller')]
class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        // validate file
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // get the file path
        $path = $request->file('file')->store('uploads', 'public');
        
        // create the file
        $file = File::create([
            'name' => $request->file('file')->getClientOriginalName(),
            'path' => $path,
        ]);

        // response
        return response()->json([
            'message' => 'File uploaded successfully!',
            'file'    => $file,
        ],201);
    }

    public function uploadMultiple(Request $request)
    {
        $uploadedFiles = [];
        
        $request->validate([
            'files'   => 'required',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        foreach ($request->file('files') as $file)
        {
            $path = $file->store('uploads','public');

            $saved = File::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
            ]);

            $uploadedFiles[] = [
                'file_id'   => $saved->id,
                'file_name' => $saved->name,
                'file_path' => assert('storage/' . $path),
            ];
        }

        return response()->json([
            'message' => 'Files uploaded successfully!',
            'files'   => $uploadedFiles,
        ],201);
    }

    /** Save in the storage > public > folders */
    // public function upload(Request $request)
    // {
    //     if (!$request->file('file')) {
    //         return response()->json([
    //             'message' => 'File not found!'
    //         ],404);
    //     }

    //     $file = $request->file('file')->store('uploads', 'public');

    //     return response()->json([
    //         'message' => 'File uploaded successfully!',
    //         'file'    => $file,
    //     ],200);
    // }
}
