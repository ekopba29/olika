@extends('layouts.mainAdmin')
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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
@endpush
