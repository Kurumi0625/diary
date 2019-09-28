@extends('layouts.layout')
@section('title', '日記を書く')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="{{ action('Admin\DiaryController@index') }}" role="button" class="btn btn-primary">トップに戻る</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 mx-auto">
                <form action="{{ action('Admin\DiaryController@create') }}" method="post" enctype="multipart/form-data">
                    <!-- 入力漏れエラー表示 -->
                    @if (count($errors) > 0)
                        <ul>
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <!-- /入力漏れエラー表示 -->
                    <!-- 日付・タイトル・本文入力欄 -->
                    <div class="form-group row">
                        <div class="col-md-5">
                            <input type="date" class="form-control" name="date" value="{{ old('date') }}" placeholder="日付を選択してください">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="タイトルを入力してください">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <textarea class="form-control" name="body" rows="20" placeholder="本文を入力してください">{{ old('body') }}</textarea>
                        </div>
                    </div>
                    <!-- /日付・タイトル・本文入力欄 -->
                    {{ csrf_field() }}
                    <!-- 画像アップロード欄 -->
                    <drop-image v-bind:name="'image_path'" v-bind:path="'{{ old('image_path') }}'"
                            v-bind:url="'/api/admin/diary/uploadImage'" v-bind:dir="'upload_images/diary/{{ auth()->user()->id }}'"></drop-image>
                    <input type="submit" class="btn btn-primary" value="更新">
                    <!-- /画像アップロード欄 -->
                </form>
            </div>
        </div>
    </div>
@endsection