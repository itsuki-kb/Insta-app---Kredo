@extends('layouts.app')

@section('title', 'Chat')

@section('content')
    @include('users.profile.header')

    <div style="margin-top: 100px">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card">
                    <div class="card-body overflow-auto" style="height: 400px">
                        @foreach ($chats as $chat)
                            <div class="row">
                                {{-- Sent Messages --}}
                                @if ($chat->sender_id == Auth::user()->id  && $chat->receiver_id == $user->id)
                                    <div class="col-auto ms-auto mb-2" style="max-width: 70%">
                                        <div class="bg-primary text-white rounded rounded-1 px-2 py-1 align-middle">{{ $chat->message }}</div>
                                    </div>

                                {{-- Received Messages --}}
                                @elseif($chat->sender_id == $user->id && $chat->receiver_id == Auth::user()->id)
                                    <div class="row col-8 me-auto mb-2 ps-0" >
                                        <div class="col-1 w-25 pe-0 me-0 text-center">
                                            @if ($user->avatar)
                                                <img src="{{ $user->avatar }}" alt="{{ $user->avatar }}" class="avatar-sm rounded-circle align-middle">
                                            @else
                                                <i class="fa-solid fa-user avatar-sm text-white bg-secondary rounded-circle text-center pt-2 align-middle"></i>
                                            @endif
                                        </div>

                                        <div class="col-auto ps-0" style="max-width: 70%">
                                            <div class="rounded rounded-1 px-2 py-1 align-middle bg-secondary text-white">{{ $chat->message }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white">
                        <form action="{{ route('profile.chats.store', $user->id) }}" method="post">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="chat_message" class="form-control" autofocus>
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

