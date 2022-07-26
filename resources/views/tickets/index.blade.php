@extends('layouts.app')
@section('style')
    <link rel="stylesheet" href="{{asset('css/cute-alert.css')}}"/>

@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><span style="font-size: 18px; margin: auto; line-height: 2">all Tickets</span> <span style="float: right">
                        <a class="btn btn-primary" href="{{route('tickets.create')}}">create ticket</a>
                    </span>
                </div>

                <div class="card-body">
                    <table class="table table-bordered data-table">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>email</th>
                            <th>subject</th>
                            <th>created_at</th>
                            <th>action</th>
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
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('js/cute-alert.js')}}"></script>

    <script>



        $(function () {

            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('tickets.datatable') }}",
                columns: [
                    {"data": 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'email', name: 'email'},
                    {data: 'subject', name: 'subject'},
                    {data: 'created_at', name: 'created_at', orderable: false, searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

        });

        function deleteTicket(id) {
            cuteAlert({
                type: "question",
                title: "confirm delete",
                message: "delete ticket",
                confirmText: "Okay",
                cancelText: "cancel"
            }).then((e) => {
                if (e == "confirm") {
                    $.ajax({
                        type: "post",
                        url: "{{url('tickets/')}}/" + id,
                        data: {
                            "_method": "delete",
                            "_token": "{{csrf_token()}}"
                        },
                        success: function (response) {
                            $('.data-table').DataTable().ajax.reload();
                            new Notify({
                                status: 'success',
                                title: 'Success',
                                text: 'done deleted successfully',
                                effect: 'fade',
                                speed: 300,
                                customClass: '',
                                customIcon: '',
                                showIcon: true,
                                showCloseButton: true,
                                autoclose: true,
                                autotimeout: 3000,
                                gap: 20,
                                distance: 20,
                                type: 3,
                                position: 'right top'
                            })

                        }
                    });
                }
            })
        }



    </script>
@endsection
