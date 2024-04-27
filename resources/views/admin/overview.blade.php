<?php
/**
 * @var array $universities
 */
?>

@extends('layouts.admin')

@section('title', 'UniSpace | AdminPanel')

@section('content')
    <div class="flex flex-col items-center">
        @include('admin.university.searchTool')

        @foreach($universities as $i => $university)
            <div class="university-block">
                <div class="main-data-block">
                    <div class="text-6xl mx-6">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div class="flex flex-col">
                        <div class="text-3xl">
                            {{ $university['name'] }}, {{ $university['city'] }}
                        </div>
                        <div class="text-xl">
                            {{ date('d M Y', strtotime($university['created_at'])) }}
                        </div>
                    </div>
                </div>
                <div class="actions-block">
                    <a href="{{ route('university.single', ['universityId' => $university['id']]) }}" target="_blank">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <i class="fa-solid fa-trash"></i>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/overview.js')}}"></script>
@endpush
