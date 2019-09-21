<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Diary;
use App\Image;
use App\User;
use App\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;


class DiaryController extends Controller
{
    public function add()
    {
        return view('admin.diary.create');
    }
    
    public function create(Request $request)
    {
        $this->validate($request, Diary::$rules);
        
        $diary = new Diary;
        $form = $request->all();
        $form['user_id'] = auth()->user()->id;
        
        $diary->fill($form);
        $diary->save();
        
        $image = new Image;
        $image->diary_id = $diary->id;
        $image->image_path = $form['image_path'];
        $image->save();
      
        return redirect('admin/diary/');
    }
    
    public function index(Request $request)
    {
        $cond_keyword = $request->cond_keyword;
        if ($cond_keyword != '') {
            $posts = Diary::where('user_id', auth()->user()->id)->where(function($query) use($cond_keyword){
                $query->where('title', 'LIKE', "%$cond_keyword%")
                ->orWhere('body', 'LIKE', "%$cond_keyword%");
                })
                ->orderByDesc('date')->paginate(10);
        } else {
            $posts = Diary::where('user_id', auth()->user()->id)->orderByDesc('date')->paginate(10);
        }
        
        return view('admin.diary.index', ['posts' => $posts, 'cond_keyword' => $cond_keyword]);
        
    }
    public function edit(Request $request)
    {
        $diary = Diary::find($request->id);
        if (empty($diary)) {
            abort(404);
        }
        return view('admin.diary.edit', ['diary_form' => $diary]);
    }
    
    public function update(Request $request)
    {
        $this->validate($request, Diary::$rules);
        $diary = Diary::find($request->id);
        $diary_form = $request->all();
        /* 要編集
        if ($request->remove == 'true') {
            $diary_form['image_path'] = null;
        } elseif ($request->file('image')) {
            $path = Storage::disk('s3')->putFile('/',$form['image'],'public');
            $diary->image_path = Storage::disk('s3')->url($path);
        } else {
            $diary_form['image_path'] = $diary->image_path;
        }*/
        
        unset($diary_form['_token']);
        //unset($diary_form['image']);
        unset($diary_form['remove']);
        $diary->fill($diary_form)->save();
        
        return redirect('admin/diary/');
    }
    
    public function delete(Request $request)
    {
        $diary = Diary::find($request->id);
        $diary->delete();
        return redirect('admin/diary/');
    }
    
    public function show(Request $request)
    {
        $cond_keyword = $request->cond_keyword;
        if ($cond_keyword != '') {
            $posts = Diary::where('user_id', auth()->user()->id)->where(function($query) use($cond_keyword){
                $query->where('title', 'LIKE', "%$cond_keyword%")
                ->orWhere('body', 'LIKE', "%$cond_keyword%");
                })
                ->orderByDesc('date')->paginate(10);
        } else {
            $posts = Diary::where('user_id', auth()->user()->id)->orderByDesc('date')->paginate(10);
        }
        
        return view('admin.diary.contents', ['posts' => $posts, 'cond_keyword' => $cond_keyword]);
        //$posts = Diary::find($request->id);
        //return view('admin.diary.contents',['posts'=>$posts, 'id'=>$id]);
        
        //
        //$posts = Diary::all();
        
        //return view('admin.diary.contents', ['posts' => $posts]);
        //return view('admin.diary.contents', ['post' => $post]);
    }
    
    public function uploadImage(Request $request)
    {
        $dir = $request->get('dir');
        $time = Carbon::now();
        $filename = str_random(5).date_format($time,'d').rand(1,9).date_format($time,'h').".".$request->file('img')->extension();
        $path = $request->file('img')->storeAs($dir, $filename, 'public');
        return response()->json(['status' => 'ok', 'path' => Storage::url($path)]);
    }
    
    public function meEdit(Request $request)
    {
     
      return view('admin.diary.me');
    }
    
}