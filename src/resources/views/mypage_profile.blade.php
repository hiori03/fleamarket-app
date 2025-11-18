@extends('layouts.app')
@section('title', 'プロフィール編集画面')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage_profile.css') }}">
@endsection
@section('content')
    <div class="content">
        <h1 class="content_title">プロフィール設定</h1>
        <form action="/mypage/profile" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="icon">
                <div class="icon_image">
                    @if (!empty($user->profile_image))
                        <img class="icon_image-previwe" src="{{ asset('storage/' . $user->profile_image) }}" alt="">
                    @endif
                </div>
                <div class="icon_choice">
                    <label for="icon" class="icon_choice-button">画像を選択する</label>
                    <input type="file" id="icon" name="profile_image" accept="image/*">
                </div>
                @error('profile_image')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <div class="content_form">
                <p class="content_form-text">ユーザー名</p>
                <input class="content_form-input" type="text" name="name" value="{{ old('name', Auth::user()->name) }}">
                @error('name')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <div class="content_form">
                <p class="content_form-text">郵便番号</p>
                <input class="content_form-input" type="text" name="postal" value="{{ old('postal', Auth::user()->address?->postal) }}">
                @error('postal')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <div class="content_form">
                <p class="content_form-text">住所</p>
                <input class="content_form-input" type="text" name="address" value="{{ old('address', Auth::user()->address?->address) }}">
                @error('address')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <div class="content_form">
                <p class="content_form-text">建物名</p>
                <input class="content_form-input" type="text" name="building" value="{{ old('building', Auth::user()->address?->building) }}">
            </div>
            <button class="content_form-button">更新する</button>
        </form>
    </div>
@endsection