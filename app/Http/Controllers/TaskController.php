<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use App\Models\Task;

class TaskController extends Controller
{
    public function storeImage(Request $request)
    {  
        
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg|',
            'video'  => 'mimes:mp4,mov,ogg,qt | max:20000'
        ]);
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension(); 
            $rand = '-' . strtolower(Str::random(10));  
            $name = $rand . '.' . $extension; 
            $image =  $this->processImage($file, $name);
        }  
        
        if(Request::hasFile('video')){

            $file = Request::file('video');
            $filename = $file->getClientOriginalName();
            $path = public_path().'/video/';
            $video =  $file->move($path, $filename);
        }

       $task =  new Task();
       $task->image = $image->optimize();
       $task->video = $video->optimize();
       $task->save();
       return Redirect::back()->withMsg(['msg' => 'Uploaded']);

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
