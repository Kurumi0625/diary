@extends('layouts.layout')
@section('title', 'トップページ')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="diary-user-name">
                    <h3>{{ Auth::user()->name }}の日記</h3>
                </div>
                <div class="diary-create">
                    <a href="{{ action('Admin\DiaryController@add') }}" role="button" class="btn btn-info">日記を書く</a>
                </div>
            </div>
            <div class="col-md-8">
                <form action="{{ action('Admin\DiaryController@index') }}" method="get">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="keyword">
                            <input type="text" class="form-control" name="cond_keyword" value="{{ $cond_keyword }}">
                            </div>
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
                <button id="prev" type="button">前の月</button>
                <button id="next" type="button">次の月</button>
                <div id="calendar"></div>
            </div>
            <div class="list-diary col-md-8 mx-auto">
                <div class="row">
                    <table class="index-diary-table">
                        <tbody>
                            @foreach($posts as $diary)
                                <tr>
                                    <td width="90%" class="index-diary-title"><a href="{{ action('Admin\DiaryController@show', ['id' => $diary->id]) }}">■{!! str_limit($diary->title, 65) !!}</a></td>
                                    <td width="10%"><a href="{{ action('Admin\DiaryController@edit', ['id' => $diary->id]) }}">編集</a></td>
                                </tr>
                                <tr>
                                    <th width="90%">{{ $diary->date }}</th>
                                    <td width="10%"><a href="{{ action('Admin\DiaryController@delete', ['id' => $diary->id]) }}" onclick='return confirm("削除してもよろしいですか？");'/>削除</a></td>
                                </tr>
                                <tr>
                                    <td class="index-diary-body" width="10%">{!! str_limit($diary->body, 400) !!}</td>
                                </tr>
                                <tr>
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