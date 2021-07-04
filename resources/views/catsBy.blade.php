@extends('layouts.mainAdmin')
@section('content')
    <div class="card">
        <div class="card-body row">
            <div class="col-5 text-center d-flex align-items-center justify-content-center">
                <div class="">
                    <h2>{{ $user->name }}</h2>
                    <p class="lead mb-5">
                        {{ ucfirst($user->level) }}
                    </p>
                    {{-- <a href={{ route('cat.createFor', ['user' => $user->id]) }} class="btn bg-navy">
                        Add Cat</a>
                    <a href="{{ route('customer.edit', ['customer' => $user->id]) }}" class="btn bg-maroon"> Update
                        Profile</a> --}}
                </div>
            </div>
            <div class="col-7">
                <table class="table table-responsive-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Cat</th>
                            {{-- <th>Birth Date</th> --}}
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user->cats as $no => $cat)
                            <tr>
                                <td>{{ ++$no }}</td>
                                <td>{{ $cat->name }}</td>
                                <td>
                                    <a href={{ route('cat.edit', ['cat' => $cat->id]) }}>
                                        <button class="btn bg-navy text-white btn-xs" cat-id={{ $cat->id }}
                                            id="storeGroomingBycat">
                                            Edit
                                        </button>
                                    </a>
                                    <a href={{ route('grooming.addBycat', ['cat' => $cat->id]) }}>
                                        <button class="btn bg-danger text-white btn-xs" cat-id={{ $cat->id }}
                                            id="storeGroomingBycat">
                                            Grooming
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            No Data
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    @include('modalReviewGrooming')
@endsection
