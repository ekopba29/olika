<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12">
                <h4> Search</h4>
            </div>
        </div>
        <div class="mb-3"></div>
        <form method='GET' action={{ Request::path() == 'customer' ? route('customer.index') : route('crew.index') }}>
            <div class="row justify-content-center">
                @if (Request::segment(1) == 'customer')
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
                        <input type="text" class="form-control" placeholder="" name="name" value="{{ old('name') }}">
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
                @if (Request::segment(1) == 'customer')

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Cities (Kota)</label>
                            <select class="custom-select" id="cities" name="cities">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Districts (Kacamatan)</label>
                            <select class="custom-select" id="districts" name="districts">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Sub Districts (Kelurahan)</label>
                            <select class="custom-select" id="subdistricts" name="subdistricts">
                                <option></option>
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col-lg-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" placeholder="" name="email" value={{ old('email') }}>
                    </div>
                </div>
                <div class="col-lg-3">
                    <!-- text input -->
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" placeholder="" name="phone" value={{ old('phone') }}>
                    </div>
                </div>
                @if (Request::path() == 'customer')
                    <div class="col-lg-3">
                @endif
                @if (Request::path() == 'crew')
                    <div class="col-lg-3 mt-3 align-self-center">
                @endif
                @if (Request::path() == 'customer')
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
@push('third_party_scripts')
    <script src="{{ asset('js/jquery.min.js')}}"></script>

    <script>
        $(function() {
            let all_resp_addr;

            $.ajax({
                type: "get",
                url: @php echo '"' .route('addresser') . '"'@endphp,
                dataType: "json",
                success: function(response) {
                    lempar_keluar_ajax(response);
                }
            });

            function lempar_keluar_ajax(response) {
                all_resp_addr = response;
                build_city();
            }

            function build_city() {
                const city = Object.keys(all_resp_addr);
                $.map(city, function(elementOrValue, indexOrKey) {
                    $("#cities").append("<option value=" + elementOrValue + ">" + elementOrValue.substr(
                        4) + "</options>");
                });
                build_kecamatan();
            }

            function build_kecamatan() {
                $("#cities").change(function(e) {
                    if ($("#cities").val() != "") {
                        const city = $(this).val();
                        const subdistricts = Object.keys(all_resp_addr[city]);
                        $("#districts").html('<option></option>');
                        $.map(subdistricts, function(elementOrValue, indexOrKey) {
                            $("#districts").append("<option value=" + elementOrValue + ">" +
                                elementOrValue.substr(5) + "</option>");
                        });
                        build_kelurahan();
                    } else {
                        $("#districts").html('<option></option>');
                    }
                });
            }

            function build_kelurahan() {
                $("#districts,#cities").change(function(e) {
                    if ($("#districts").val() != "") {
                        const city = $("#cities").val();
                        const kacamatan = $("#districts").val();
                        const subdistricts = all_resp_addr[city][kacamatan];
                        $("#subdistricts").html('<option></option>');
                        $.map(subdistricts, function(elementOrValue, indexOrKey) {
                            $("#subdistricts").append("<option value=" + elementOrValue[
                                    "subdis_id"] + ">" +
                                elementOrValue["subdis_name"] + "</option>");
                        });
                    } else {
                        $("#subdistricts").html('<option></option>');
                    }
                });
            }
        })
    </script>

@endpush
