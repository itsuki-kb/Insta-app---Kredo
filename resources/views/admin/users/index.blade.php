@extends('layouts.app')

@section('title', 'Admin: Users')

@section('content')
    {{-- search bar --}}
    <div class="row mb-3">
        <form action="{{ route('admin.users.search') }}" style="width: 300px" class="col-auto ms-auto">
            <input type="search" name="search" id="search" class="form-control form-control-sm"
                   placeholder="Search by name...">
        </form>
    </div>


    <table class="table table-hover align-middle bg-white border text-secondary">
        <thead class="small table-success text-secondary">
            <tr>
                <th></th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>CREATED_AT</th>
                <th>STATUS</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($all_users as $user)
                <tr>
                    <td>
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}" alt="" class="rounded-circle d-block mx-auto avatar-md">
                        @else
                            <i class="fa-solid fa-circle-user text-center icon-md d-block"></i>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark fw-bold">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ date('M d, Y', strtotime($user->created_at)) }}</td>
                    <td>
                        {{-- $user->trashed() returns TRUE if the user is soft deleted --}}
                        @if ($user->trashed())
                            <i class="fa-regular fa-circle text-secondary"></i>&nbsp; Inactive
                        @else
                            <i class="fa-solid fa-circle text-success"></i>&nbsp; Active
                        @endif
                    </td>
                    <td>
                        @if (Auth::user()->id !== $user->id)
                            <div class="dropdown">
                                <button class="btn btn-sm" data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </button>

                                <div class="dropdown-menu">
                                    @if ($user->trashed())
                                        <button class="dropdown-item text-success" data-bs-toggle="modal"
                                                data-bs-target="#activate-user-{{ $user->id}}">
                                            <i class="fa-solid fa-user-check"></i> Activate {{ $user->name }}
                                        </button>
                                    @else
                                        <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                                data-bs-target="#deactivate-user-{{ $user->id}}">
                                            <i class="fa-solid fa-user-slash"></i> Deactivate {{ $user->name }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                            {{-- Include deactivate modal --}}
                            @include('admin.users.modal.status')
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Pagination - AppServiceProviderでデザイン調整済み --}}
    {{ $all_users->links()}}
@endsection
