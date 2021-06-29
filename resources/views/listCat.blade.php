@extends('layouts.mainAdmin')

@section('content')
    <div class="fade-in">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <h3> Search</h3>
                            </div>
                        </div>
                        <div class="mb-3"></div>
                        <form method="GET" action={{ route('cat.index') }}>
                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Cat Name</label>
                                        <input type="text" class="form-control" placeholder="" name="cat_name" value="">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <!-- text input -->
                                    <div class="form-group">
                                        <label>Owner</label>
                                        <input type="text" class="form-control" placeholder="" name="owner" value="">
                                    </div>
                                </div>

                                <div class="col-lg-3 mt-3 align-self-center">
                                    <button class="btn btn-block bg-maroon text-white btn-md" type=" submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3>All Cat</h3>
                        <table class="table table-responsive-sm">
                            <thead>
                                <th>Name</th>
                                <th>Owner</th>
                                <th>Options</th>
                            </thead>
                            <tbody>
                                @forelse ($cats as $cat)
                                    <tr>
                                        <td>{{ $cat->name }}</td>
                                        <td>{{ $cat->owner->name }}</td>
                                        <td>
                                            <a href={{ route('grooming.addBycat', ['cat' => $cat->id]) }}>
                                                <button class="btn bg-danger text-white" cat-id={{ $cat->id }}
                                                    id="storeGroomingBycat">
                                                    Grooming
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>

                        </table>
                        @if (count($cats) < 1)
                            <p></p>
                            <h4 class="text-center">
                                No Data
                            </h4>
                        @endif
                        {!! $cats->links('layouts.pagination') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
