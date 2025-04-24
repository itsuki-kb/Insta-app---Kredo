@extends('layouts.app')

@section('title', 'Suggested Users')

@section('content')
    <div class="w-50 mx-auto card bg-white shadow border-0 p-4">
        @if ($suggested_users)
            <div class="row">
                <div class="col-auto text-center w-100 border-bottom border-2 mb-3">
                    <p class="fw-bold h1 pb-3">Suggested Users</p>
                </div>
            </div>

            @foreach ($suggested_users as $user)
                <div class="row align-items-center mb-3 px-4 py-2">
                    {{-- avatar --}}
                    <div class="col-auto">
                        <a href="{{ route('profile.show', $user->id) }}">
                            @if ($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle avatar-md">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                            @endif
                        </a>
                    </div>

                    {{-- Profile --}}
                    <div class="col ps-1 text-truncate">
                        {{-- Name --}}
                        <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold">
                            {{ $user->name }}
                        </a>

                        {{-- Email --}}
                        <p class="text-muted mb-0">{{ $user->email }}</p>

                        {{-- Number of Follwers --}}
                        <p class="text-muted mb-0">
                            <strong>{{ $user->followers->count() }}</strong> {{ $user->followers->count() == 1 ? 'follower' : 'followers' }}
                        </p>
                    </div>

                    {{-- follow button --}}
                    <div class="col-auto">
                        <form action="{{ route('follow.store', $user->id) }}" method="post">
                            @csrf

                            <button type="submit" class="btn btn-primary border-0 btn-sm">
                                Follow
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach

        @else
            <div class="row">
                <div class="col-auto">
                    <p class="fw-bold text-secondary">No suggestions now</p>
                </div>
            </div>
        @endif
    </div>
@endsection
