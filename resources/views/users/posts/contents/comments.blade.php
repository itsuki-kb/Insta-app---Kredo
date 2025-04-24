<div class="mt-3">
    {{-- show all comments of the post here --}}
    @if ($post->comments->isNotEmpty())
        <hr>
        <ul class="list-group">
            @foreach ($post->comments->take(3) as $comment)
                <li class="list-group-item border-0 p-0 mb-2">
                    <a href="{{ route('profile.show', $comment->user->id) }}" class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                    &nbsp;
                    <p class="d-inline fw-light">{{ $comment->body }}</p>

                    {{-- DELETE comment --}}
                    <form action="{{ route('comment.destroy', $comment->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <span class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($comment->created_at)) }}</span>

                        {{-- if the Auth user is the owner of the comment, show delete button. --}}
                        @if (Auth::user()->id === $comment->user->id)
                            &middot;
                            <button type="submit" class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                        @endif
                    </form>
                </li>
            @endforeach

            {{-- link to view all comments on show post page --}}
            @if ($post->comments->count() > 3)
                <li class="list-group-item border-0 px-0 pt-0">
                    <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none small">
                        View all {{ $post->comments->count() }} comments
                    </a>
                </li>
            @endif
        </ul>
    @endif

    <form action="{{ route('comment.store', $post->id) }}" method="post">
        @csrf

        <div class="input-group">
            <textarea name="comment_body" rows="1" placeholder="Add a comment..."
                      class="form-control form-control-sm">{{ old('comment_body') }}</textarea>

            <button type="submit" class="btn btn-outline-secondary btn-sm">
                <i class="fa-regular fa-paper-plane"></i>
            </button>
        </div>
        {{-- error --}}
        @error('comment_body')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
    </form>
</div>
