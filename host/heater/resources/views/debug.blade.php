@extends('layouts.main')
@section('content')
    <div id="menu">
        <menu_list></menu_list>
    </div>
    <div class="content">
        <div id="debug">
            <debug_list></debug_list>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/menu.js') }}"></script>
    <script src="{{ mix('js/debugSite.js') }}"></script>
@endsection

