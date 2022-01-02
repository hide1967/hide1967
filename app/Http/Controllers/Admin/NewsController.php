<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

//以下を追記することでNews　Modelが使えるようになる
use App\News;

use App\History;

use Carbon\Carbon;

use Storage; //Storageファサードを追加する。クリエイトアクションも変更している

class NewsController extends Controller
{
    //
    public function add()
    {
        return view('admin.news.create');
    }
    
    //以下を追記
    public function create(Request $request)//すべての$request(idとかIPとか名前とかをクラスRequestで取得している)
    {
        
        //追記validationを行う.
        $this->validate($request, News::$rules);
        
        $news = new News;//インスタンスの作成
        $form = $request->all();//news_formの作成
        
        //フォームから画像が送信されてきたら、保存して、＄news->image_pathに画像のパスを保存する。
        if(isset($form['image'])) {
            $path = Storage::disk('s3')->putfile('/', $news_form['image'], 'public');
            $news->image_path = Storage::disk('s3')->url($path);
        } else {
            $news->image_path = null;
        }
        
        //フォームから送信されてきた_tokenを削除する
        unset($form['_token']);
        //フォームから送信されてきたimageを削除する
        unset($form['image']);
        
        //データベースに保存する
        $news->fill($form);
        $news->save();
        
        
        //admin/news/createにりだいれくとする
        return redirect('admin/news/create');
    }
    
    //15章追記
    public function index(Request $request)
    {
        $cond_title = $request->cond_title;
        if ($cond_title != ''){
            //検索されたら検索結果を取得する
            $posts = News::where('title', $cond_title)->get();
        } else {
            //それ以外はすべてのニュースを取得する
            $posts = News::all();
        }
        return view ('admin.news.index', ['posts' => $posts, 'cond_title' =>$cond_title]);
    }
    
    //16イカを追記
    
    public function edit(Request $request)
    {
        //News Modelからデータを取得する
        $news = News::find($request->id);
        if(empty($news)) {
            abort(404);
        }
        return view('admin.news.edit', ['news_form' => $news]);
    }
    
    //編集画面から送信されたフォームデータを処理する部分
    public function update(Request $request){
        
        //validationする
        $this->validate($request, News::$rules);
        //newsmodelからデータを取得する
        $news = News::find($request->id);
        //送信されてきたフォームデータを格納する
        $news_form = $request->all();
        
        //画像を変更したときにエラーが発生しないようにする
        if($request->remove =='true'){
            $news_form['image_path'] =null;
        }elseif($request->file('image')){
            $path = Storage::disk('s3')->putFile('/',$news_form['image'],'public');
            $news->image_path = Storage::disk('s3')->url($path);
        }else{
            $news_form['image_path'] = $news->image_path;
        }
        unset($news_form['image']);
        unset($news_form['remove']);
        
        unset($news_form['_token']);
        
        //該当するデータを上書きして保存する
        $news->fill($news_form)->save();
        
        //HistoryModelに編集履歴を追加する
        $history = new History();
        $history->news_id = $news->id;
        $history->edited_at = Carbon::now();
        $history->save();

        
        return redirect('admin/news');
    }
    //deleteアクションを追加
    public function delete(Request $request){
        //該当するNews modelを取得
        $news = News::find($request->id);
        //削除する
        $news->delete();
        return redirect('admin/news');
    }
}
