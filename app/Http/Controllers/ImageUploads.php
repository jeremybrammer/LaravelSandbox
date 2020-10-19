<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploads extends Controller
{
    public function index(){
        $data = [
            "someData" => "This is from the ImageUploads controller."
        ];
        return view("imageuploads.index", $data);
    }
}
