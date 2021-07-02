@extends('layouts.mainAdmin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @include('formSearchUser')
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="pull-right">{{ Request::path() == 'customer' ? 'Customer' : 'Crew' }}</h4>
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
                    <div class="mb-3 table-responsive"></div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $number => $user)
                                <tr>
                                    <td>{{ ++$number }}</td>
                                    <td>{{ $user->unique_number }}</td>
                                    <td>{{ $user->name }}   [{{ ucfirst($user->level) }}]</td>
                                    <td>
                                            {{ $user->address }}
                                            <p>
                                                {{ $user->city_name }} -
                                                {{ $user->dis_name }} -
                                                {{ $user->subdis_name }}
                                            </p>
                                    </td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td> Total Cats  : {{ $user->cats->count() ?? '-' }} 
                                        <br>
                                         Free Grooming : {{ $user->freeGrooming->total ?? '-' }}
                                        </td>
                                    <td></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-danger dropdown-toggle btn-xs" type="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Options
                                            </button>
                                            <div class="dropdown-menu" x-placement="bottom-start">
                                                @if ($user->cats->count() > 0)
                                                    <a class="dropdown-item"
                                                        href={{ route('cat.showBy', ['user' => $user->id]) }}>Cat
                                                        List</a>
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
