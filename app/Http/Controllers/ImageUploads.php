<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
// Validator, File, Redirect;

class ImageUploads extends Controller
{
    public function index(){
        $data = [
            "someData" => "This is from the ImageUploads controller."
        ];
        return view("imageuploads.index", $data);
    }

    public function store(Request $request){

        $validated = $request->validate([
            "image-upload-field" => "required"
        ],
        [
            "image-upload-field.required" => "You must choose an image file."
        ]);

        $file = $request->file("image-upload-field");
        dd($file->getSize());
        return "Nothing here yet.";
    }
}
