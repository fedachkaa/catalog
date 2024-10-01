<?php
/**
 * @var array $topicsData
 */
?>
@extends('layouts.main')

@section('title', 'Topics | UniSpace')

@section('content')
    @include('userProfile.universityAdminProfile.partials.sidebar-template')

    <div class="pl-60">
        <h1 class="title">Теми</h1>
        <ol class="list-decimal text-xl flex flex-col gap-2 items-center m-5">
            @foreach($topicsData as $topic)
                <ul class="list-item subject-item">{{ $topic['topic'] }}</ul>
            @endforeach
        </ol>
    </div>
@endsection
