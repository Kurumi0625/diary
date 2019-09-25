@extends('layouts.layout')
  @section('title', '記事表示')

  @section('content')
      <div class="container">
          <div class="row">
              <div class="col-md-12">
                  <a href="{{ action('Admin\DiaryController@index') }}" role="button" class="btn btn-primary">トップに戻る</a>
              </div>
          </div>
          <!-- 日記詳細 -->
          <div class="row">
              <div class="detail-diary">
                  <p class="detail-diary-title">{{ $diary->title }}</p>
                  <p class="detail-diary-date">{{ $diary->date }}</p>
                  <p class="detail-diary-body">{!! $diary->body !!}</p>
                  @foreach ($diary->images as $image)
                    <img src="{{ $image->image_path }}" width="200" />
                  @endforeach
              </div>
              <hr style="width:100%; height:1px; background-color:#D9E5FF;">
          </div>
          <!-- /日記詳細 -->

          <!-- 5年間の日記 -->
          <div class="row">
              <div class="list-diary col-md-12 mx-auto">
                  <div class="row">
                      <div class="past-diary">
                              @foreach($posts as $diary)
                                  <p class="past-diary-title">{{ $diary->title }}</p>
                                  <p class="past-diary-date">{{ $diary->date }}</p>
                                  <p class="past-diary-body">{!! $diary->body !!}</p>
                                  <hr style="width:100%; height:1px; background-color:#D9E5FF;">
                              @endforeach
                      </div>
                  </div>
              </div>
          </div>
          <!-- /5年間の日記 -->
      </div>
  @endsection