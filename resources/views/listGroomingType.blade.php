@extends('layouts.mainAdmin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body table-responsive-sm">
                    <div class="d-flex justify-content-between">
                        <h4 class="pull-right">Grooming Types</h4>
                        @if (Auth::user()->level == 'owner')
                            <a href={{ route('grooming_type.create') }}>
                                <button class="btn btn btn-block bg-navy">Add Grooming Type</button>
                            </a>
                        @endif
                    </div>
                    <table class="table">
                        <thead>
                            <th>No</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Allow Free</th>
                            @if (Auth::user()->level == 'owner')
                                <th>Options</th>
                            @endif
                        </thead>
                        <tbody>
                            @forelse ($types as $no => $type)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $type->grooming_name }}</td>
                                    <td>{{ $type->price }}</td>
                                    <td>{{ $type->allow_free }}</td>
                                    @if (Auth::user()->level == 'owner')
                                        <td>
                                            <a href="{{ route('grooming_type.edit', ['grooming_type' => $type->id]) }}">
                                                <button class="btn bg-navy text-white btn-xs" type-id={{ $type->id }}
                                                    id="storeGroomingByType">
                                                    Edit
                                                </button>
                                            </a>
                                    @endif
                                    {{-- <a href={{ route('grooming.addByType', ['Type' => $type->id]) }}>
                                            <button class="btn bg-danger btn-xs text-white" type-id={{ $type->id }}
                                                id="storeGroomingByType">
                                                Grooming
                                            </button>
                                        </a> --}}
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>

                    </table>
                    @if (count($types) < 1)
                        <p></p>
                        <h4 class="text-center">
                            No Data
                        </h4>
                    @endif
                    {!! $types->links('layouts.pagination') !!}
                </div>
            </div>
        </div>
    </div>

@endsection
