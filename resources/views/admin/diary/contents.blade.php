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
                  <p class="detail-diary-title">{!! $diary->title !!}</p>
                  <p  class="detail-diary-date">{{ $diary->date }}</p>
                  <p class="detail-diary-body">{!! $diary->body !!}</p>
              </div>
          </div>
          <!-- /日記詳細 -->

          <!-- 5年間の日記 -->
          <div class="row">
              <div class="list-diary col-md-12 mx-auto">
                  <div class="row">
                      <table class="past-diary-table">
                          <tbody>
                              @foreach($posts as $diary)
                                <tr>
                                    <th>{{ $diary->date }}</th>
                                </tr>
                                <tr>
                                    <th class="title">{!! $diary->title !!}</th>
                                </tr>
                                <tr>
                                    <td>{!! $diary->body !!}</td>
                                </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
          <!-- /5年間の日記 -->
      </div>
  @endsection