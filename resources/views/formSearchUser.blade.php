<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <h4> Search</h4>
            </div>
        </div>
        <div class="mb-3"></div>
        <form method='GET' action={{ Request::path() == "customer" ? route('customer.index') : route('crew.index') }}>
            <div class="row justify-content-center">
                @if (Request::segment(1) == "customer")
                <div class="col-lg-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Unique Number (ID)</label>
                        <input type="text" class="form-control" placeholder="" name="unique_number"
                            value={{ old('unique_number') }}>
                    </div>
                </div>
                @endif
                <div class="col-lg-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="" name="name"
                            value="{{ old('name') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" placeholder="" name="address"
                            value="{{ old('address') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" placeholder="" name="email"
                            value={{ old('email') }}>
                    </div>
                </div>
                <div class="col-lg-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" placeholder="" name="phone"
                            value={{ old('phone') }}>
                    </div>
                </div>
                @if (Request::path() == "customer")
                <div class="col-lg-3">
                @endif
                @if (Request::path() == "crew")
                <div class="col-lg-3 mt-3 align-self-center">
                @endif
                    @if (Request::path() == "customer")
                    <label>Level</label>
                    <!-- text input -->
                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="member" name="level[]">
                            <label class="form-check-label">Member</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="notmember" name="level[]">
                            <label class="form-check-label">Not Member</label>
                        </div>
                    </div>
                    @endif
                    <button class="btn btn-block bg-maroon text-white btn-md" type=" submit">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>