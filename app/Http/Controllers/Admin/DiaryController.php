<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Diary;

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
        
        if (isset($form['image'])) {
        $path = $request->file('image')->store('public/image');
        $diary->image_path = basename($path);
      } else {
          $diary->image_path = null;
      }

      unset($form['_token']);
      unset($form['image']);
      

      $diary->fill($form);
      $diary->save();
    
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
                ->get();
        } else {
            $posts = Diary::where('user_id', auth()->user()->id)->get()->sortByDesc('date');
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
        // AWS S3最後herokuやってから
        if ($request->remove == 'true') {
            $diary_form['image_path'] = null;
        } elseif ($request->file('image')) {
            $path = Storage::disk('s3')->putFile('/',$form['image'],'public');
            $diary->image_path = Storage::disk('s3')->url($path);
        } else {
            $diary_form['image_path'] = $diary->image_path;
        }
        
        unset($diary_form['_token']);
        unset($diary_form['image']);
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
    
}