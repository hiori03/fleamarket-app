@extends('layouts.app')
@section('title', '商品出品画面')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection
@section('content')
    <div class="content">
        <h1 class="content_title">商品の出品</h1>
        <form action="/sell" method="POST" enctype="multipart/form-data">
            @csrf
            <p class="content_title-p">商品画像</p>
            <div class="image_div">
                <div class="image_choice-border">
                    <label for="image" class="image_choice-button">画像を選択する</label>
                    <input type="file" id="image" name="item_image" accept="image/*">
                </div>
                @error('item_image')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <h2 class="content_title-h2">商品の詳細</h2>
            <p class="content_title-category">カテゴリー</p>
            <div class="category_div">
                @foreach ($categories as $category)
                    <input class="category_input" id="{{ $category->id }}" type="checkbox" name="category_id[]" value="{{ $category->id }}" {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}>
                    <label class="category_label" for="{{ $category->id }}">
                        {{ $category->category }}
                    </label>
                @endforeach
                @error('category_id')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <p class="content_title-p">商品の状態</p>
            <div class="situation_div">
                <div class="situation_select-div">
                    <select class="situation_select" name="situation">
                        <option value="" selected hidden>選択してください</option>
                        <option value="0" {{ old('situation') == '0' ? 'selected' : '' }}>良好</option>
                        <option value="1" {{ old('situation') == '1' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                        <option value="2" {{ old('situation') == '2' ? 'selected' : '' }}>やや傷や汚れあり</option>
                        <option value="3" {{ old('situation') == '3' ? 'selected' : '' }}>状態が悪い</option>
                    </select>
                </div>
                @error('situation')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <h2 class="content_title-h2">商品名と説明</h2>
            <p class="content_title-p">商品名</p>
            <div class="name_div">
                <input class="name_input" type="text" name="item_name" value="{{ old('item_name') }}">
                @error('item_name')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <p class="content_title-p">ブランド名</p>
            <input class="brand_input" type="text" name="brand" value="{{ old('brand') }}">
            <p class="content_title-p">商品の説明</p>
            <div class="content_div">
                <textarea class="content_textarea" rows="6" name="content" id="">{{ old('content') }}</textarea>
                @error('content')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <p class="content_title-p">販売価格</p>
            <div class="price_div">
                <div class="price_input-div">
                    <span class="price_text">¥</span>
                    <input class="price_input" type="text" name="price" value="{{ old('price') }}">
                </div>
                @error('price')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <button class="content_button" type="submit">出品する</button>
        </form>
    </div>
@endsection