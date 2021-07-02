@extends('layouts.mainAdmin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4 class="card-title"> Add Grooming</h4>
                            <p></p>
                            <div class="card-body">
                                <form id='form-grooming' method="POST"
                                    action={{ route('grooming.storeBycat', ['cat' => Request::segment(2)]) }}>
                                    <p class="mb-3"></p>
                                    <div class="row d-flex justify-content-center">
                                        @csrf
                                        <div class=" col-sm-12 col-lg-4">
                                            <label>
                                                Customer :
                                            </label>
                                            <h5>{{ $datas->owner->name }}</h5>
                                        </div>
                                        <div class=" col-sm-12 col-lg-4">
                                            <label>
                                                Cat Name :
                                            </label>
                                            <h5>{{ $datas->name }}</h5>
                                        </div>
                                        <div class=" col-sm-12 col-lg-4">
                                            <label>
                                                Free Grooming :
                                            </label>
                                            <h5>{{ $freeGrooming }}</h5>
                                        </div>
                                        <p class="mb-5 mt-5"></p>
                                        <div class=" col-sm-12 col-lg-3">
                                            {{-- Groomer : <h5>{{ $freeGrooming }}</h5> --}}
                                            <div class="form-group">
                                                <label for="groomer">Groomer</label>
                                                <select class="form-control" id="groomer" name="groomer" required>
                                                    <option></option>
                                                    @foreach ($groomers as $groomer)
                                                        <option value={{ $groomer->id }}
                                                            {{ old('groomer') == $groomer->id ? 'selected' : '' }}>
                                                            {{ $groomer->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-none">
                                            {{-- Groomer : <h5>{{ $freeGrooming }}</h5> --}}
                                            <div class="form-group">
                                                <label for="groomer">Cat</label>
                                                <input id="cat-id" name="cat" value={{ $datas->id }}>
                                                <input id="owner-id" name="owner" value={{ $datas->owner->id }}>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="form-group">
                                                <label for="groomer">Date</label>
                                                <input id="groom_date" name="groom_date" class="groom_date form-control" value="{{ old('groom_date') ?? now() }}">
                                            </div>
                                        </div>
                                        <div class=" col-sm-12 col-lg-3">
                                            {{-- Groomer : <h5>{{ $freeGrooming }}</h5> --}}
                                            <div class="form-group">
                                                <label for="groomer">Payment</label>
                                                <select class="form-control" id="payment" name="payment" required>
                                                    <option></option>
                                                    @if ($freeGrooming > 0 || in_array($datas->owner->level, ['owner', 'crew']))
                                                        <option value="free"
                                                            {{ old('payment') == 'free' ? 'selected' : '' }}>From Free
                                                            Grooming</option>
                                                    @endif
                                                    <option value="cash" {{ old('payment') == 'cash' ? 'selected' : '' }}>
                                                        Cash</option>
                                                    <option value="debit"
                                                        {{ old('payment') == 'debit' ? 'selected' : '' }}>Debit</option>
                                                    <option value="credit"
                                                        {{ old('payment') == 'credit' ? 'selected' : '' }}>Credit
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-center">
                                    <button class="btn bg-danger text-white d-none" cat-id={{ $datas->id }} id="storeGroomingBycat"
                                        cat-name={{ $datas->name }} onclick="showModalSumary(this)">
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
    @include('modalReviewGrooming')
@endsection

@push('third_party_scripts')
    <script src="{{ asset('js/jquery.min.js')}}"></script>

    <script>
        $(function() {
            //Date range picker with time picker
            $('.groom_date').datetimepicker({
                format: 'YYYY-MM-DD HH:mm',
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
