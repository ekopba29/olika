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
                    <div class="mb-3 "></div>
                    <div class="table-responsive">
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
                                    @if (Auth::user()->level == 'owner')
                                        <th>Free Grooming Manual</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $number => $user)
                                    <tr>
                                        <td>{{ ++$number }}</td>
                                        <td>{{ $user->unique_number }}</td>
                                        <td>{{ $user->name }} [{{ ucfirst($user->level) }}]</td>
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
                                        <td>
                                            Total Cats : {{ $user->cats->count() ?? '-' }}
                                            <br>
                                            Free Grooming : {{ $user->freeGrooming->total ?? '-' }}
                                            <br>
                                            Total Grooming : {{ $user->groomingsCustomer->count() ?? '-' }}
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
                                                            List
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href={{ route('boarding.create', ['user' => $user->id]) }}>
                                                            Boarding
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href={{ route('grooming.add', ['user' => $user->id]) }}>
                                                            Grooming
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href={{ route('grooming.reportBy', ['user' => $user->id]) }}>
                                                            Report
                                                        </a>
                                                    @endif
                                                    @if ($user->level == 'notmember')
                                                        <a class="dropdown-item" href="#"
                                                            route="{{ route('customer.upgradeToMember', ['user' => $user->id]) }}"
                                                            owner="{{ $user->name }}"
                                                            onclick="confrimUpgrade(this)">Upgrade
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
                                        @if (Auth::user()->level == 'owner')
                                            <td>
                                                <form method="POST"
                                                    action={{ route('customer.setFreegroomingManual', ['user' => $user->id]) }}>
                                                    @csrf
                                                    <input type="number" name="total"
                                                        value="{{ $user->freeGrooming->total ?? '0' }}">
                                                    <button type="submit">update</button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
    <div class="modal fade show" id="confirmationUpgrade" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-danger" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation Upgrade to Member</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            Upgrade &nbsp; <span class="confrimName"> </span> &nbsp; to Member ?
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a href="" id="submitUpgrade">
                        <button class="btn btn-danger" type="button">Confirm</button>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('third_party_scripts')

    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <script>
        function confrimUpgrade(element) {

            const link = $(element).attr("route");
            console.log(link)
            const name = $(element).attr("owner");

            $(".confrimName").text("  " + name);
            $("#submitUpgrade").attr('href', link);

            $('#confirmationUpgrade').modal({
                show: true
            });
        }

        function submitUpgrade(e) {
            $("#form-grooming").submit();
        }
    </script>
@endpush
