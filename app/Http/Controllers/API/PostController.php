<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;
class PostController extends Controller
{
    // all posts
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
    // add post
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
    // edit post
    public function edit($id)
    {
        $post = Post::find($id);
        return response()->json($post);
    }
    // update post
    public function update($id, Request $request)
    {
        $post = Post::find($id);
        $post->update($request->all());
        return response()->json('The post successfully updated');
    }
    // delete post
    public function delete($id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json('The post successfully deleted');
    }
}