@extends('dashboard.layouts.app')

@section('style')
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">all transactions</div>

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
@endsection
