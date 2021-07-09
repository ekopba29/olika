<div class="modal fade show" id="summary-grooming" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-danger" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation Grooming Data</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <div class=" col-sm-12">
                            Cat Name : <h5 id="cat-name-preview" class="text-orange"></h5>
                        </div>

                        <div class=" col-sm-12">
                            Customer : <h5 class="text-danger">{{ $user->name }}</h5>
                        </div>

                        <div class="mt-3 col-sm-12">
                            <div class="row">
                                <div class="col-lg-6">
                                    Price : <h5 id="price-preview"></h5>
                                </div>
                                <div class="col-lg-6">
                                    Payment : <h5 id="payment-preview"></h5>
                                </div>
                            </div>
                        </div>

                        <div class=" col-sm-12">
                        </div>
                        <hr class="btn">
                        <div class="col-sm-12 text-right">
                            Groomer : <h5 id="groomer-preview" class="text-navy"></h5>
                        </div>
                        <div class="col-sm-12 text-right">
                            <h6 id="groomdate-preview" class="text-success"></h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" type="button" onclick="submitGrooming()">Confirm</button>
            </div>
        </div>

    </div>
</div>


<div class="modal fade show" id="alert-free" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-danger" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title text-danger">Alert!!!</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="row">
                        <h3 class="text-danger">
                            Grooming type <span class="grooming-name text-navy"></span> not for free!
                        </h3>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-dismiss="modal">Oke</button>
            </div>
        </div>

    </div>
</div>

@push('third_party_scripts')

    <script src="{{ asset('js/jquery.min.js') }}"></script>

    @if (!in_array($user->level, ['crew', 'owner']))
        <script>

        </script>
    @endif
    <script>
        validationGroomerAndPayment();
        // membantu show tabel kucing ketika gagal validasi pilih kucing dari controller
        $("#groomer,#payment,#groomingType").change();

        // cek boleh pakai free grooming untuk grooming tertentu saja
        function checkValidityFreeGrooming() {
            const allowToFree = $("#groomingType option:selected").attr('allow-free');
            const groomName = $("#groomingType option:selected").attr('grooming-name');
            const wantUseFree = $("#payment option:selected").val() == "free" ? true : false;
            console.log(allowToFree, wantUseFree)

            const levelUser = "{{ $user->level }}";
            if (levelUser == "owner" || levelUser == "crew") {
                return true
            }

            if (wantUseFree) {
                console.log(allowToFree, wantUseFree)
                if (allowToFree == "n") {
                    console.log(allowToFree, wantUseFree)
                    $('#alert-free').modal({
                        show: true
                    });
                    $('.grooming-name').text(groomName)
                    $("#table-cat").addClass("d-none");
                    $("#storeGroomingBycat").addClass("d-none");
                    return false;
                }
            }

            return true;
        }

        function showModalSumary(element) {

            const payment = $("#payment option:selected").text();
            const price = $("#groomingType option:selected").attr('price');
            const catName = $(element).attr("cat-name");
            const catId = $(element).attr("cat-id");
            const groomdate = $("#groom_date").val().split('-');
            const groomer = $("#groomer option:selected").text();

            $("#cat-name-preview").text(catName);
            $("#payment-preview").text(payment);
            $("#price-preview").text(price);
            $("#groomdate-preview").text(groomdate[2] + "/" + groomdate[1] + "/" + groomdate[0]);
            $("#groomer-preview").text(groomer);
            $("#cat-id").val(catId);


            $('#summary-grooming').modal({
                show: true
            });
        }

        function submitGrooming(e) {
            $("#form-grooming").submit();
        }

        function validationGroomerAndPayment() {
            $("#groomer,#payment,#groomingType").on("change", function() {
                if ($("#groomer").val() != "" && $("#payment").val() != "" && $("#groomingType").val() != "") {
                    if (checkValidityFreeGrooming()) {
                        $("#table-cat").removeClass("d-none");
                        $("#storeGroomingBycat").removeClass("d-none");
                    }
                } else {
                    $("#table-cat").addClass("d-none");
                }
            })
        }
    </script>
@endpush
