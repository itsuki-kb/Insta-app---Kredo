<div class="modal fade" id="chat-image-{{ $chat->id }}">
    <div class="modal-dialog">
        <div class="modal-content border-success">
            <div class="modal-body">
                <div class="mt-3">
                    <img src="{{ $chat->image }}" alt="chat image" class="w-100 h-100">
                    <p class="mt-1 text-muted">{{ $chat->message }}</p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <form action="" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-dismiss="modal">
                        Close
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
