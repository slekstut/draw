<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DrawingController extends Controller
{
    public function saveDrawing(Request $request)
    {
        $dataUrl = $request->input('dataUrl');
        // dd($dataUrl);
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $dataUrl));
        $filename = 'drawing-' . time() . '.png';
        $path = public_path('/drawings/' . $filename);
        dd($path);
        file_put_contents($path, $imageData);
        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            $imageUrl = asset('storage/' . $path);
            return response()->json(['imageUrl' => $imageUrl, 'message' => 'File uploaded successfully.']);
        }
        return response()->json(['message' => 'No file uploaded.'], 400);
    }


    // public function store(Request $request)
    // {
    //     $dataUrl = $request->input('dataUrl');
    //     $fileData = $request->input('fileData');
    //     $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $dataUrl));
    //     $filename = uniqid() . '.png';
    //     file_put_contents(storage_path('app/public/' . $filename), $data);


    //     $imageUrl = asset('storage/' . $filename);
    //     return response()->json(['success' => true, 'imageUrl' => $imageUrl]); // include the URL in the response
    // }

}
