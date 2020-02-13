<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Reviewコントローラー内で、Reviewモデルを使えるようにするための記述
use App\Review;

class ReviewController extends Controller
{
    public function index()
    {
        // where()はデータを取得する際の絞り込み条件指定するメソッドで、statusが1（アクティブな投稿）を取得しています。
        // orderBy()ではデータの並び順を指定してます。1つ目の引数には並び替えに使いたいカラム名、2つ目の引数には並び順を昇順（ASC）、降順(DESC)のどちらかを指定
        // get()の代わりにpaginate(数を指定)を使う事で、自動的にページネーションを生成
        $reviews = Review::where('status', 1)->orderBy('created_at', 'DESC')->paginate(9);
    
        // dd()はdump dieの略で、中身を分解してその時点で動作を止めるデバッグ関数
        // dd($reviews);
    
        // データを渡すcompact()。compactの引数に変数名を指定することでビューで変数が使える
        return view('index', compact('reviews'));
    }

    // 引数に$idを指定。ルーティングのURLに記述した{id}の値が入ってくる。
    public function show($id)
    {
        // Reviewモデル を通じて、URLパラメーターに一致かつ、status=1（アクティブなレビュー）を->first()を用いて1件取得。次にstatus=1である必要があるため、->where() を繋げる。条件に一致した最初の行を取得する->first()でデータを取得して、データをビューに渡す。
        $review = Review::where('id', $id)->where('status', 1)->first();

        return view('show', compact('review'));
    }

    public function create()
    {
        return view('review');
    }

    // 引数はHTTPリクエストクラスで、HTTP通信に関わる様々な機能を利用
    // 上部記載のRequestクラス使用。$requestインスタンスをサービスプロバイダ機能で自動生成してる。
    // $request = request();
    public function store(Request $request)
    {   
        // POSTで送信されたデータを $postに代入
        $post = $request->all();

        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 画像有無で条件分岐
        if ($request->hasFile('image')) {

        // HTTPリクエストクラスで取得できるデータはビューのinputタグのname属性に合わせた名前でデータを取得
        $request->file('image')->store('/public/images');
        $data = ['user_id' => \Auth::id(), 'title' => $post['title'], 'body' => $post['body'], 'image' => $request->file('image')->hashName()];

        } else {
            $data = ['user_id' => \Auth::id(), 'title' => $post['title'], 'body' => $post['body']];
        }
        
        // insertメソッドはデータベースに新しいレコード（行）を追加するメソッド
        // 引数の配列に関して、キーはテーブルのカラム名、バリュー(値)は挿入されるデータ
        // モデルに対して、::に続けてメソッド名を記述することで、メソッドを呼び出す
        Review::insert($data);

        return redirect('/')->with('flash_message', '投稿が完了しました');

    }
}
