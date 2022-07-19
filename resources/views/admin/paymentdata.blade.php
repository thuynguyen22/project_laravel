@extends('admin')
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Order Payment List
                    <small>Order Payment List</small>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
            <table class="table table-hover">
                <thead>
                    <tr align="center">
                        <th>ID</th>
                        <th>Id order</th>
                        <th>Vnp Amount</th>
                        <th>Vnp BankCode</th>
                        <th>Vnp BankTranNo</th>
                        <th>Vnp CardType</th>
                        <th>Vnp OrderInfo</th>
                        <th>Vnp PayDate</th>
                        <th>Vnp TmnCode</th>
                        <th>Vnp TransactionNo</th>
                        <th>Vnp TxnRef</th>
                        <th>Vnp SecureHashType</th>
                        <th>Vnp SecureHash</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bill as $bi)
                    <tr>
                        <td>{{$bi->id}}</td>
                        <td>{{$bi->id_order}}</td>
                        <td>{{$bi->vnp_Amount}}</td>
                        <td>{{$bi->vnp_BankCode}}</td>
                        <td>{{$bi->vnp_BankTranNo}}</td>
                        <td>{{$bi->vnp_CardType}}</td>
                        <td>{{$bi->vnp_OrderInfo}}</td>
                        <td>{{$bi->vnp_PayDate}}</td>
                        <td>{{$bi->vnp_TmnCode}}</td>
                        <td>{{$bi->vnp_TransactionNo}}</td>
                        <td>{{$bi->vnp_TxnRef}}</td>
                        <td>{{$bi->vnp_SecureHashType}}</td>
                        <td>{{$bi->vnp_SecureHash}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection