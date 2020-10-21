<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

// use jeremybrammer\laravelimagetos3package\laravelimagetos3package;
use jeremybrammer\laravelimagetos3package\ImageTos3Interface;

class ImageUploads extends Controller
{
    private $imagetos3;

    public function __construct(ImageTos3Interface $imagetos3){
        //dd($imagetos3);
        $this->imagetos3 = $imagetos3;
    }
    public function index(){
        $data = [
            "someData" => "This is from the ImageUploads controller.",
            "testData" => $this->imagetos3->test()
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

        $this->imagetos3->handUploadRequest($request);

        // $file = $request->file("image-upload-field");
        // dd($file->getSize());
        return "Nothing here yet.";
    }
}
