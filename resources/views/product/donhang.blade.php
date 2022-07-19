@extends('matter')
@section('content')

<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                style="max-width:600px;margin-left:auto;margin-right:auto;text-align:center">
                <tr>
                    <td align="center" style="padding: 35px 35px 20px 35px; background-color: #ffffff;"
                        bgcolor="#ffffff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                            style="max-width:600px;">
                            <tr>
                                <td align="center"
                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 25px;">
                                    <img src="https://img.icons8.com/carbon-copy/100/000000/checked-checkbox.png"
                                        width="125" height="120" style="display: block; border: 0px; " /><br>
                                    <h2
                                        style="font-size: 30px; font-weight: 800; line-height: 36px; color: #333333; margin: 0;">
                                        Thank You For Your Order! </h2>
                                </td>
                            </tr>
                            <tr>
                                <td align="left"
                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px; padding-top: 10px;">
                                    <p style="font-size: 16px; font-weight: 400; line-height: 24px; color: #777777;">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Praesentium iste ipsa
                                        numquam odio dolores, nam. </p>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" style="padding-top: 20px;">
                                    <table cellspacing="0" cellpadding="0" border="0" width="100%">
                                        <tr>
                                            <td width="75%" align="left"
                                                style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                TOTAL </td>
                                            <td width="25%" align="left"
                                                style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 800; line-height: 24px; padding: 10px; border-top: 3px solid #eeeeee; border-bottom: 3px solid #eeeeee;">
                                                {{$bill->total}} </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="100%" valign="top" width="100%"
                        style="padding: 0 35px 35px 35px; background-color: #ffffff;" bgcolor="#ffffff">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
                            style="max-width:660px;">
                            <tr>
                                <td align="center" valign="top" style="font-size:0;">
                                    <div
                                        style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                            style="max-width:300px;">
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                    <p style="font-weight: 800;">Delivery Address</p>
                                                    <p>{{$customer->address}}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div
                                        style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                            style="max-width:300px;">
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                    <p style="font-weight: 800;">Order date</p>
                                                    <p>{{$bill->date_order}}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td align="center" valign="top" style="font-size:0;">
                                    <div
                                        style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                            style="max-width:300px;">
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                    <p style="font-weight: 800;">Receiver</p>
                                                    <p>{{$customer->name}}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div
                                        style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                            style="max-width:300px;">
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                    <p style="font-weight: 800;">Phone Receiver</p>
                                                    <p>{{$customer->phone_number}}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td align="center" valign="top" style="font-size:0;">
                                    <div
                                        style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                            style="max-width:300px;">
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                    <p style="font-weight: 800;">Mã đơn hàng</p>
                                                    <p>{{$payments->vnp_TxnRef}}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div
                                        style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                            style="max-width:300px;">
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                    <p style="font-weight: 800;">Thời gian thanh toán</p>
                                                    <p>{{$payments->vnp_PayDate}}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                                <td align="center" valign="top" style="font-size:0;">
                                    <div
                                        style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                            style="max-width:300px;">
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                    <p style="font-weight: 800;">Mã vnp TmnCode</p>
                                                    <p>{{$payments->vnp_TmnCode}}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div
                                        style="display:inline-block; max-width:50%; min-width:240px; vertical-align:top; width:100%;">
                                        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%"
                                            style="max-width:300px;">
                                            <tr>
                                                <td align="left" valign="top"
                                                    style="font-family: Open Sans, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: 400; line-height: 24px;">
                                                    <p style="font-weight: 800;">vnp SecureHashType</p>
                                                    <p>{{$payments->vnp_SecureHashType}}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endsection