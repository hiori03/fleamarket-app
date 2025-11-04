@extends('layouts.app')
@section('title', '送付先住所変更画面')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchase_address.css') }}">
@endsection
@section('content')
    <div class="content">
        <h1 class="content_title">住所の変更</h1>
        <form action="{{ route('purchase.address.update', ['item' => $item->id]) }}" method="POST">
            @csrf
            <div class="content_form">
                <p class="content_form-text">郵便番号</p>
                <input class="content_form-input" type="text" name="postal" value="{{ old('postal', session('purchase_address.postal') ?? Auth::user()->address?->postal) }}">
                @error('postal')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <div class="content_form">
                <p class="content_form-text">住所</p>
                <input class="content_form-input" type="text" name="address" value="{{ old('address', session('purchase_address.address') ?? Auth::user()->address?->address) }}">
                @error('address')
                    <p class="error_message">{{ $message }}</p>
                @enderror
            </div>
            <div class="content_form">
                <p class="content_form-text">建物名</p>
                <input class="content_form-input" type="text" name="building" value="{{ old('building', session('purchase_address.building') ?? Auth::user()->address?->building) }}">
            </div>
            <button class="content_form-button">更新する</button>
        </form>
    </div>
@endsection