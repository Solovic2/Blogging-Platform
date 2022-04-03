<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
   
    public function index()
    {
        return view('admin.index');
    }

    public function dashboard()
    {
        $posts = Post::count();
        $comments = Comment::count();
        $tags = Tag::count();

       $mostActivated = User::withCount(['likes' ,'comments'])->orderByRaw('likes_count + comments_count DESC')->take(5)->get()->map->only('id','name','likes_count','comments_count');

       $mostPostLikes = Post::withCount('likes')->orderBy('likes_count','DESC')->take(5)->get()->map->only('id','title','likes_count');
       $mostPostComments = Post::withCount('comments')->orderBy('comments_count','DESC')->take(5)->get()->map->only('id','title','comments_count');
       return $object = collect([
            'posts' => $posts ,'comments' =>$comments,'tags' => $tags 
            ,'mostActivated'=>$mostActivated , 'mostPostLikes'=> $mostPostLikes , 'mostPostComments'=>$mostPostComments
        ]);
            
    
    }
    /**         Users           */

    public function getUsers()
    {
        return User::latest()->get();
    }
    public function addUser(Request $request)
    {
       $validate = $request->validate([
            'name'=> ['required', 'string', 'max:255'],
            'email'=> ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        
        return User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password']),
        ]);
        
    }
    public function editUser(Request $request)
    {
        $validated = $request->validate([
            'name'=> ['required', 'string', 'max:255'],
            'email'=> ['required', 'same:email','string', 'email', 'max:255', 'unique:users,email,'. $request->id],
        ]);

       $user  = User::findOrFail($request->id);
       $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $request->password == null ? $request->oldPassword : Hash::make($request->password)
        ]);
        return $user->password;
    }

    public function deleteUser(Request $request)
    {
       return User::destroy($request->id);
    }

    /**         Tags            */

    public function getTags()
    {
        return Tag::latest()->get();
    }
    public function addTag(Request $request)
    {
        $validate = $request->validate([
            'tagName'=> ['required','unique:tags'] 
        ]);
      return Tag::create(['tagName'=> $validate['tagName']]) ;
    }
    public function deleteTag(Request $request)
    {
       return Tag::destroy($request->id);
    }
    public function editTag(Request $request)
    {

        $validate = $request->validate([
            'tagName'=> 'required|unique:tags,tagName,'. $request->id ,
         ]);

       return Tag::findOrFail($request->id)->update(['tagName'=>$validate['tagName']]);
    }

    /**         Posts            */

    public function getPosts()
    {
        return Post::with('tags')->latest()->get();
    }
    public function saveImages(Request $request)
    {
       $image =  time() . '.' . $request->image->extension();
       $request->image->move(public_path('uploads'),$image);
       return $image;
    }

    public function deleteImages(Request $request)
    {
        if(File::exists(public_path($request->image))) return unlink(public_path($request->image));
        return 0;
    }
    public function addPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'body'=>'required',
            'tags'=>'required',
            'img'=>'required',
        ]);
        $post =  Post::create(['title'=>$validated['title'],'banner'=>$validated['img'],'body'=>$validated['body'],'user_id'=>Auth::id()]);
        $post->tags()->attach($validated['tags']);
        return Post::with('tags')->find($post->id);
        
    }
    public function uploadBanner(Request $request)
    {
        $pic = time(). '.' . $request->file->extension();
        $request->file->move(public_path('uploads'),$pic);
        return $pic;
    }
    public function editPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'body'=>'required',
            'tags'=>'required',
            'img'=>'required'
        ]);
        $post = Post::with('tags')->findOrFail($request->id);
        $post->update(['title'=>$validated['title'],'banner'=>$validated['img'],'body'=>$validated['body']]);
        $post->tags()->sync($validated['tags']);
        return $post;
       
    }
    public function deletePost(Request $request)
    {
       return Post::destroy($request->id);
    }


}
