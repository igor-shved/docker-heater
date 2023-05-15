@extends('layouts.main')
@section('content')
    <div id="menu">
        <menu_list></menu_list>
    </div>
    <div class="content">
        <div id="app">
            <heater_list></heater_list>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/menu.js') }}"></script>
    <script src="{{ mix('js/heater.js') }}"></script>
@endsection

