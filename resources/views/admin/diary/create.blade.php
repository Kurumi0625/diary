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

                    @if (count($errors) > 0)
                        <ul>
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    @endif
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
                    {{ csrf_field() }}
                    <drop-image v-bind:name="'image_path'" v-bind:path="'{{ old('image_path') }}'"
                            v-bind:url="'/api/admin/diary/uploadImage'" v-bind:dir="'upload_images/diary/{{ auth()->user()->id }}'"></drop-image>
                    <input type="submit" class="btn btn-primary" value="更新">
                </form>
            </div>
        </div>
    </div>
@endsection