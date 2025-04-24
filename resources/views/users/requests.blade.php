@extends('layouts.app')

@section('title', 'Follow Requests')

@section('content')
    <div class="w-50 mx-auto card bg-white shadow border-0 p-4">
        @if ($follow_requests->isNotEmpty())
            <div class="row">
                <div class="col-auto text-center w-100 border-bottom border-2 mb-3">
                    <p class="fw-bold h1 pb-3">Follow Requests</p>
                </div>
            </div>

            @foreach ($follow_requests as $request)
                <div class="row align-items-center mb-3 px-4 py-2">
                    {{-- avatar --}}
                    <div class="col-auto">
                        <a href="{{ route('profile.show', $request->follower->id) }}">
                            @if ($request->follower->avatar)
                                <img src="{{ $request->follower->avatar }}" alt="{{ $request->follower->name }}" class="rounded-circle avatar-md">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                            @endif
                        </a>
                    </div>

                    {{-- Profile --}}
                    <div class="col ps-1 text-truncate">
                        {{-- Name --}}
                        <a href="{{ route('profile.show', $request->follower->id) }}" class="text-decoration-none text-dark fw-bold">
                            {{ $request->follower->name }}
                        </a>

                        {{-- Email --}}
                        <p class="text-muted mb-0">{{ $request->follower->email }}</p>

                        {{-- Number of Follwers --}}
                        <p class="text-muted mb-0">
                            <strong>{{ $request->follower->followers->count() }}</strong> {{ $request->follower->followers->count() == 1 ? 'follower' : 'followers' }}
                        </p>
                    </div>

                    {{-- follow button --}}
                    <div class="col-auto row">
                        <div class="col-auto">
                            <form action="{{ route('follow.accept', $request->follower->id) }}" method="post">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="btn btn-primary border-0 btn-sm">
                                    Accept
                                </button>
                            </form>
                        </div>

                        <div class="col-auto">
                            <form action="{{ route('follow.decline', $request->follower->id) }}" method="post">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger border-0 btn-sm">
                                    Decline
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach

        @else
            <div class="row">
                <div class="col-auto">
                    <p class="fw-bold text-secondary">No Follow Requests now</p>
                </div>
            </div>
        @endif
    </div>
@endsection
