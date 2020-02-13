@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/top.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="row justify-content-center">

  <!-- foreachは配列やオブジェクトの中身を1つずつ展開していくループ関数 -->
  @foreach($reviews as $review)

    <div class="col-md-4">
        <div class="card mb50">
            <div class="card-body">


            <!-- サムネイルのダミー表示 値の有無は !empty(変数)でチェック-->
            @if(!empty($review->image))
              <div class='image-wrapper'><img class='book-image' src="{{ asset('storage/images/'.$review->image) }}"></div>
            @else

                <div class='image-wrapper'><img class='book-image' src="{{ asset('images/dummy.png') }}"></div>

                @endif

                <h3 class='h3 book-title'>{{ $review->title }}</h3>
                <p class='description'>
                {{ $review->body }}
                </p>
                <!-- route('show', ['変数名' => 実際に入る値 ]) -->
                <a href="{{ route('show', ['id' => $review->id ]) }}" class='btn btn-secondary detail-btn'>詳細を読む</a>
            </div>
        </div>
    </div>
  @endforeach
</div>

<!-- 自動的に生成されたページネーションの中にlinks()という項目があり、link()の正体はビューで使えるHTMLコード -->
{{ $reviews->links() }}

@endsection