@extends('layouts.layout')
@section('title', 'トップページ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <a href="{{ action('Admin\DiaryController@meEdit') }}">{{ Auth::user()->name }}の日記</a>
            </div>
            <div class="col-md-8">
                <form action="{{ action('Admin\DiaryController@index') }}" method="get">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="cond_keyword" value="{{ $cond_keyword }}">
                        </div>
                        <div class="col-md-2">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-primary" value="検索">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <a href="{{ action('Admin\DiaryController@add') }}" role="button" class="btn btn-primary">日記を書く</a>
                <label>※カレンダーを入れる</label>
            </div>
            <div class="list-diary col-md-10 mx-auto">
                <div class="row">
                    <table class="table">
                        <tbody>
                            @foreach($posts as $diary)
                                <tr>
                                    <td width="20%">{{ $diary->date }}</td>
                                    <td width="70%">
                                        <div>
                                            <a href="{{ action('Admin\DiaryController@show') }}">{{ str_limit($diary->title, 50) }}</a>
                                            <br><a>{{ str_limit($diary->body, 80) }}</a>
                                        </div>
                                    </td>
                                    <td width="10%">
                                        <div>
                                            <a href="{{ action('Admin\DiaryController@edit', ['id' => $diary->id]) }}">編集</a>
                                        </div>
                                        <div>
                                            <a href="{{ action('Admin\DiaryController@delete', ['id' => $diary->id]) }}" onclick='return confirm("削除してもよろしいですか？");'/>削除</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection