<div class="modal fade" id="like-list-{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <div class="h5 modal-title text-danger mx-auto">
                    <i class="fa-solid fa-heart"></i> Likes
                </div>
            </div>

            <div class="modal-body mx-auto">
                @forelse ($post->likes as $like)
                    <div class="row align-items-center mb-2">
                        {{-- avatar --}}
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $like->user->id) }}">
                                @if ($like->user->avatar)
                                    <img src="{{ $like->user->avatar }}" alt="{{ $like->user->name }}" class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>

                        {{-- name --}}
                        <div class="col ps-0">
                            <a href="{{ route('profile.show', $like->user->id) }}" class="text-decoration-none text-dark">
                                {{ $like->user->name }}
                            </a>
                        </div>
                    </div>
                @empty
                    No likes yet.
                @endforelse
            </div>
        </div>
    </div>
</div>
