@extends('layouts.app')
@section('title', '商品詳細画面')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection
@section('content')
    <div class="show">
        <div class="show_image-space">
            <div class="show_image-size">
                <img class="show_item-image" src="{{ asset($item->item_image) }}" alt="{{ $item->item_name }}">
            </div>
        </div>
        <div class="show_text-space">
            <div class="show_text-size">
                <h1 class="show_item-name">{{ $item->item_name }}</h1>
                <p class="show_brand">{{ $item->brand }}</p>
                <p class="show_price">
                    <span class="span_text">¥</span>
                    <span class="span_price">{{ number_format($item->price) }}</span>
                    <span class="span_text">(税込)</span>
                </p>
                <div class="show_count">
                    <form action="{{ route('items.favorite', $item->id) }}" method="POST" class="favorite_item">
                        @csrf
                        <button type="submit" class="star_button  {{ $item->isFavoritedBy(auth()->user()) ? 'liked' : '' }}">
                        </button>
                        <span class="count">{{ $item->favoritedByUsers()->count() }}</span>
                    </form>
                    <div class="comment_space">
                        <p class="comment_icon"></p>
                        <span class="count">{{ $item->comments()->count() }}</span>
                    </div>
                </div>

                <a class="show_purchase-button" href="/purchase/{{ $item->id }}">購入手続きへ</a>
                <h2 class="show_content-title">商品説明</h2>
                <p class="show_content">{{ $item->content }}</p>
                <h2 class="show_information-title">商品の情報</h2>
                <div class="show_category-div">
                    <p class="show_category-title">カテゴリー</p>
                    <div class="categories_size">
                        @foreach ($item->categories as $category)
                            <p class="show_categories">
                                {{ $category->category }}
                            </p>
                        @endforeach
                    </div>
                </div>
                <div class="show_situation-div">
                    <p class="show_situation-title">商品の状態</p>
                    <p class="show_situation">{{ $item->situation_label }}</p>
                </div>
                <h2 class="show_comment-title">コメント({{ $item->comments()->count() }})</h2>
                @foreach($item->comments as $comment)
                    <div class="comment_user">
                        <div class="comment_img-div">
                            <img class="comment_img" src="{{ $comment->user->profile_image }}" alt="">
                        </div>
                        <p class="comment_name">{{ $comment->user->name }}</p>
                    </div>
                    <p class="comment_text">{{ $comment->comment }}</p>
                @endforeach
                <h3 class="show_comment-tag">商品へのコメント</h3>
                <form action="{{ route('items.comment', $item->id) }}" method="POST">
                    @csrf
                    <div class="show_textarea-div">
                        <textarea rows="10" class="show_form-textarea" name="comment" id=""></textarea>
                        @error('comment')
                            <p class="error_message">{{ $message }}</p>
                        @enderror
                    </div>
                    <button class="show_form-button"  type="submit">コメントを送信する</button>
                </form>
            </div>

        </div>
    </div>
@endsection