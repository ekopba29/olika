@extends('layouts.mainAdmin')
@section('content')
    @php $action = Request::segment(2) == 'create' ? route('customer.store') : route('customer.update', ['customer' => $user->id]) @endphp
    @php $name = $user->name ?? old('name') @endphp
    @php $email = $user->email ?? old('email') @endphp
    @php $address = $user->address ?? old('address') @endphp
    @php $phone = $user->phone ?? old('phone') @endphp
    @php $unique_number = $user->unique_number ?? old('unique_number') @endphp
    @php $level = $user->level ?? old('level') @endphp
    @php $title =  Request::segment(2) == 'create'  ? "Add" : "Edit" @endphp
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <h4> {{ $title }} Customer</h4>
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
                                    <label>Unique Number (ID)</label>
                                    <input type="text" class="form-control" placeholder="" name="unique_number" id="unique_number"
                                        value="{{ $unique_number }}" readonly>
                                </div>
                            </div>
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
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" placeholder="" name="address"
                                        value="{{ $address }}">
                                </div>
                            </div>
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
                                    <select class="custom-select" id="subdistricts" name="subdis_id">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <label>Level</label>
                                <!-- text input -->
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input clvl" type="radio" value="member" name="level" id="level" @if ($level == 'member') checked="true" @endif>
                                        <label class="form-check-label">Member</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input clvl" type="radio" value="notmember" name="level" id="level" @if ($level == 'notmember') checked="true" @endif>
                                        <label class="form-check-label">Not Member</label>
                                    </div>
                                </div>
                                <button class="btn btn-block bg-maroon text-white btn-xs" type="submit" id="submit">Save</button>
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
            let all_resp_addr;

            $('.clvl').on('change',function(){
                const lvl = $(this).val();
                console.log(lvl)
                if (lvl == "member"){
                    $.ajax({
                        type: "get",
                        url: @php echo "'" . route('customer.generateUniqueId') . "'" @endphp,
                        dataType: "text",
                        success: function (response) {
                            $("#unique_number").val(response);
                            $('#submit').attr("disabled", false);
                        },
                        beforeSend: function (){
                            $('#submit').attr("disabled", true);
                        }
                    });
                }
                else {
                    $("#unique_number").val("");
                }
            });

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
