<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImagesController extends Controller
{
    
    public function __construct()
    {
        //
    }

    public function make(Request $request)
    {
    //$reglas = array('image' => 'mimes:jpeg,png');
        $this->validate($request, [
            //'file' => 'required|dimensions:ratio=2/3',
            'file' => 'required|mimes:jpeg,png|dimensions:min_width=1024,min_height=1024|max:5000',
        ]);


        if ($request->hasFile('file'))
        {
            return 'se ha recibido un archivo';
        }
    }
}
