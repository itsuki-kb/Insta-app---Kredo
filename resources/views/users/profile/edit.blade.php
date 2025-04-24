@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-8">
            <form action="{{ route('profile.update') }}" method="post" class="bg-white shadow roudend-3 p-5"
                  enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                {{-- avatar --}}
                <div class="row mb-3">
                    <div class="col-4">
                        {{-- display the avatar of the user --}}
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle p-1 shadow mx-auto avatar-lg">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
                        @endif
                    </div>
                    <div class="col-auto align-self-end">
                        <input type="file" name="avatar" id="avatar" class="form-control form-control-sm mt-1">
                        <div class="form-text">
                            Acceptable formats: jpeg, jpg, png and gif only. <br>
                            Max file size is 1048kb.
                        </div>
                        {{-- Error --}}
                        @error('avatar')
                            <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- name --}}
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ old('name', $user->name) }}" autofocus>
                    {{-- Error --}}
                    @error('name')
                        <p class="text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                {{-- email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">E-mail Address</label>
                    <input type="email" name="email" id="email" class="form-control"
                           value="{{ old('email', $user->email) }}">
                    {{-- Error --}}
                    @error('email')
                        <p class="text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                {{-- introduction --}}
                <div class="mb-3">
                    <label for="introduction" class="form-label fw-bold">Introduction</label>
                    <textarea name="introduction" id="introduction" rows="5" class="form-control"
                              placeholder="Describe yourself">{{ old('introduction', $user->introduction) }}</textarea>
                    {{-- Error --}}
                    @error('introduction')
                        <p class="text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Private --}}
                <div class="mb-3 form-check">
                    <input type="checkbox" name="private" id="private" class="form-check-input"
                           {{ old('private', $user->private) ? 'checked' : '' }}>
                    <label for="private" class="form-check-label">Make my profile private</label>
                    <div class="form-text">
                        If you check this box, only you and your friends can see your profile.
                    </div>
                </div>

                <button type="submit" class="btn btn-warning px-5">Save</button>

            </form>
        </div>
    </div>
@endsection
