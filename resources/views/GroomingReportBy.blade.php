@extends('layouts.mainAdmin')

@section('content')
    @php
    if ($datas != null) {
        $payment_cash = $datas
            ->filter(function ($item) {
                return $item->payment == 'cash';
            })
            ->count();
        $payment_credit = $datas
            ->filter(function ($item) {
                return $item->payment == 'credit';
            })
            ->count();
        $payment_debit = $datas
            ->filter(function ($item) {
                return $item->payment == 'debit';
            })
            ->count();
        $payment_free = $datas
            ->filter(function ($item) {
                return $item->payment == 'free';
            })
            ->count();
    }
    @endphp

    @php
    if ($datas != null) {
        $idOwner = [];
        $idGroomer = [];
        foreach ($datas as $key => $item) {
            $dtOwner = ['id' => $item->owner->id, 'payment' => $item->payment, 'owner' => $item->owner->name, 'unique_number' => $item->owner->unique_number];
            array_push($idOwner, $dtOwner);
            $dtGroomer = ['id' => $item->groomer->id, 'groomer' => $item->groomer->name];
            array_push($idGroomer, $dtGroomer);
        }

        $idOwnerGrouping = [];
        foreach ($idOwner as $key => $item) {
            $groupOwner[$item['id']][] = $item;
        }
        
        $idGroomerGrouping = [];
        foreach ($idGroomer as $key => $item) {
            $groupGroomer[$item['id']][] = $item;
        }
    }
    @endphp
    {{ Request::get('range_report') }}
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-center">{{ ucfirst($user->name ?? "") }}</h1>
            <div class="card">
                <div class="card-body">
                    <h4>Search</h4>
                    <div class="mb-3"></div>
                    <form method="GET" action={{ route('grooming.reportBy',['user'=>Request::segment(2)]) }}>
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>From</label>
                                    <input type="text" class="form-control groomdate" placeholder="" name="from" required
                                        value={{ Request::get('from') ?? '' }}>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <!-- text input -->
                                <div class="form-group">
                                    <label>To</label>
                                    <input type="text" class="form-control groomdate" placeholder="" name="to" required
                                        value={{ Request::get('to') ?? '' }}>
                                </div>
                            </div>
                            <div class="col-lg-3 align-self-center mt-3">
                                <button class="btn btn-block bg-maroon text-white btn-md" type=" submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-danger">Report Crew</h4>
                    <div class="mb-3"></div>
                    <table class="table table1 table-responsive-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Groomer</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @unless(!$datas)
                        @php
                            $no = 1;
                            $totalAllGroomingan = 0; 
                        @endphp
                            @isset($groupGroomer)
                                @foreach ($groupGroomer as $number => $data)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $data[0]['groomer'] }}</td>
                                        <td>
                                            @php
                                                $totalGroomingan = 0;
                                                foreach ($data as $key => $value) {
                                                    $totalGroomingan = $totalGroomingan + 1;
                                                    $totalAllGroomingan = $totalAllGroomingan + 1;
                                                }
                                            @endphp
                                            {{ $totalGroomingan }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endunless
                        </tbody>
                        <tfoot class="bg-danger">
                            <td></td>
                            <td>Total</td>
                            <td>{{$totalAllGroomingan}}</td>
                        </tfoot>
                    @endunless

                    </table>
                    <p>
                        @unless($datas)
                        <h4 class="text-center">
                            No Data
                        </h4>
                        <div class="mb-3"></div>
                    @endunless
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-warning">Report Summary Customer</h4>
                    <div class="mb-3"></div>
                    <table class="table table2 table-responsive-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Owner</th>
                                <th>Free Grooming</th>
                                <th>Non Free Grooming</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        @unless(!$datas)
                            @isset($groupOwner)
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($groupOwner as $number => $data)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $data[0]['unique_number'] }}</td>
                                        <td>{{ ucfirst($data[0]['owner']) }}</td>
                                        <td>
                                            @php
                                                $freeGrooming = 0;
                                                foreach ($data as $key => $value) {
                                                    if ($value['payment'] == 'free') {
                                                        $freeGrooming = $freeGrooming + 1;
                                                    }
                                                }
                                            @endphp
                                            {{ $freeGrooming }}
                                        </td>
                                        <td>
                                            @php
                                                $payGrooming = 0;
                                                foreach ($data as $key => $value) {
                                                    if ($value['payment'] != 'free') {
                                                        $payGrooming = $payGrooming + 1;
                                                    }
                                                }
                                            @endphp
                                            {{ $payGrooming }}
                                        </td>
                                        <td>{{ $freeGrooming + $payGrooming }}</td>
                                    </tr>
                                @endforeach
                            @endunless
                        </tbody>
                        <tfoot class="bg-warning">
                            <td></td>
                            <td></td>
                            <td>Total</td>
                            <td>{{ $payment_free }}</td>
                            <td>{{ $payment_cash + $payment_credit + $payment_debit }}</td>
                            <td>{{ $payment_cash + $payment_credit + $payment_debit + $payment_free }}</td>
                        </tfoot>
                    @endunless

                    </table>
                    <p>
                        @unless($datas)
                        <h4 class="text-center">
                            No Data
                        </h4>
                        <div class="mb-3"></div>
                    @endunless
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-primary">Report Detail</h4>
                    <div class="mb-3"></div>
                    <table class="table table3 table-responsive-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID</th>
                                <th>Owner</th>
                                <th>Cat Name</th>
                                <th>Groomer</th>
                                <th>Groome Type</th>
                                <th>Payment</th>
                                <th>Grooming Date</th>
                                <th>Accumulated Free</th>
                                @if (Auth::user()->level == "owner")
                                    <th>Delete</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @unless(!$datas)
                                @foreach ($datas as $number => $data)
                                    <tr>
                                        <td>{{ ++$number }}</td>
                                        <td>{{ $data->owner->unique_number }}</td>
                                        <td>{{ ucfirst($data->owner->name) }}</td>
                                        <td>{{ ucfirst($data->cat->name) }}</td>
                                        {{-- <td>{{ $user->username }}</td> --}}
                                        <td>{{ ucfirst($data->groomer->name) }}</td>
                                        <td>{{ ucfirst($data->groomType->grooming_name) }} Rp. {{ number_format($data->payment_price, 0, ',', '.')}}</td>
                                        <td>{{ ucfirst($data->payment) }}</td>
                                        <td>{{ date('d M Y H:i', strtotime($data->grooming_at)) }}</td>
                                        <td>{{$data->accumulated_free_grooming}}</td>
                                        @if (Auth::user()->level == "owner")
                                            <td>
                                                @if ($data->accumulated_free_grooming != "y")
                                                <a route={{route('grooming.delete',['idgrooming'=>$data->id])}} owner="{{ucfirst($data->owner->name)}}" cat="{{$data->cat->name}}" accumulated="{{$data->accumulated_free_grooming}}" onclick="confrimDelete(this)">
                                                    <button class="btn btn-danger btn-xs">Delete</button>        
                                                </a>
                                                @endif
                                                <a href={{route('grooming.edit',['idgrooming'=>$data->id])}}>
                                                    <button class="btn btn-warning btn-xs">Edit</button>        
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @endunless
                        </tbody>
                    </table>
                    <p>
                        @unless($datas)
                        <h4 class="text-center">
                            No Data
                        </h4>
                        <div class="mb-3"></div>
                    @endunless
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show" id="confirmationDelete" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-danger" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation Delete</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="row">
                            Delete Grooming &nbsp; <span class="confrimName"> </span> &nbsp;  ?
                            <p>
                                <h1 class="text-danger warningFree">
                                </h1>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a href="" id="submitDelete">
                        <button class="btn btn-danger" type="button">Confirm</button>
                    </a>
                </div>
            </div>
    
        </div>
    </div>
@endsection
@push('third_party_scripts')
    <script src="{{ asset('js/jquery.min.js')}}"></script>
    <script>
        function confrimDelete(element) {
 
             const link = $(element).attr("route");
             const name = $(element).attr("cat");
             const owner = $(element).attr("owner");
             const accumulated = $(element).attr("accumulated");
 
             $(".confrimName").text("  " + name);
             if(accumulated == "y") {
                $(".warningFree").text("  " + "Free grooming " +owner+ " akan dikurangi 1");
             }
             $("#submitDelete").attr('href',link);
 
             $('#confirmationDelete').modal({
                 show: true
             });
         }
 
         function submitDelete(e) {
             $("#form-grooming").submit();
         }
 
     </script>

    <script>
        $(function() {
            //Date range picker with time picker
            $('.groomdate').datetimepicker({
                format: 'DD-MM-YYYY',
                icons: {
                    up: "fa fa-chevron-circle-up",
                    down: "fa fa-chevron-circle-down",
                    next: 'fa fa-chevron-circle-right',
                    previous: 'fa fa-chevron-circle-left'
                },
            });
        })
    </script>
    <script>
        $(document).ready(function() {
            $(".table1").DataTable({
                "responsive": true,
                "search": false,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [{
                    extend: 'excelHtml5',
                    title: @php
                        echo "'Crew Report " . date('d M Y', strtotime(Request::get('from'))) . ' To ' . date('d M Y', strtotime(Request::get('to'))) . "'";
                    @endphp,
                }],
                //  {
                //     extend: 'pdfHtml5',
                @php
                //     echo "'Grooming Report " . date('d M Y', strtotime(Request::get('from'))) . 'To' . date('d M Y', strtotime(Request::get('to'))) . "'";
                //
                @endphp
                // }],
                "paging": false,
                "info": false,

            }).buttons().container().appendTo('#DataTables_Table_0_wrapper .col-md-6:eq(0)');

            $(".table2").DataTable({
                "responsive": true,
                "search": false,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [{
                    extend: 'excelHtml5',
                    title: @php
                        echo "'Summary Report " . date('d M Y', strtotime(Request::get('from'))) . ' To ' . date('d M Y', strtotime(Request::get('to'))) . "'";
                    @endphp,
                }],
                // {
                //     extend: 'pdfHtml5',
                @php
                //     echo "'Summary Report " . date('d M Y', strtotime(Request::get('from'))) . 'To' . date('d M Y', strtotime(Request::get('to'))) . "'";
                //
                @endphp
                // }],
                "paging": false,
                "info": false,

            }).buttons().container().appendTo('#DataTables_Table_1_wrapper .col-md-6:eq(0)');

            $(".table3").DataTable({
                "responsive": true,
                "search": false,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [{
                    extend: 'excelHtml5',
                    title: @php
                        echo "'Grooming Report " . date('d M Y', strtotime(Request::get('from'))) . ' To ' . date('d M Y', strtotime(Request::get('to'))) . "'";
                    @endphp,
                }],
                //  {
                //     extend: 'pdfHtml5',
                @php
                //     echo "'Grooming Report " . date('d M Y', strtotime(Request::get('from'))) . 'To' . date('d M Y', strtotime(Request::get('to'))) . "'";
                //
                @endphp
                // }],
                "paging": false,
                "info": false,

            }).buttons().container().appendTo('#DataTables_Table_2_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
