@extends('layouts.mainAdmin')
@section('content')
    @php $action = Request::segment(2) == 'create' ? route('boarding.store', ['user' => $user->id] ) : route('boarding.update', ['boarding' => $boarding->id]) @endphp
    @php $title =  Request::segment(2) == 'create' ? "Add" : "Edit" @endphp
    @php $idUser =  Request::segment(2) == 'create' ? Request::segment(3) : $boarding->owner->id @endphp
    @php $in = date('d-m-Y',strtotime( old('in') ?? now())) @endphp
    @php $out = date('d-m-Y',strtotime( old('out') ?? now()))  @endphp
    @php $action = Request::segment(2) == 'create' ? route('boarding.store', ['user' => $user->id] ) : route('boarding.update', ['boarding' => $boarding->id]) @endphp

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4> {{ $title }} Boarding</h4>
                        </div>
                    </div>
                    <div class="mb-4"></div>
                    <form method='POST' action={{ $action }}>
                        @csrf
                        @if (Request::segment(2) != 'create')
                            @method('PUT')
                        @endif
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <label>Owner :</label>
                                <br>
                                {{ $user->name }}
                                <input name="owner" class="d-none" value="{{ $idUser }}">
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label>Cat</label>
                                    <select class="custom-select" id="cat" name="cat" required>
                                        <option></option>
                                        @foreach ($user->cats as $cat)
                                            <option value="{{$cat->id}}" @if ($cat->id)
                                                selected
                                            @endif>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>IN (Masuk)</label>
                                    <input type="text" class="form-control in" placeholder="" name="in"
                                        value={{ $in }} required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Out (Out)</label>
                                    <input type="text" class="form-control out" placeholder="" name="out"
                                        value={{ $out }} required>
                                </div>
                            </div>
                            <button class="btn bg-maroon text-white btn-md" type=" submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('third_party_scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <script>
        $(function() {
            //Date range picker with time picker
            $('.in,.out').datetimepicker({
                format: 'DD-MM-YYYY',
                icons: {
                    up: "fa fa-chevron-circle-up",
                    down: "fa fa-chevron-circle-down",
                    next: 'fa fa-chevron-circle-right',
                    previous: 'fa fa-chevron-circle-left'
                },
            });
        })
    </script>
@endpush

{{-- @extends('layouts.mainAdmin')
@section('content')
    @php $action = Request::segment(2) == 'create_for' ? route('cat.storeFor', ['user' => $user->id] ) : route('cat.updateFor', ['user' => $user->id]) @endphp
    @php $name = $cat->name ?? old('name') @endphp
    @php $birth_date = $cat->birth_date ?? old('birth_date') @endphp
    @php $owner_id = $cat->owner_id ?? old('owner_id') @endphp
    @php $title =  Request::segment(2) == 'create_for' ? "Add" : "Edit" @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4> {{ $title }} Cat</h4>
                        </div>
                    </div>
                    <div class="mb-4"></div>

                    <form method='POST' action={{ $action }}>
                        @csrf
                        @if (Request::segment(2) != 'create_for')
                            @method('PUT')
                        @endif
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <label>Owner :</label>
                                <br>
                                {{ $user->name }}
                            </div>
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Cat Name :</label>
                                    <input type="text" class="form-control" placeholder="" name="name"
                                        value={{ $name }}>
                                </div>
                            </div>
                            <div class="col-lg-3 d-none">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Owner</label>
                                    <input type="text" class="form-control" placeholder="" name="owner"
                                        value={{ $user->id }}>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Birth Data</label>
                                    <input type="text" class="form-control cat_birth" placeholder="" name="birth_date"
                                        value={{ $birth_date }}>
                                </div>
                            </div>
                            <div class="col-lg-3 d-flex align-self-center mt-3">
                                <button class="btn btn-block bg-maroon text-white btn-md " type="submit">Save</button>
                            </div>
                            <div class="col-lg-3 d-flex align-self-center">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('third_party_scripts')
    <script src="{{ asset('js/jquery.min.js')}}"></script>

    <script>
        $(function() {
            //Date range picker with time picker
            $('.cat_birth').datetimepicker({
                format: 'DD-MM-YYYY',
                icons: {
                    up: "fa fa-chevron-circle-up",
                    down: "fa fa-chevron-circle-down",
                    next: 'fa fa-chevron-circle-right',
                    previous: 'fa fa-chevron-circle-left'
                },
            });
        })
    </script>
@endpush --}}
