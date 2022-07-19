@extends('admin')
@section('content')
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Order
                    <small>List</small>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr align="center">
                        <th>ID order</th>
                        <th>Id customer</th>
                        <th>Date order</th>
                        <th>Total</th>
                        <th>Payment method</th>
                        <th>Note</th>
                        <th>Status</th>
                        <th colspan='2'>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bill as $bi)
                    <tr>
                        <td>{{$bi->id}}</td>
                        <td>{{$bi->id_customer}}</td>
                        <td>{{$bi->date_order}}</td>
                        <td>{{$bi->total}}</td>
                        <td>{{$bi->payment}}</td>
                        <td>{{$bi->note}}</td>
                        <td>{{$bi->status}}</td>

                        @if($bi->id_status==1||$bi->id_status==2)
                        @if($bi->id_status==1)
                        <td><a href="/postConfirm/{{$bi->id}},2">Xác nhận</a></td>
                        @else
                        <td><a href="/postConfirm/{{$bi->id}},3">Đã giao hàng</a></td>
                        @endif
                        <td><a href="/postConfirm/{{$bi->id}}, 4">Hủy đơn</a></td>
                        @else
                        <td></td>
                        <td></td>
                        @endif
                    </tr>
                    @foreach($bill_detail as $detail)
                    @if($detail->id_bill==$bi->id)
                    <tr>
                        <td></td>
                        <td style='color:blue'>{{$detail->name}}</td>
                        <td style='color:blue'>{{$detail->quantity}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection