<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\News;

class NewsController extends Controller
{
    //
    public function index(Request $request)
    {
        $posts = News::all()->sortByDesc('updated_at');//（ポストにニュースをすべての情報を入れる。）NewsallでEloquentのすべてのnewsテーブルを取得し、投稿日時順に新しいほうから並べる
        
        if (count($posts) > 0){//ポスツが1以上ならシフトで最初のデータを削除して取り出して、ヘッドラインに入れて、削除しなかった残りのをポストに入れる
            $headline = $posts->shift();//配列の最初のデータを削除し、その値を返すメソッド,最新の記事を$headlineに代入し、$postsは代入された最新記事以外の記事が格納されている
        }else{
            $headline=null;//ポスツが０ならヘッドラインはなし。
        }
        
        //news/index.blade.phpファイルを渡している
        //またViewてんぷれーとにheadline、posts、という変数を渡している
        return view('news.index', ['headline' => $headline, 'posts' => $posts]);
    }
}
