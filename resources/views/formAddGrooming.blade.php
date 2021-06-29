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
                                    action={{ route('grooming.store', [
    'user' => Request::segment(2),
]) }}>
                                    <div class="row">
                                        @csrf
                                        <div class=" col-sm-12 col-lg-3">
                                            <label>
                                                Customer :
                                            </label>
                                            <h5>{{ $user->name }}</h5>
                                        </div>
                                        <div class=" col-sm-12 col-lg-3">
                                            <label>
                                                Free Grooming :
                                            </label>
                                            <h5>{{ $freeGrooming }}</h5>
                                        </div>
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
                                            </div>
                                        </div>
                                        <div class=" col-sm-12 col-lg-3">
                                            <div class="form-group">
                                                <label for="groomer">Payment</label>
                                                <select class="form-control" id="payment" name="payment" required>
                                                    <option></option>
                                                    @if ($freeGrooming > 0 || in_array($user->level, ['owner', 'crew']))
                                                        <option value="free"
                                                            {{ old('payment') == 'free' ? 'selected' : '' }}>From Free
                                                            Grooming</option>
                                                    @endif
                                                    <option value="cash"
                                                        {{ old('payment') == 'cash' ? 'selected' : '' }}>Cash</option>
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