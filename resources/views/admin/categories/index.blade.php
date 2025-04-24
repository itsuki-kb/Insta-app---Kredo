@extends('layouts.app')

@section('title', 'Admin: Categories')

@section('content')
    <form action="{{ route('admin.categories.store') }}" method="post" class="row mb-3">
        @csrf
        <div class="col-6 pe-0">
            <input type="text" name="new_category" class="form-control"
                   placeholder="Add a category...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add
            </button>
        </div>
    </form>


    <table class="table table-hover align-middle bg-warning border text-center">
        <thead class="small table-warning">
            <tr>
                <th>#</th> {{-- column for index --}}
                <th>NAME</th>
                <th>COUNT</th>
                <th>LAST_UPDATED</th>
                <th></th> {{-- column for modal buttons --}}
            </tr>
        </thead>

        <tbody>
            @foreach ($all_categories as $category)
                <tr>
                    <td>
                        {{ $category->id }}
                    </td>
                    <td>
                        {{ $category->name }}
                    </td>
                    <td>
                        {{ $category->categoryPost->count() }}
                    </td>
                    <td>
                        {{ date('M d, Y', strtotime($category->updated_at)) }}
                    </td>
                    <td>
                        <button class="btn btn-outline-warning" data-bs-toggle="modal"
                                data-bs-target="#edit-category-{{ $category->id}}">
                            <i class="fa-solid fa-pen"></i>
                        </button>

                        <button class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#delete-category-{{ $category->id}}">
                            <i class="fa-solid fa-trash"></i>
                        </button>

                        {{-- Include deactivate modal --}}
                        @include('admin.categories.modal.action')
                    </td>
                </tr>
            @endforeach

            {{-- Uncategorized count here --}}
            <tr>
                <td></td>
                <td>
                    Uncategorized<br>
                    <span class="text-sm text-muted">Hidden posts are not included.</span>
                </td>
                <td>
                    {{ $uncategorized_posts }}
                </td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>

@endsection
