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
          // 日記詳細
          $diary = Diary::find($request->id);
          // 日付
          $date = $diary->date;
          // 取得したい日付を配列
          $post_dates = [];
          for ($i=1; $i<5; $i++) {
              $post_dates[] = Carbon::parse($date)->clone()->subYears($i)->format('Y-m-d');
          }
          
          // 5年間の日記
          // 条件：自分が書いた日記 かつ 同じ日付 かつ 5年前まで かつ 今表示している日記以外
          $posts =
              Diary::where('user_id', auth()->user()->id)
                  ->whereIn('date', $post_dates)
                  ->orderByDesc('date')
                  ->get();

          return view('admin.diary.contents', ['posts' => $posts, 'diary' => $diary]);
    }
    
    public function uploadImage(Request $request)
    {
        $dir = $request->get('dir');
        $time = Carbon::now();
        $filename = str_random(5).date_format($time,'d').rand(1,9).date_format($time,'h').".".$request->file('img')->extension();
        $path = $request->file('img')->storeAs($dir, $filename, 'public');
        return response()->json(['status' => 'ok', 'path' => Storage::url($path)]);
    }
    
    
}