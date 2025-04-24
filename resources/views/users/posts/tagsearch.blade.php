@extends('layouts.app')

@section('title', 'tag-search')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3>Search results for category: {{ $category_name->name }}</h3>
            <div class="mt-5 row">
                @foreach ($posts as $post)
                <div class="col-4 mb-3">
                    <div class="square-thumb">
                        <a href="{{ route('post.show', $post->id) }}">
                            <img src="{{ $post->image }}" alt="{{ $post->description }}" class="img-thumbnail">
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
