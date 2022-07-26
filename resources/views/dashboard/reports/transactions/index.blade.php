@extends('dashboard.layouts.app')

@section('style')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">

            <div class="col-12 ">
                <p>update graph every 24h</p>
            </div>
            <div class="col-12 text-center">
                <div style=" height: 200px !important; width: 200px !important;">
                    <canvas id="myChart"></canvas>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">reports</div>

                    <div class="card-body">
                        <table class="table table-bordered data-table">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>from</th>
                                <th>to</th>
                                <th>amount</th>
                                <th>created_at</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>

        $(function () {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.transactions.list') }}",
                columns: [
                    {"data": 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'from', name: 'fromUser.name'},
                    {data: 'to', name: 'toUser.name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'created_at', name: 'created_at', orderable: false, searchable: false},
                ]
            });

        });


    </script>


    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        // TODO refactor move it to (VIEW MODEL)
        // add one for show graph
        var success = 1;
        var fail = 1;
        if ({{$transactionAnalytics->count()}}) {
            var success = '{{ optional($transactionAnalytics->where('status', 1)->first())->total ?? 1 }}'
            var fail = '{{ optional($transactionAnalytics->where('status', 0)->first())->total ?? 1 }}'
        }


        const data = [success, fail];


        var chart = new Chart(ctx, {
            options: {
                plugins: {
                    // Change options for ALL labels of THIS CHART
                    datalabels: {
                        color: '#36A2EB'
                    }
                }
            },
            type: 'pie',
            data: {
                labels: [
                    'success',
                    'fail'
                ],
                datasets: [{
                    label: 'Report Transactions',
                    data: data,
                    backgroundColor: [
                        '#5b9bd5',
                        '#ed7d31',
                    ],
                    hoverOffset: 4
                }]
            }
        });
    </script>
@endsection
