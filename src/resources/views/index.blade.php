@extends(Auth::check() ? 'layouts.app' : 'layouts.guest')
@section('title', '商品一覧画面')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection
@section('content')
    <div class="sub_header">
        @php
            $query = request()->query();
        @endphp
        @php
            $recommendedQuery = $query;
            unset($recommendedQuery['tab']);
        @endphp
        <a class="sub_header-button {{ request()->query('tab') !== 'mylist' ? 'button-active' : '' }}"
            href="{{ url('/') . (http_build_query($recommendedQuery) ? '?' . http_build_query($recommendedQuery) : '') }}">おすすめ</a>

        @php
            $mylistQuery = $query;
            $mylistQuery['tab'] = 'mylist';
        @endphp
        <a class="sub_header-button {{ request()->query('tab') === 'mylist' ? 'button-active' : '' }}"
            href="{{ url('/') . (http_build_query($mylistQuery) ? '?' . http_build_query($mylistQuery) : '') }}">マイリスト</a>
    </div>
    <div class="list">
        @foreach ($items as $item)
            <div class="list_item">
                <div class="list_item-size">
                    <a class="list_item-link" href="/item/{{ $item->id }}">
                        <div class="sold-overlay {{ $item->is_sold ? 'is_sold' : '' }}">
                            <img class="list_item-image" src="{{ $item->item_image }}" alt="{{ $item->item_name }}">
                            @if ($item->is_sold)
                                <span class="sold-text">SOLD</span>
                            @endif
                        </div>
                    </a>
                </div>
                <div class="list_name-size">
                    <a class="list_item-name" href="/item/{{ $item->id }}">{{ $item->item_name }}</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection