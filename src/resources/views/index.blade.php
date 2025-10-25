@extends(Auth::check() ? 'layouts.app' : 'layouts.guest')
@section('title', '商品一覧画面')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection
@section('content')
    <div class="sub_header">
        <a class="sub_header-button {{ request()->query('tab') !== 'mylist' ? 'button-active' : '' }}" href="/">おすすめ</a>
        <a class="sub_header-button {{ request()->query('tab') === 'mylist' ? 'button-active' : '' }}" href="{{ url('/?tab=mylist') }}">マイリスト</a>
    </div>
    <div class="list">
        @foreach ($items as $item)
            <div class="list_item">
                <div class="list_item-size">
                    <a class="list_item-link" href="">
                        <img class="list_item-image" src="{{ $item->item_image }}" alt="{{ $item->item_name }}">
                    </a>
                </div>
                <div class="list_name-size">
                    <a class="list_item-name" href="">{{ $item->item_name }}</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection