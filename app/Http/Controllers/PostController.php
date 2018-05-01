<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post as PostR;
use App\Http\Resources\PostCollection;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new PostCollection(Post::orderBy('id','desc')->paginate(15));
        // return new PostR(Post::findOrFail(3));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $exploded = explode(',', $request->image);
        $decoded = base64_decode($exploded[1]);
        $extension = '';
        if (str_contains($exploded[0],'jpeg')) {
            $extension = 'jpg';
        }else{
            $extension = 'png';
        }

        $fileName = str_random(). '.'. $extension;

        $path = public_path().'/postImages/'.$fileName;

        //file_put_contents($path, $decoded);

        Post::create([
            'user_id' => $request->user()->id,
            'post_title' => $request->textTitle,
            'post_body' => $request->textBody,
            'post_img' => $fileName,
        ]);

        file_put_contents($path, $decoded);

        //return $request->all();
        //$post = Post::create($request->except('image') + ['user_id' => auth()->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostR($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if (auth()->user()->id != $post->user_id) {
            return "You can't edit this";
        }else{
            //&& $request->image !== '' &&  $request->image !== ""
            if ($request->image !== NULL) {
                
                Storage::disk('public')->delete($post->post_img);
                $exploded = explode(',', $request->image);
                $decoded = base64_decode($exploded[1]);
                $extension = '';
                if (str_contains($exploded[0],'jpeg')) {
                    $extension = 'jpg';
                }else{
                    $extension = 'png';
                }

                $fileName = str_random(). '.'. $extension;

                $path = public_path().'/postImages/'.$fileName;

                //file_put_contents($path, $decoded);

                file_put_contents($path, $decoded);

                $post->post_img = $fileName;

            }
            $post->post_title = $request->post_title;
            $post->post_body = $request->post_body;
            $post->save();
            return $post->post_img;
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post,Request $request)
    {
        if (auth()->user()->id != $post->user_id) {
            return "You can't delete this";
        }else{
            $post->delete();
            Storage::disk('public')->delete($post->post_img);
        }
    }
}
