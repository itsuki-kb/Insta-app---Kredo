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
                                    {{-- If not Soft deleted, display the message --}}
                                    @if (!$chat->deleted_at)
                                        <div class="col-auto ms-auto mb-2 position-relative" style="max-width: 70%">
                                            {{-- long-press-target = key to call JS --}}
                                            <div class="bg-primary text-white rounded rounded-1 px-2 py-1 align-middle long-press-target" data-chat-id="{{ $chat->id }}">
                                                {{ $chat->message }}
                                            </div>

                                            {{-- If I LONG-PRESS a message, display a DELETE button --}}
                                            {{-- "chat-menu" is used in chat.JS --}}
                                            <div class="dropdown-menu chat-menu position-absolute" style="top: 100%; right: 0; display: none;" id="dropdown-{{ $chat->id }}">
                                                <form action="{{ route('profile.chats.destroy', $chat->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                        {{-- Display image --}}
                                        @if ($chat->image)
                                            <div class="row">
                                                <div class="col-auto ms-auto mb-2" style="max-width: 70%">
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#chat-image-{{ $chat->id }}">
                                                        <img src="{{ $chat->image }}" alt="image" class="rounded" style="width: 100px; height: 100px;">
                                                    </button>
                                                </div>
                                                {{-- Inculde MODAL here --}}
                                                @include('users.profile.modals.chat-image')
                                            </div>
                                        @endif
                                    @else
                                        {{-- Soft deleted message --}}
                                        <div class="col-auto ms-auto mb-2 position-relative" style="max-width: 70%">
                                            <p class="text-secondary text-sm">the message was deleted.</p>
                                        </div>
                                    @endif

                                {{-- Received Messages --}}
                                @elseif($chat->sender_id == $user->id && $chat->receiver_id == Auth::user()->id)
                                    @if (!$chat->deleted_at)
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

                                        {{-- Display image --}}
                                        @if ($chat->image)
                                            <div class="row">
                                                <div class="col-auto me-auto ms-5 mb-2" style="max-width: 70%">
                                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#chat-image-{{ $chat->id }}">
                                                        <img src="{{ $chat->image }}" alt="image" class="rounded" style="width: 100px; height: 100px;">
                                                    </button>
                                                </div>
                                                {{-- Inculde MODAL here --}}
                                                @include('users.profile.modals.chat-image')
                                            </div>
                                        @endif
                                    @else
                                        {{-- Soft deleted message --}}
                                        <div class="col-auto me-auto mb-2 position-relative" style="max-width: 70%">
                                            <p class="text-secondary text-sm">the message was deleted.</p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Message FORM --}}
                    <div class="card-footer bg-white">
                        <form action="{{ route('profile.chats.store', $user->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            {{-- Text --}}
                            <div class="input-group my-3">
                                <input type="text" name="chat_message" class="form-control" autofocus>
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </div>

                             {{-- Image --}}
                            <div class="mb-4">
                                <label for="image" class="form-label text-secondary">Image(if any)</label>
                                <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info">
                                <div class="form-text" id="image-info">
                                    The acceptable formats are jpeg, jpg, png and gif only.
                                    Max file is 1048Kb.
                                </div>
                                {{-- Error messages --}}
                                @error('image')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

