@extends('layouts.layout')
@section('title', '投稿記事を編集する')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <a href="{{ action('Admin\DiaryController@index') }}" role="button" class="btn btn-primary">トップに戻る</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 mx-auto">
                <form action="{{ action('Admin\DiaryController@update') }}" method="post" enctype="multipart/form-data">
                    <!-- 入力漏れエラー表示 -->
                    @if (count($errors) > 0)
                        <ul>
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <!-- /入力漏れエラー表示 -->
                    <!-- 日付・タイトル・本文編集欄 -->
                    <div class="form-group row">
                        <div class="col-md-5">
                            <input type="date" class="form-control" name="date" value="{{ $diary_form->date }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <input type="text" class="form-control" name="title" value="{{ $diary_form->title }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <textarea class="form-control" name="body" rows="20">{{ $diary_form->body }}</textarea>
                        </div>
                    </div>
                    <!-- /日付・タイトル・本文編集欄 -->
                    <!-- 画像編集欄 -->
                    <div class="form-group row">
                        <div class="col-md-10">
                            <input type="hidden" name="id" value="{{ $diary_form->id }}">
                            {{ csrf_field() }}
                            <drop-image v-bind:name="'image_path'" v-bind:path="'{{ old('image_path', count($diary_form->images) != 0 ? $diary_form->images[0]->image_path : NULL) }}'"
                            v-bind:image_id="'{{ old('image_path', count($diary_form->images) != 0 ? $diary_form->images[0]->id : NULL) }}'"
                            v-bind:url="'/api/admin/diary/uploadImage'" v-bind:dir="'upload_images/diary/{{ auth()->user()->id }}'"></drop-image>
                            <!-- /画像編集欄 -->
                            <input type="submit" class="btn btn-primary" value="更新">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection