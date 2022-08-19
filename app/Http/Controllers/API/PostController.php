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
        return view('post');
    }
    
    public function getPosts()
    {
        $posts = Post::all()->toArray();
        return array_reverse($posts);
    }
    
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }
    
    public function save(Request $request)
    {
        $id = $request->get('id') ?? null;
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = time().'.'.$file->getClientOriginalExtension();
            $path = $file->move('post-documents/', $fileName);
            $request->merge(['document_path' => $path]);
        }
        if ($id) {
            $post = Post::findOrFail($id);
            if ($post->document_path && $request->hasFile('document')) {
                File::delete(public_path($post->document_path));
            }
            $post->update($request->all());
        }else {
            $post = new Post($request->all());
            $post->save();
        }
        return response()->json('The post successfully saved');
    }
    
    public function delete($id)
    {
        $post = Post::findOrFail($id);
        if ($post->document_path) {
            File::delete(public_path($post->document_path));
        }
        $post->delete();
        return response()->json('The post successfully deleted');
    }
}