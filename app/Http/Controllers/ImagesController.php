<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Dropbox\Client;
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
            'file' => 'required|mimes:jpeg,png|dimensions:min_width=256,min_height=256|max:5000',
        ]);

        
        if ($request->hasFile('file'))
        {
            $file = $request->file('file');
            $dimensions = getimagesize($file);
            $name = $file->getClientOriginalName();
            
            
            $F_name = pathinfo($name, PATHINFO_FILENAME);
            $filteredName = preg_replace('/([^a-zA-Z0-9])/', '', $F_name);
            $F_extension = pathinfo($name, PATHINFO_EXTENSION);
            
            
            $data = [];
            $image_content = '';
            $toDoJobs = ['half' => 2, 'third' => 3, 'quarter' => 4 ];
            $toDoTumbs = [256, 128, 64];
            $dropbox = Storage::disk('dropbox')->getDriver()->getAdapter()->getClient();
            
            foreach ($toDoJobs as $key => $value){
                //dynamic name;
                $time_id = Carbon::now()->format('dmYhis');
                $file_name = $filteredName.$time_id.'_'.$key.'.'.$F_extension;

                //resize
                $new_file = Image::make($file)->resize(($dimensions[0] / $value), ($dimensions[1] / $value), function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                //get quarter content and save for next foreach
                if ($key == 'quarter'){
                    $image_content = $new_file;
                }

                //storing image on dropbox
                Storage::disk('dropbox')->put('/'.$file_name, $new_file->stream());
                $response = $dropbox->createSharedLinkWithSettings('/'.$file_name, ["requested_visibility" => "public"]);
                

                $data[] = [
                    'url' => str_replace('dl=0', 'raw=1', $response['url']),
                    'name' => $response['name'], 'size' => $response['size'],
                    'dimentions' => number_format($dimensions[0] / $value, 0) .'x'. number_format($dimensions[1] / $value, 0),
                    'extension' => $F_extension,
                ];
                
                //$uploaded_file = Storage::disk('public')->put($file_name, $new_file->stream());
                //$urls[$key] = Storage::url($file_name); local storage
            }

            foreach($toDoTumbs as $value){
                $time_id = Carbon::now()->format('dmYhis');
                $file_name = $filteredName.$time_id.'_'.$value.'X'.$value.'.'.$F_extension;

                $new_tumb = Image::make($image_content)->resize($value, $value, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                Storage::disk('dropbox')->put('/'.$file_name, $new_tumb->stream());
                $response = $dropbox->createSharedLinkWithSettings('/'.$file_name, ["requested_visibility" => "public"]);

                $data[] = [
                    'url' => str_replace('dl=0', 'raw=1', $response['url']),
                    'name' => $response['name'], 'size' => $response['size'],
                    'dimentions' => $value .'x'. $value,
                    'extension' => $F_extension,
                ];

                $image_content = $new_tumb;
            }
            
        return $data;
            
            //$path = $request->file('file')->store('default', 'public'); //carpeta, disco
            //$file =  Storage::get('/public/' . $path); //storage/app/ . /public/
            //$path = Storage::disk('public')->exists('/public//'.'QHt09p47ourfaKRTnnPZMm2jMAqEtgzG5w2hOrkU.png');
            
            //return $path = Storage::get('/public/'.$path);
            //return $size = Storage::size('/public/' . $path);
            //return $file->getClientOriginalName();

            //$procesed_file = Image::make($request->file('file'))->encode('png', 90);
            //$path = Storage::disk('public')->put('nuevaimagenreducida.png', $procesed_file);
                       
            //$path = Storage::get(dirname(__DIR__, 3).'/public/storage/'.'QHt09p47ourfaKRTnnPZMm2jMAqEtgzG5w2hOrkU.png');
            //return $path;
        }
    }
}


    // const THUMBNAIL_SIZE_XS = 'w32h32';
    // const THUMBNAIL_SIZE_S = 'w64h64';
    // const THUMBNAIL_SIZE_M = 'w128h128';
    // const THUMBNAIL_SIZE_L = 'w640h480';
    // const THUMBNAIL_SIZE_XL = 'w1024h768';