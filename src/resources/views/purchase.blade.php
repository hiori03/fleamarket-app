@extends('layouts.app')
@section('title', '商品購入画面')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection
@section('content')
    <div class="content">
        <div class="content_information">
            <div class="item_information">
                <div class="item_image-size">
                    <img class="item_image" src="{{ asset($item->item_image) }}" alt="{{ $item->item_name }}">
                </div>
                <div class="information_text-div">
                    <h1 class="item_name">{{ $item->item_name }}</h1>
                    <p class="item_price">
                        <span class="span_text">¥</span>
                        <span class="span_price">{{ number_format($item->price) }}</span>
                    </p>
                </div>
            </div>
            <div class="payment_information">
                <p class="payment_title-information">支払い方法</p>
                <form method="GET" action="{{ route('purchaseform', ['item' => $item->id]) }}">
                    <div class="payment_div">
                        <div class="payment_select-div">
                            <select class="payment_select" name="payment_method" onchange="this.form.submit()">
                                <option value="" hidden>選択してください</option>
                                <option value="0" {{ request('payment_method') == '0' ? 'selected' : '' }}>コンビニ払い</option>
                                <option value="1" {{ request('payment_method') == '1' ? 'selected' : '' }}>カード支払い</option>
                            </select>
                        </div>
                        @error('payment_method')
                            <p class="error_message">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="shipping_address">
                <div class="shipping_title-div">
                    <p class="shipping_address-title">配送先</p>
                    <a class="shipping_address-link" href="/purchase/address/{{ $item->id }}">変更する</a>
                </div>
                <div class="postal_div">
                    <p class="postal_text">〒</p>
                    <p class="postal_input">{{ $address->postal ?? '' }}</p>
                </div>
                <div class="address_div">
                    <p class="address_text">{{ $address->address ?? '' }}{{ $address->building ?? '' }}</p>
                    @error('address')
                        <p class="error_message">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="content_payment">
            <form action="{{ url('/purchase/' . $item->id) }}" method="POST">
                @csrf
                <div class="payment_border">
                    <div class="price_div">
                        <p class="price_tag">商品代金</p>
                        <div class="price_span">
                            <span class="span_text-payment">¥</span>
                            <span class="span_price-payment">{{ number_format($item->price) }}</span>
                        </div>
                    </div>
                    <div class="payment_text-div">
                        <p class="payment_title">支払い方法</p>
                        <p class="payment_text">
                            @if(request('payment_method') == '0')
                                コンビニ支払い
                            @elseif(request('payment_method') == '1')
                                カード支払い
                            @else
                                未選択
                            @endif
                        </p>
                    </div>
                </div>
                <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
                <input type="hidden" name="postal_order" value="{{ $address->postal ?? '' }}">
                <input type="hidden" name="address_order" value="{{ $address->address ?? '' }}">
                <input type="hidden" name="building_order" value="{{ $address->building ?? '' }}">
                <button class="form_button">購入する</button>
            </form>
        </div>
    </div>
@endsection