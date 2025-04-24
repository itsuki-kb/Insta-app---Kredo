@extends('layouts.app')

@section('title', 'Admin: Posts')

@section('content')
    <table class="table table-hover align-middle bg-primary border">
        <thead class="small table-primary">
            <tr>
                <th></th> {{-- column for index --}}
                <th></th> {{-- column for image --}}
                <th>CATEGORY</th>
                <th>OWNER</th>
                <th>CREATED_AT</th>
                <th>STATUS</th>
                <th></th> {{-- column for dropdown --}}
            </tr>
        </thead>

        <tbody>
            @foreach ($all_posts as $post)
                <tr>
                    <td>
                        {{ $post->id }}
                    </td>
                    <td>
                        <img src="{{ $post->image }}" alt="" class="d-block mx-auto image-lg">
                    </td>
                    <td>
                        @forelse ($post->categoryPost as $category)
                            <div class="badge bg-secondary">{{ $category->category->name }}</div>
                        @empty
                            <div class="badge bg-dark">Uncategorized</div>
                        @endforelse
                    </td>
                    <td>
                        <a href="{{ route('profile.show', $post->user_id) }}" class="text-decoration-none text-dark fw-bold">
                            {{ $post->user->name }}
                        </a>
                    </td>
                    <td>{{ date('M d, Y', strtotime($post->created_at)) }}</td>
                    <td>
                        {{-- $post->trashed() returns TRUE if the post is soft deleted --}}
                        @if ($post->trashed())
                            <i class="fa-regular fa-circle text-secondary"></i>&nbsp; Hidden
                        @else
                            <i class="fa-solid fa-circle text-primary"></i>&nbsp; Visible
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>

                            <div class="dropdown-menu">
                                @if ($post->trashed())
                                    <button class="dropdown-item text-success" data-bs-toggle="modal"
                                            data-bs-target="#activate-post-{{ $post->id}}">
                                        <i class="fa-solid fa-eye"></i> Unhide Post {{ $post->id }}
                                    </button>
                                @else
                                    <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#deactivate-post-{{ $post->id}}">
                                        <i class="fa-solid fa-eye-slash"></i> Hide Post {{ $post->id }}
                                    </button>
                                @endif
                            </div>
                        </div>
                        {{-- Include deactivate modal --}}
                        @include('admin.posts.modal.status')
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Pagination - AppServiceProviderでデザイン調整済み --}}
    {{ $all_posts->links()}}
@endsection
