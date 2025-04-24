{{-- Deactivate --}}
<div class="modal fade" id="deactivate-post-{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-danger">
            <div class="modal-header border-danger">
                <h3 class="h5 modal-title text-danger">
                    <i class="fa-solid fa-eye-slash"></i> Hide Post
                </h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to hide this post?</p>
                <div class="">
                    <img src="{{ $post->image }}" alt="" class="image-lg">
                    <p class="figure-caption pt-1">{{ $post->description }}</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.posts.deactivate', $post->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger btn-sm">Hide</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Activate --}}
<div class="modal fade" id="activate-post-{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-success">
            <div class="modal-header border-success">
                <h3 class="h5 modal-title text-success">
                    <i class="fa-solid fa-eye"></i> Unhide Post
                </h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to unhide this post?</p>
                <div class="">
                    <img src="{{ $post->image }}" alt="" class="image-lg">
                    <p class="figure-caption pt-1">{{ $post->description }}</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.posts.activate', $post->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success btn-sm">Unhide</button>
                </form>
            </div>
        </div>
    </div>
</div>
