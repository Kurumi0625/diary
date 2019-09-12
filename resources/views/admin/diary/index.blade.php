@extends('layouts.layout')
@section('title', 'トップページ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <a href="{{ action('Admin\DiaryController@add') }}" role="button" class="btn btn-primary">日記を書く</a>
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
                <label>※カレンダー入れる※</label>
            </div>
            <div class="list-diary col-md-10 mx-auto">
                <div class="row">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="10%">日付</th>
                                <th width="20%">タイトル</th>
                                <th width="50%">本文</th>
                                <th width="10%">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $diary)
                                <tr>
                                    <th>{{ $diary->date }}</th>
                                    <td>{{ str_limit($diary->title, 15) }}</td>
                                    <td>{{ str_limit($diary->body, 30) }}</td>
                                    <td>
                                        <div>
                                            <a href="{{ action('Admin\DiaryController@edit', ['id' => $diary->id]) }}">編集</a>
                                        </div>
                                        <div>
                                            <a href="{{ action('Admin\DiaryController@delete', ['id' => $diary->id]) }}">削除</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection