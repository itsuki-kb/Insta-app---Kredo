@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    @include('users.profile.header')

    {{-- show all the followers here --}}
    <div style="margin-top: 100px">
        @if ($notificationDetails->isNotEmpty())
            <div class="row justify-content-center">
                <div class="col-auto">
                    <h3 class="text-muted text-center">Notifications</h3>

                    @foreach ($notificationDetails as $detail)
                        <div class="row align-items-center mt-3">
                            <div class="col-auto">
                                <a href="{{ route('profile.show', $detail['sender_id']) }}">
                                    @if ($avatar = $detail['avatar'])
                                        <img src="{{ $avatar }}" alt="{{ $avatar }}" class="rounded-circle avatar-sm">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                    @endif
                                </a>
                            </div>

                            <div class="col-auto">
                                <a href="{{ route('profile.chats', $detail['sender_id']) }}" class="text-decoration-none">
                                    <div class=" text-black">
                                        {{ $detail['count'] }} message{{ $detail['count'] > 1 ? 's' : '' }} from {{ $detail['sender_name'] }}
                                    </div>
                                    <div class="text-muted">
                                        {{ $detail['last_message'] }}
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <h3 class="text-muted text-center">No Notifications Now</h3>
        @endif
    </div>
@endsection
