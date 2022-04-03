<?php

namespace App\Http\Controllers;

use App\Events\CommentToPostEvent;
use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public  function getAllTags()
    {
        
        return Tag::withCount('posts')->get();
    }
    public function getAllPosts()
    {
        return Post::with('tags')->latest()->get();
    }
    public function getPost($id)
    {
        return Post::with(['comments' =>function($q){
            $q->orderBy('updated_at','desc');
        },'likes','tags'])->findOrFail($id);
    }
    public function addComment(Request $request)
    {
        $comment = Comment::create(['comment'=>$request->comment,'user_id'=>Auth::id(),'post_id'=>$request->id]);
        broadcast(new CommentToPostEvent($comment));
        return $comment;

    }

    public function getAllPostsOfTag($id)
    {
       return Tag::with(['posts'=>function($q){
           $q->orderBy('updated_at','desc');
       }])->findOrFail($id);
    }

}
