@extends('layouts.mainAdmin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3> Edit Grooming</h3>
                            <p></p>
                            <div class="card-body">
                                <form id="form-grooming" method="POST" action={{ route('grooming.update', ['idgrooming' => $grooming->id ]) }}>
                                    <p class="mb-3"></p>
                                    @method('PUT')
                                    <div class="row d-flex justify-content-center">
                                        @csrf
                                        <div class=" col-sm-12 col-lg-4">
                                            <label>
                                                Customer :
                                            </label>
                                            <h5>{{ $grooming->owner->name }}</h5>
                                        </div>
                                        <div class=" col-sm-12 col-lg-2">
                                            <div class="form-group">
                                                <label for="groomer">Cat Name</label>
                                                <select class="form-control" id="catName" name="cat"
                                                    required>
                                                    @foreach ($cats as $cat)
                                                        <option value="{{ $cat->id }}"
                                                            {{ $grooming->cat_id == $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <p class="mb-5 mt-5"></p>
                                        <div class=" col-sm-12 col-lg-2">
                                            <div class="form-group">
                                                <label for="groomer">Groomer</label>
                                                <select class="form-control" id="groomer" name="groomer" required="">
                                                    @foreach ($groomers as $item)
                                                        <option value={{ $item->id }}
                                                            {{ $grooming->groomer_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class=" col-sm-12 col-lg-2">
                                            <div class="form-group">
                                                <label for="groomer">Grooming Type</label>
                                                <select class="form-control" id="groomingType" name="grooming_type"
                                                    required>
                                                    @foreach ($groomingType as $itemType)
                                                        <option
                                                            {{ $grooming->groomingtype_id == $itemType->id ? 'selected' : '' }}
                                                            price="Rp. {{ number_format($itemType->price, 0, ',', '.') }}"
                                                            allow-free={{ $itemType->allow_free }}
                                                            grooming-name="{{ $itemType->grooming_name }}"
                                                            value="{{ $itemType->id }}"
                                                            {{ old('grooming_type') == $itemType->id ? 'selected' : '' }}>
                                                            {{ $itemType->grooming_name }} || Rp.
                                                            {{ number_format($itemType->price, 0, ',', '.') }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-danger pull-right">Save</button>
                                        </div>
                                        <div>
                                            <div class="form-group">
                                                <label for="groomer">Date</label>
                                                <input id="groom_date" name="groom_date" class="groom_date form-control"
                                                    value={{ $grooming->grooming_at }}>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-center">
                                    <button class="btn bg-danger text-white d-none" cat-id="1" id="storeGroomingBycat"
                                        cat-name="editcat" onclick="showModalSumary(this)">
                                        Grooming
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
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
            $('.groom_date').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: {
                    up: "fa fa-chevron-circle-up",
                    down: "fa fa-chevron-circle-down",
                    next: 'fa fa-chevron-circle-right',
                    previous: 'fa fa-chevron-circle-left',
                    time: 'fa fa-clock',

                },
            });
        })
    </script>
@endpush
