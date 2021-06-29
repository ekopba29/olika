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
                            Customer : <h5>{{ $user->name }}</h5>
                        </div>
                        <div class=" col-sm-12">
                            Cat Name : <h5 id="cat-name-preview"></h5>
                        </div>
                        <div class=" col-sm-12">
                            Payment : <h5 id="payment-preview"></h5>
                        </div>
                        <div class=" col-sm-12">
                            Groomer : <h5 id="groomer-preview"></h5>
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

@push('third_party_scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        validationGroomerAndPayment();
        // membantu show tabel kucing ketika gagal validasi pilih kucing dari controller
        $("#groomer,#payment").change();

        function showModalSumary(element) {

            const payment = $("#payment option:selected").text();
            const catName = $(element).attr("cat-name");
            const catId = $(element).attr("cat-id");
            const groomer = $("#groomer option:selected").text();

            $("#cat-name-preview").text(catName);
            $("#payment-preview").text(payment);
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
            $("#groomer,#payment").on("change", function() {
                if ($("#groomer").val() != "" && $("#payment").val() != "") {
                    $("#table-cat").removeClass("d-none");
                    $("#storeGroomingBycat").removeClass("d-none");
                } else {
                    $("#table-cat").addClass("d-none");
                }
            })
        }
    </script>
@endpush
