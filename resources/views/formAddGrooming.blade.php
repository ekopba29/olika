@extends('layouts.mainAdmin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4> Add Grooming</h4>
                            <p></p>
                            <div class="card-body">
                                <form id='form-grooming' method="POST"
                                    action={{ route('grooming.store', ['user' => Request::segment(2)]) }}>
                                    <div class="row">
                                        @csrf
                                        <div class="col-sm-12 col-lg-12 d-flex">
                                            <div class=" col-sm-12 col-lg-6 d-flex justify-content-end m-3">
                                                <div>
                                                    <label>
                                                        Customer :
                                                    </label>
                                                    <h2 class="text-danger">{{ $user->name }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-lg-6 d-flex justify-content-start m-3">
                                                <div>
                                                    <label>
                                                        Free Grooming :
                                                    </label>
                                                    <h2 class="text-right text-orange">{{ $freeGrooming }}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex col-lg-12 justify-content-around">
                                            <div class=" col-sm-12 col-lg-3">
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
                                                <div class="form-group">
                                                    <label for="groomer">Cat</label>
                                                    <input id="cat-id" name="cat" value="{{ old('cat') }}">
                                                    <input id="owner" name="owner" value="{{ $user->id }}">
                                                </div>
                                            </div>
                                            <div>
                                                <div class="form-group">
                                                    <label for="groomer">Date</label>
                                                    <input id="groom_date" name="groom_date" class="groom_date form-control"
                                                        value="{{ old('groom_date') ?? now() }}">
                                                </div>
                                            </div>
                                            <div class=" col-sm-12 col-lg-3">
                                                <div class="form-group">
                                                    <label for="groomer">Grooming Type</label>
                                                    <select class="form-control" id="groomingType" name="grooming_type" required>
                                                        <option></option>
                                                        @foreach ($groomingType as $itemType)
                                                            <option 
                                                            price="Rp. {{ number_format($itemType->price, 0, ',', '.')}}"
                                                            allow-free={{ $itemType->allow_free }}
                                                            grooming-name="{{ $itemType->grooming_name }}"
                                                            value="{{ $itemType->id }}"
                                                                {{ old('grooming_type') == $itemType->id ? 'selected' : '' }}>
                                                                {{ $itemType->grooming_name }} || Rp. {{ number_format($itemType->price, 0, ',', '.')}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class=" col-sm-12 col-lg-3">
                                                <div class="form-group">
                                                    <label for="groomer">Payment</label>
                                                    <select class="form-control" id="payment" name="payment" required>
                                                        <option></option>
                                                        @if ($freeGrooming > 0 || in_array($user->level, ['owner', 'crew']))
                                                            <option value="free"
                                                                {{ old('payment') == 'free' ? 'selected' : '' }}>From
                                                                Free
                                                                Grooming</option>
                                                        @endif
                                                        <option value="cash"
                                                            {{ old('payment') == 'cash' ? 'selected' : '' }}>Cash
                                                        </option>
                                                        <option value="debit"
                                                            {{ old('payment') == 'debit' ? 'selected' : '' }}>Debit
                                                        </option>
                                                        <option value="credit"
                                                            {{ old('payment') == 'credit' ? 'selected' : '' }}>Credit
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 d-none" id="table-cat">
            <div class="card">
                <div class="card-body">
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
                            @forelse ($cats as $no => $cat)
                                <tr>
                                    <td>{{ ++$no }}</td>
                                    <td>{{ $cat->name }}</td>
                                    {{-- <td>{{ $cat->birth_date }}</td> --}}
                                    <td>
                                        <button class="btn bg-danger text-white" cat-id="{{ $cat->id }}"
                                            cat-name="{{ $cat->name }}" onclick="showModalSumary(this)">
                                            Grooming
                                        </button>
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
    </div>
    @include('modalReviewGrooming')
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
