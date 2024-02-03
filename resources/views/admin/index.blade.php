@extends('layouts.admin')

@section('title', 'UniSpace | AdminPanel')

@section('content')
    <div class="flex flex-col">
        <div class="text-xl text-center">
            Заяви ВНЗ
        </div>
        @foreach($inactiveUniversities as $university)
            <div class="border-2 flex flex-row justify-between items-center m-10 p-7 border-gray-800 rounded-md shadow-xl shadow-gray-600">
                <div class="flex flex-col">
                    <div>
                        <b>Назва:</b> {{ $university['name'] }}
                    </div>
                    <div>
                        <b>Місто:</b> {{ $university['city'] }}
                    </div>
                </div>
                <div>
                    <a href="{{ route('university.single', ['id' => $university['id']]) }}"><i class="far fa-eye"></i> Переглянути</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
