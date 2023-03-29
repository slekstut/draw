<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FilenameValidationController extends Controller
{
    public function validateFilename(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fileData' => ['required', 'max:25', 'alpha_num']
        ], [
            'fileData.required' => 'The fileData field is required.',
            'fileData.max' => 'The fileData field may not be greater than :max characters.',
            'fileData.alpha_num' => 'The fileData field must be alphanumeric.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'message' => 'Validation error.'], 422);
        }

        $isValid = true;
        return response()->json(['isValid' => $isValid]);
    }
}
