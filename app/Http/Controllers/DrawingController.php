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
}
