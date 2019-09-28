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
    //「日記を書く」へ
    public function add()
    {
        return view('admin.diary.create');
    }
    
    //「日記を書く」ページ
    public function create(Request $request)
    {
        $this->validate($request, Diary::$rules);
        
        $diary = new Diary;
        $form = $request->all();
        $form['user_id'] = auth()->user()->id;
        
        $diary->fill($form);
        $diary->save();
        
        if (!empty($form['image_path'])) {
            $image = new Image;
            $image->diary_id = $diary->id;
            $image->image_path = $form['image_path'];
            $image->save();
        }
      
        return redirect('admin/diary/');
    }
    
    //トップページ、曖昧検索
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
    
    //「編集」へ
    public function edit(Request $request)
    {
        $diary = Diary::find($request->id);
        if (empty($diary)) {
            abort(404);
        }
        return view('admin.diary.edit', ['diary_form' => $diary]);
    }
    
    //日記・画像アップ
    public function update(Request $request)
    {
        $this->validate($request, Diary::$rules);
        $diary = Diary::find($request->id);
        $diary_form = $request->all();
        $diary->fill($diary_form)->save();

        // 🌟 画像
        // 削除されたIDがあれば、Imageテーブルから削除
        if (!empty($request->deletedIds)) {
            foreach ($request->deletedIds as $deletedId) {
                $image = Image::find($deletedId);
                $image->delete();
            }
        }

        // image_pathにデータがあれば、Imageテーブルに新規登録
        if (!empty($diary_form['image_path'])) {
            $image = new Image;
            $image->diary_id = $diary->id;
            $image->image_path = $diary_form['image_path'];
            $image->save();
        }

        return redirect('admin/diary/');
    }
    
    //削除
    public function delete(Request $request)
    {
        $diary = Diary::find($request->id);
        $diary->delete();
        return redirect('admin/diary/');
    }
    
    //5年日記表示
    public function show(Request $request)
    {
          // 当日日記詳細
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
    
    public function getDiaries(Request $request) {
          $year = $request->get('year');
          $month = $request->get('month');
          $diaries = Diary::whereYear('date', '=', $year)->whereMonth('date', '=', $month)->get();
          return response()->json(['status' => 'ok', 'diaries' => $diaries]);
  }
    
}