@extends('layouts.mainAdmin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @include('formSearchUser')
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="pull-right">{{(Request::path() == 'customer') ? 'Customer' : 'Crew'}}</h4>
                        @if (Request::path() == 'customer')
                            <a href={{ route('customer.create') }}>
                                <button class="btn btn btn-block bg-navy">Add Customer</button>
                            </a>
                        @endif
                        @if (Request::path() == 'crew')
                            <a href={{ route('crew.create') }}>
                                <button class="btn btn btn-block bg-navy">Add Crew</button>
                            </a>
                        @endif
                    </div>
                    <div class="mb-3"></div>
                    <table class="table table-responsive-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Level</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Total Cats</th>
                                <th>Free Grooming</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $number => $user)
                                <tr>
                                    <td>{{ ++$number }}</td>
                                    <td>{{ $user->unique_number }}</td>
                                    <td>{{ ucfirst($user->level) }}</td>
                                    <td>{{ $user->name }}</td>
                                    {{-- <td>{{ $user->username }}</td> --}}
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->cats_count ?? '-' }}</td>
                                    <td>{{ $user->freeGrooming->total ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-danger dropdown-toggle" type="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Options
                                            </button>
                                            <div class="dropdown-menu" x-placement="bottom-start">
                                                @if ($user->cats_count > 0)
                                                    <a class="dropdown-item"
                                                        href={{ route('cat.showBy', ['user' => $user->id]) }}>Cat List</a>
                                                    <a class="dropdown-item"
                                                        href={{ route('grooming.add', ['user' => $user->id]) }}>
                                                        Grooming
                                                    </a>
                                                @endif
                                                @if ($user->level == 'notmember')
                                                    <a class="dropdown-item"
                                                        href={{ route('customer.upgradeToMember', ['user' => $user->id]) }}>Upgrade
                                                        to Member</a>
                                                @endif
                                                <a href={{ route('cat.createFor', ['user' => $user->id]) }}
                                                    class="dropdown-item">
                                                    Add Cat</a>
                                                <a href="{{ route('customer.edit', ['customer' => $user->id]) }}"
                                                    class="dropdown-item"> Update Profile</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                    <p>
                        @if (count($users) < 1)
                            <h4 class="text-center">
                                No Data
                            </h4>
                        @endif
                    </p>
                    {{ $users->links('layouts.pagination') }}
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
