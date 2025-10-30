@extends('layouts.app')
@section('title', 'プロフィール画面')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection
@section('content')
    <div class="content">
        <div class="sub_header">
            <div class="profile">
                <div class="profile_image-div">
                    <img class="profile_image" src="{{ asset('images/Armani+Mens+Clock.jpg') }}" alt="">
                </div>
                <p class="profile_name">{{ $user->name }}</p>
                <a class="profile_button" href="/mypage/profile">プロフィールを編集</a>
            </div>
            <div class="tab">
                <a class="tab_button {{ request()->query('page') === 'sell' ? 'button-active' : '' }}" href="{{ url('/mypage?page=sell') }}">出品した商品</a>
                <a class="tab_button {{ request()->query('page') === 'buy' ? 'button-active' : '' }}" href="{{ url('/mypage?page=buy') }}">購入した商品</a>
            </div>
        </div>
        <div class="list">
            @foreach ($items as $item)
                <div class="list_item">
                    <div class="list_item-size">
                        <a class="list_item-link" href="/item/{{ $item->id }}">
                            <img class="list_item-image" src="{{ $item->item_image }}" alt="{{ $item->item_name }}">
                        </a>
                    </div>
                    <div class="list_name-size">
                        <a class="list_item-name" href="/item/{{ $item->id }}">{{ $item->item_name }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection