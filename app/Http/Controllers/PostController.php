<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('create');
    }

    public function store(Request $request)
    {

       $this->validate($request, [
         'title' => 'required',
         'description' => 'required'

     ]);


       $description = $request->description;
       $dom = new \DomDocument();
       $dom->loadHtml($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);    
       $images = $dom->getElementsByTagName('img');
       foreach($images as $k => $img){


           $data = $img->getAttribute('src');
           list($type, $data) = explode(';', $data);
           list($type, $data) = explode(',', $data);
           $data = base64_decode($data);
           $image_name= "/upload/" . time().$k.'.png';
           $path = public_path() . $image_name;
           file_put_contents($path, $data);
           $img->removeAttribute('src');
           $img->setAttribute('src', $image_name);

       }

       $description = $dom->saveHTML();
       $summernote = new Post;
       $summernote->title = $request->title;
       $summernote->description = $description;
       $summernote->save();

       return redirect()->back();

       echo "<h1>Title</h1>" , $Title;
       echo "<h2>Description</h2>" , $description;

   }

   public function list()
   {
       $data = Post::get();
       return view('list',compact('data'));
   }

}