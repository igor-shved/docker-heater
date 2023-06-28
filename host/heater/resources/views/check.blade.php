@extends('layouts.main')
@section('content')
    <div class="content">
        <div class="check_status">
            <h1>Работает маршрут {{ $nameRoute }}</h1>
            <p>{{ $data }}</p>
        </div>
    </div>
@endsection

