<div class="row">
    {{-- avatar/icon --}}
    <div class="col-4">
        {{-- display the avatar of the user --}}
        @if ($user->avatar)
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle p-1 shadow mx-auto avatar-lg">
        @else
            <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
        @endif
    </div>

    <div class="col-8">
        {{-- name & button(Edit, Follow) --}}
        <div class="row mb-3">
            {{-- name --}}
            <div class="col-auto">
                <h2 class="display-6 mb-0">
                    {{ $user->name }}
                    @if ( $user->private == 1 )
                        <i class="fa-solid fa-lock xsmall"></i>
                    @endif
                </h2>

            </div>
            {{-- button --}}
            <div class="col-auto p-2">
                @if (Auth::user()->id === $user->id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm fw-bold">Edit Profile</a>
                @else
                    @if ($user->isFollowed())
                        @if ($user->hasPendingRequest())
                            {{-- Pending / cancel requesting --}}
                            <form action="{{ route('follow.destroy', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Follow request sent</button>
                            </form>
                        @else
                            {{-- unfollow user --}}
                            <form action="{{ route('follow.destroy', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Following</button>
                            </form>
                        @endif
                    @else
                        {{-- follow user --}}
                        <form action="{{ route('follow.store', $user->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                        </form>
                    @endif
                @endif
            </div>

            {{-- MESSAGE --}}
            {{-- Check if allowed user --}}
            @can('viewProfile', $user)
                {{-- Check if this is not the page of myself --}}
                @if (Auth::user()->id !== $user->id)
                    <div class="col-auto p-2">
                        <a href="{{ route('profile.chats', $user->id) }}" class="btn btn-outline-secondary btn-sm fw-bold">
                            <i class="fa-solid fa-message px-2"></i>
                        </a>
                    </div>
                @endif
            @endcan

        </div>

        {{-- posts/followers/following --}}
        <div class="row mb-3">
            {{-- posts --}}
            <div class="col-auto">
                <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark">
                    {{-- condition ? true statement : false statement --}}
                    <strong>{{ $user->posts->count() }}</strong> {{ $user->posts->count() == 1 ? 'post' : 'posts' }}
                </a>
            </div>

            @php
                $canView = Auth::user()->can('viewProfile', $user);
            @endphp
                {{-- followers --}}
                <div class="col-auto">
                    <a href="{{ route('profile.followers', $user->id) }}" class="text-decoration-none text-dark"
                       style="{{ $canView ? '' : 'pointer-events: none; cursor: not-allowed;' }}">
                        <strong>{{ $user->followers->count() }}</strong> {{ $user->followers->count() == 1 ? 'follower' : 'followers' }}
                    </a>
                </div>

                {{-- following --}}
                <div class="col-auto">
                    <a href="{{ route('profile.following', $user->id) }}" class="text-decoration-none text-dark"
                        style="{{ $canView ? '' : 'pointer-events: none; cursor: not-allowed;' }}">
                        <strong>{{ $user->following->count() }}</strong> following
                    </a>
                </div>

            {{-- notifications --}}
            @if (Auth::user()->id == $user->id)
                <div class="col-auto">
                    <a href="{{ route('profile.notifications', $user->id) }}" class="text-decoration-none text-dark">
                        Notifications
                    </a>
                </div>
            @endif
        </div>
        <div class="fw-bold">{{ $user->introduction }}</div>
    </div>
</div>
