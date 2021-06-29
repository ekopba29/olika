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
        foreach ($datas as $key => $item) {
            $dt = ['id' => $item->owner->id, 'payment' => $item->payment, 'owner' => $item->owner->name];
            array_push($idOwner, $dt);
        }

        $idOwnerGrouping = [];
        foreach ($idOwner as $key => $item) {
            $groupOwner[$item['id']][] = $item;
        }
    }
    @endphp
    {{ Request::get('range_report') }}
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4>Search</h4>
                    <div class="mb-3"></div>
                    <form method="GET" action={{ route('grooming.report') }}>
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
                    <h4>Report Summary</h4>
                    <div class="mb-3"></div>
                    <table class="table table1 table-responsive-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Owner</th>
                                <th>Free Grooming</th>
                                <th>Non Free Grooming</th>
                            </tr>
                        </thead>
                        <tbody>
                            @isset($groupOwner)
                            @php
                                $no = 1;
                            @endphp
                                @foreach ($groupOwner as $number => $data)
                                    <tr>
                                        <td>{{ $no++ }}</td>
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4>Report Detail</h4>
                    <div class="mb-3"></div>
                    <table class="table table2 table-responsive-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Owner</th>
                                <th>Cat Name</th>
                                <th>Groomer</th>
                                <th>Payment</th>
                                <th>Grooming Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @unless(!$datas)
                                @foreach ($datas as $number => $data)
                                    <tr>
                                        <td>{{ ++$number }}</td>
                                        <td>{{ ucfirst($data->owner->name) }}</td>
                                        <td>{{ ucfirst($data->cat->name) }}</td>
                                        {{-- <td>{{ $user->username }}</td> --}}
                                        <td>{{ ucfirst($data->groomer->name) }}</td>
                                        <td>{{ ucfirst($data->payment) }}</td>
                                        <td>{{ date('d M Y H:i', strtotime($data->created_at)) }}</td>
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

@endsection
@push('third_party_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

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
                    echo "'Summary Report " . date('d M Y', strtotime(Request::get('from'))) . ' To ' . date('d M Y', strtotime(Request::get('to'))) . "'";
                    @endphp,
                }], 
                // {
                //     extend: 'pdfHtml5',
                //     title: @php
                //     echo "'Summary Report " . date('d M Y', strtotime(Request::get('from'))) . 'To' . date('d M Y', strtotime(Request::get('to'))) . "'";
                //     @endphp
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
                    echo "'Grooming Report " . date('d M Y', strtotime(Request::get('from'))) . ' To ' . date('d M Y', strtotime(Request::get('to'))) . "'";
                    @endphp,
                }],
                //  {
                //     extend: 'pdfHtml5',
                //     title: @php
                //     echo "'Grooming Report " . date('d M Y', strtotime(Request::get('from'))) . 'To' . date('d M Y', strtotime(Request::get('to'))) . "'";
                //     @endphp
                // }],
                "paging": false,
                "info": false,

            }).buttons().container().appendTo('#DataTables_Table_1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
