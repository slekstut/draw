<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilenameValidationController extends Controller
{
    public function validateFilename(Request $request)
    {
        $filename = $request->input('filename');
        dd($filename);
        $isValid = true; // perform validation logic here
        return response()->json(['isValid' => $isValid]);
    }
}
