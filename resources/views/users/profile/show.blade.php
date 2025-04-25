@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    @include('users.profile.header')

    {{-- Show all posts here --}}
    <div class="mt-3 mt-md-5">
    {{-- <div style="margin-top: 100px"> --}}
        @can('ViewProfile', $user)
            @if ($user->posts->isNotEmpty())
            <div class="row">
                @foreach ($user->posts as $post)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <a href="{{ route('post.show', $post->id) }}">
                            <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="grid-img">
                        </a>
                    </div>
                @endforeach
            </div>
            @else
                <h3 class="text-muted text-center">No Posts Yet.</h3>
            @endif
        @else
            <h3 class="text-muted text-center">This is a private account.
            </h3>
        @endcan

    </div>
@endsection
