@extends('layouts.mainAdmin')
@section('content')
    @php $action = Request::segment(2) == 'create' ? route('customer.store') : route('customer.update', ['customer' => $user->id]) @endphp
    @php $name = $user->name ?? old('name') @endphp
    @php $email = $user->email ?? old('email') @endphp
    @php $phone = $user->phone ?? old('phone') @endphp
    @php $level = $user->level ?? old('level') @endphp
    @php $title =  Request::segment(2) == 'create'  ? "Add" : "Edit" @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4> {{ $title }} Crew</h4>
                        </div>
                    </div>
                    <div class="mb-3"></div>
                    <form method='POST' action={{ $action }}>
                        @csrf
                        @if (Request::segment(2) != 'create')
                            @method('PUT')
                        @endif
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control" placeholder="" name="name"
                                        value="{{ $name }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" placeholder="" name="email"
                                        value={{ $email }}>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control" placeholder="" name="phone"
                                        value={{ $phone }}>
                                </div>
                            </div>
                            <div class="col-lg-3 d-flex align-self-center">
                                <button class="btn btn-block bg-maroon text-white btn-md mt-3" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
