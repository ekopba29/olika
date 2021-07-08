@extends('layouts.mainAdmin')
@section('content')
    @php $action = Request::segment(2) == 'create' ? route('grooming_type.store') : route('grooming_type.update', ['grooming_type' => $groomingType->id]) @endphp
    @php $name = $groomingType->grooming_name ?? old('grooming_name') @endphp
    @php $price = $groomingType->price ?? old('price') @endphp
    @php $allow_free = $groomingType->allow_free ?? old('allow_free') @endphp
    @php $title =  Request::segment(2) == 'create'  ? "Add" : "Edit" @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4> {{ $title }} Grooming Type</h4>
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
                                    <input type="text" class="form-control" placeholder="" name="grooming_name"
                                        value="{{ $name }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" class="form-control" placeholder="" name="price"
                                        value={{ $price }}>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Allow Free</label>
                                    <br>
                                    <input type="radio" class="" value="y" placeholder="" name="allow_free"
                                        {{ $allow_free == 'y' ? 'checked' : false }}> Yes
                                        <div></div>
                                    <input type="radio" class="" value="n" placeholder="" name="allow_free"
                                        {{ $allow_free == 'n' ? 'checked' : false }}> No
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
