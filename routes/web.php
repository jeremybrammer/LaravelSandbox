<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route to the ImageUploads controller methods.
Route::get('imageuploads', 'ImageUploads@index')->name('imageuploads.index');
Route::post('imageuploads/create', 'ImageUploads@store')->name('imageuploads.store');
Route::get("imageuploads/{imageUpload}/view", "ImageUploads@view")->name("imageuploads.view");
