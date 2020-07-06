<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

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
            $file = $request->file('file');
            $dimensions = getimagesize($file);
            $name = $file->getClientOriginalName();
            
            $time_id = Carbon::now()->format('dmYhis');
            $F_name = pathinfo($name, PATHINFO_FILENAME);
            $filteredName = preg_replace('/([^a-zA-Z0-9])/', '', $F_name);
            $F_extension = pathinfo($name, PATHINFO_EXTENSION);
            $file_name = $filteredName . $time_id . '.' . $F_extension;

            $toDoJobs = [
                ['half' => 2], ['third' => 3], ['quarter' => 4], ['ratio' => ['210', '16:9']]
            ];
            
            $new_file = Image::make($file)->resize(($dimensions[0] / 3), ($dimensions[1] / 3), function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            Storage::disk('public')->put($file_name, $new_file->save($file_name));
            return 1;

            $path = $request->file('file')->store('default', 'public'); //carpeta, disco
            $file =  Storage::get('/public/' . $path); //storage/app/ . /public/
            //$path = Storage::disk('public')->exists('/public//'.'QHt09p47ourfaKRTnnPZMm2jMAqEtgzG5w2hOrkU.png');
            
            //return $path = Storage::get('/public/'.$path);
            //return $size = Storage::size('/public/' . $path);
            //return $file->getClientOriginalName();

            //$procesed_file = Image::make($request->file('file'))->encode('png', 90);
            //$path = Storage::disk('public')->put('nuevaimagenreducida.png', $procesed_file);
                       
            //$path = Storage::get(dirname(__DIR__, 3).'/public/storage/'.'QHt09p47ourfaKRTnnPZMm2jMAqEtgzG5w2hOrkU.png');
            return $path;
        }
    }
}
