<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer as FacadesImageOptimizer;

class TaskController extends Controller
{
    public function storeImage(Request $request)
    {  
        
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg|',
        ]);
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); 
            $rand = '-' . strtolower(Str::random(10));  
            $name = $rand . '.' . $extension; 
            $image =  $this->processImage($file, $name);   
            dd($image);
        }   
    }

    public function processImage($file, $name) 
    {  
        $file->move(public_path('/test/image'), $name);  
        $webp = public_path() . '/test/image/' .$name;      
        $im = imagecreatefromstring(file_get_contents($webp)); 
        $new_webp =preg_replace('"\.(jpg|jpeg|png|webp)$"','.webp', $webp);  
        return $new_webp;
    }
}
