<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
class PostController extends Controller
{
    
    public function index()
    {
        $posts = Post::all()->toArray();
        return array_reverse($posts);
    }
    
    public function store(Request $request)
    {
        dd($request->all());
        $post = Post::updateOrCreate([
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ]);
        $post->save();
        return response()->json('The post successfully added');
    }
    
    public function add(Request $request)
    {
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $path = $file->move('post-documents/', $fileName);
            $request->merge(['document_path' => $path]);
        }
        $post = new Post($request->all());
        $post->save();
        return response()->json('The post successfully added');
    }
    
    public function edit($id)
    {
        $post = Post::find($id);
        return response()->json($post);
    }
    
    public function update($id, Request $request)
    {
        $post = Post::find($id);
        if ($request->hasFile('document')) {
            if ($post->document_path) {
                File::delete(public_path($post->document_path));
            }
            $file = $request->file('document');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $path = $file->move('post-documents/', $fileName);
            $request->merge(['document_path' => $path]);
        }
        $post->update($request->all());
        return response()->json('The post successfully updated');
    }
    
    public function delete($id)
    {
        $post = Post::find($id);
        if ($post->document_path) {
            File::delete(public_path($post->document_path));
        }
        $post->delete();
        return response()->json('The post successfully deleted');
    }
}