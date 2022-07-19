<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Slide;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\BillDetail;
use App\Models\Bill;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Cart;
use App\Jobs\SendEmail;
use App\Models\User;
use App\Rules\Captcha;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash as FacadesHash;
use Illuminate\Support\Facades\Cookie;

class PageController extends Controller
{
    //==========================================TẤT CẢ CÁC TRANG=======================================
    // Trang chủ
    public function getAdmin()
    {
        $cookie = Cookie::get('admin');
        if ($cookie == null) {
            return redirect()->route('LoginAdmin');
        }
        $user = User::All();
        $bill = $this->getBill(1);
        $bill_detail = $this->getBillDetail();
        return view('admin.index', compact('user'));
    }
    public function getIndexAdmin(Request $request)
    {
        $cookie = Cookie::get('admin');
        if ($cookie == null) {
            return redirect()->route('LoginAdmin');
        }
        $bill = $this->getBill(1);
        $bill_detail = $this->getBillDetail();
        return view('admin.master', compact('bill', 'bill_detail'));
    }

    public function getIndex()
    {
        $slide = Slide::All();
        $new_product = Product::where('new', 1)->paginate(8);

        $sanpham_khuyenmai = Product::where('promotion_price', '<>', 0)->paginate(8);

        return view('product.trangchu', compact('slide', 'new_product', 'sanpham_khuyenmai'));
    }
    // Loại sản phẩm
    public function getLoaisp($type)
    {
        // hiện thị sp  theo loại
        $sp_theoloai = Product::where('id_type', $type)->paginate(6);
        //  hiện thị sp khác loại
        $loai_sp = ProductType::where('id', $type)->first();
        $loai = ProductType::all();
        $sp_khac = Product::where('id_type', '<>', $type)->paginate(8);
        return view('product.loai_sanpham', compact('sp_theoloai', 'sp_khac', 'loai', 'loai_sp'));
    }
    // Trang chi tieets sanr phaamr
    public function getChitietsp(Request $req)
    {
        $sanpham = Product::where('id', $req->id)->first();
        $sp_tuongtu = Product::where('id_type', $sanpham->id_type)->paginate(6);
        $sp_banchay = Product::where('promotion_price', '=', 0)->paginate(3);
        $sp_new = Product::select('id', 'name', 'id_type', 'description', 'unit_price', 'promotion_price', 'image', 'unit', 'new', 'created_at', 'updated_at')->where('new', '>', 0)->orderBy('updated_at', 'ASC')->paginate(3);
        return view('product.chitiet_sanpham', compact('sanpham', 'sp_tuongtu', 'sp_banchay', 'sp_new'));
    }
    // trang about
    public function getAbout()
    {
        return view('product.about');
    }
    //Trang liên hệ
    public function getContact()
    {
        return view('product.contact');
    }

    //===============================================CHECKOUT================================================
    // Phần xem giỏ hàng
    public function getcheckout()
    {
        return view('product.checkout');
    }
    public function postCheckout(Request $req)
    {
        $cart = Session::get('cart');
        $customer = new Customer;
        $customer->name = $req->full_name;
        $customer->gender = $req->gender;
        $customer->email = $req->email;
        $customer->address = $req->address;
        $customer->phone_number = $req->phone;
        $customer->note = $req->notes;
        $bill = new Bill;
        $bill->date_order = date('Y-m-d');
        $bill->total = $cart->totalPrice;
        $bill->payment = $req->payment_method;
        $bill->note = $req->notes;

        if ($req->payment_method === 'vnpay') {
            session(['bill' => $bill]);
            session(['customer' => $customer]);
            session(['cost_id' => $req->id]);
            session(['url_prev' => url()->previous()]);
            $vnp_TmnCode = "RR5KEBHN"; //Mã website tại VNPAY 
            $vnp_HashSecret = "PTRLXAYUXBQRRKPTVOBYBQZJSOFWPYIE"; //Chuỗi bí mật
            $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = route('returnVNpay');
            $vnp_TxnRef = date("YmdHis"); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = "Thanh toán hóa đơn phí dich vụ";
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $cart->totalPrice * 100;
            $vnp_Locale = 'vn';
            $vnp_IpAddr = request()->ip();

            $inputData = array(
                "vnp_Version" => "2.0.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . $key . "=" . $value;
                } else {
                    $hashdata .= $key . "=" . $value;
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                // $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
                $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
                $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
            }
            return redirect($vnp_Url);
        } else {
            $customer->save();
            $bill->id_customer = $customer->id;
            $bill->save();

            foreach ($cart->items as $key => $value) {
                $bill_detail = new BillDetail;
                $bill_detail->id_bill = $bill->id;
                $bill_detail->id_product = $key;
                $bill_detail->quantity = $value['qty'];
                $bill_detail->unit_price = $value['price'] / $value['qty'];
                $bill_detail->save();
            }

            $message = [
                'type' => 'Email thông báo đặt hàng thành công',
                'thanks' => 'Cảm ơn ' . $req->full_name . ' Đã đặt hàng',

                'cart' => $cart,
                'content' => 'Đơn hàng của bạn sẽ tới sớm nhất có thể!',
            ];
            SendEmail::dispatch($message, $req->email)->delay(now()->addMinute(1));
            Session::forget('cart');
            return redirect('/homepage')->with('thongbao', 'Đặt hàng thành công');
        }
    }

    public function vnpay($id, $amount, $bill, $customer)
    {
        session(['bill' => $bill]);
        session(['customer' => $customer]);
        session(['cost_id' => $id]);
        session(['url_prev' => url()->previous()]);
        $vnp_TmnCode = "RR5KEBHN"; //Mã website tại VNPAY 
        $vnp_HashSecret = "PTRLXAYUXBQRRKPTVOBYBQZJSOFWPYIE"; //Chuỗi bí mật
        $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "returnVNpay";
        $vnp_TxnRef = date("YmdHis"); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = "Thanh toán hóa đơn phí dich vụ";
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $amount * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.0.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            // $vnpSecureHash = md5($vnp_HashSecret . $hashdata);
            $vnpSecureHash = hash('sha256', $vnp_HashSecret . $hashdata);
            $vnp_Url .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
        }
        return redirect($vnp_Url);
    }

    public function returnVNpay(Request $request)
    {
        $bill = null;
        $customer = null;
        $cart = Session::get('cart');
        if ($request->vnp_ResponseCode == "00") {
            $new = new Payment();
            $customer = Session::get('customer');
            $customer->save();
            $bill = Session::get('bill');
            $bill->id_customer = $customer->id;
            $bill->save();
            $new->id_order = $bill->id;
            $new->vnp_Amount = $request->vnp_Amount;
            $new->vnp_BankCode = $request->vnp_BankCode;
            $new->vnp_BankTranNo = $request->vnp_BankTranNo;
            $new->vnp_CardType = $request->vnp_CardType;
            $new->vnp_OrderInfo = $request->vnp_OrderInfo;
            $new->vnp_PayDate = $request->vnp_PayDate;
            $new->vnp_TmnCode = $request->vnp_TmnCode;
            $new->vnp_TransactionNo = $request->vnp_TransactionNo;
            $new->vnp_TxnRef = $request->vnp_TxnRef;
            $new->vnp_SecureHashType = $request->vnp_SecureHashType;
            $new->vnp_SecureHash = $request->vnp_SecureHash;
            $new->save();
            foreach ($cart->items as $key => $value) {
                $bill_detail = new BillDetail;
                $bill_detail->id_bill = $bill->id;
                $bill_detail->id_product = $key;
                $bill_detail->quantity = $value['qty'];
                $bill_detail->unit_price = $value['price'] / $value['qty'];
                $bill_detail->save();
            }

            $message = [
                'type' => 'Email thông báo đặt hàng thành công',
                'thanks' => 'Cảm ơn ' . $customer->name . ' Đã đặt hàng',

                'cart' => $cart,
                'content' => 'Đơn hàng của bạn sẽ tới sớm nhất có thể!',
            ];
            SendEmail::dispatch($message, $customer->email)->delay(now()->addMinute(1));
            Session::forget('cart');
            Session::forget('bill');
            Session::forget('customer');
            $payments = $request;
            return view('product/donhang', compact("bill", "customer", 'payments'));
        }
        session()->forget('url_prev');
        return redirect()->with('errors', 'Lỗi trong quá trình thanh toán phí dịch vụ');
    }
    // thêm vào Giỏ hàng 
    public function getAddToCart(Request $req, $id)
    {
        $product = Product::find($id);
        $oldCart = Session('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->add($product, $id);
        $req->session()->put('cart', $cart);
        return redirect()->back();
    }
    // Xóa thông tin giỏ hàng 
    public function getDelItemCart($id)
    {
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if (count($cart->items) > 0) {
            Session::put('cart', $cart);
        } else {
            Session::forget('cart');
        }
        return redirect()->back();
    }

    //================================================== CRUD của admin===============================================
    // Trang chủ
    public function loginAdmin()
    {
        return view('admin.login');
    }
    public function logoutAdmin()
    {
        $cookie = Cookie::forget('admin');
        return redirect()->route('admin');
    }
    public function postLoginAdmin(Request $request)
    {
        if ($request->email == 'admin@gmail.com' && $request->password == 'admin12345') {
            Cookie::queue('admin', 'admin@gmail.com, admin12345', 60);
            return redirect()->route('admin');
        }
        return redirect()->back()->with('message', 'Thông tin đăng nhập không đúng!');
    }
    public function getBillDetail()
    {
        $bill_detail = BillDetail::join('products', 'bill_details.id_product', '=', 'products.id')
            ->select('bill_details.*', 'products.name')
            ->orderByRaw('bill_details.id ASC')
            ->get();
        return $bill_detail;
    }
    public function getBill($id_status)
    {
        $bill = Bill::where('id_status', $id_status)->join('order_status', 'bills.id_status', '=', 'order_status.id')
            ->select('bills.*', 'order_status.status')
            ->orderByRaw('bills.id ASC')
            ->get();
        return $bill;
    }
    public function check()
    {
        $cookie = Cookie::get('admin');
        if ($cookie == null) {
            return redirect()->route('LoginAdmin');
        }
    }

    public function getOrderPayment(Request $request)
    {
        $cookie = Cookie::get('admin');
        if ($cookie == null) {
            return redirect()->route('LoginAdmin');
        }
        $bill = Payment::all();
        return view('admin.paymentdata', compact('bill'));
    }

    public function getDelivery(Request $request)
    {
        $cookie = Cookie::get('admin');
        if ($cookie == null) {
            return redirect()->route('LoginAdmin');
        }
        $bill = $this->getBill(2);
        $bill_detail = $this->getBillDetail();
        return view('admin.master', compact('bill', 'bill_detail'));
    }

    public function getDeliveried(Request $request)
    {
        $cookie = Cookie::get('admin');
        if ($cookie == null) {
            return redirect()->route('LoginAdmin');
        }
        $bill = $this->getBill(3);
        $bill_detail = $this->getBillDetail();
        return view('admin.master', compact('bill', 'bill_detail'));
    }
    public function getDelete(Request $request)
    {
        $cookie = Cookie::get('admin');
        if ($cookie == null) {
            return redirect()->route('LoginAdmin');
        }
        $bill = $this->getBill(4);
        $bill_detail = $this->getBillDetail();
        return view('admin.master', compact('bill', 'bill_detail'));
    }
    // Trang form thêm sản phẩm
    public function getAdminAdd()
    {
        return view('admin.master');
    }
    public function postConfirm(Request $request)
    {
        $find = Bill::find($request->id);
        $find->id_status = $request->id_status;
        $find->save();
        return redirect()->back();
    }
    // thêm sản phẩm
    public function postAdminAdd(Request $request)
    {
        $product = new Product();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName('image');
            $file->move('source/image/product', $fileName);
        }
        $file_name = null;
        if ($request->file('image') != null) {
            $file_name = $request->file('image')->getClientOriginalName();
        }
        $product->name = $request->name;
        $product->image = $file_name;
        $product->description = $request->description;
        $product->unit_price = $request->unit_price;
        $product->promotion_price = $request->promotion_price;
        $product->unit = $request->unit;
        $product->new = $request->new;
        $product->id_type = $request->type;
        $product->save();
        //return $this->getIndexAdmin();
        return redirect()->route('admin');
    }

    //---------------------------------------------------
    // public function postAdminAdd(Request $request){
    //     $product = new Product();
    //     $name='';
    //     if($request->hasfile('image')){
    //         $this->validate($request,[
    //             'image'=>'mimes:jpg,png,gif,jpeg',
    //             'description'=>'required',
    //             'unit_price'=>'required',
    //             'promotion_price'=>'required',
    //             'unit'=>'required',
    //             'new'=>'required',
    //             'id_type'=>'required'
    //         ],[
    //             'image.mimes'=>'Chỉ chấp nhận file hình ảnh',
    //             'description.required'=>'Bạn chưa nhập mô tả',
    //             'unit_price.required'=>'Bạn chưa nhập price',
    //             'promotion_price.required'=>'Bạn chưa bạn chưa promotion_price',
    //             'unit.required'=>'Bạn chưa nhập unit',
    //             'new.required'=>'Bạn chưa bạn chưa new',
    //             'id_type.required'=>'Bạn chưa nhập id_type'

    //         ]);
    //         $file = $request->file('image');
    //         $name=$file->getClientOriginalName();
    //         //$destinationPath=public_path('images'); //project\public\car, public_path(): trả về đường dẫn tới thư mục public
    //         $file->move('source/image/product', $name); //lưu hình ảnh vào thư mục public/car
    //     }
    //     $product->name = $request->name;
    //     // $product->image = $file_name;
    //     $product->description=$request->description;
    //     $product->unit_price =$request->unit_price;
    //     $product->promotion_price = $request->promotion_price;
    //     $product->unit = $request->unit;
    //     $product->new = $request->new;
    //     $product->id_type = $request->type;
    //     $product->save();
    //     //return $this->getIndexAdmin();
    //     return redirect()->route('admin');
    // }

    //------------------------------------------------
    // Sửa sản phẩm
    public function getAdminEdit($id)
    {
        $product = Product::find($id);
        return view('admin.editform', compact('product'));
    }
    // Sửa sản phẩm
    public function postAdminEdit(Request $request)
    {
        $id = $request->id;
        $product = Product::find($id);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName('image');
            $file->move('source/image/product', $fileName);
        }
        if ($request->file('image') != null) {
            $product->image = $fileName;
        }
        $product->name = $request->name;
        $product->id_type = $request->type;

        $product->description = $request->description;
        $product->unit_price = $request->unit_price;
        $product->promotion_price = $request->promotion_price;

        $product->unit = $request->unit;
        $product->new = $request->new;

        $product->save();
        return redirect()->route('admin');
        //return $this->getIndexAdmin();

    }
    // Xóa sản phẩm
    public function postAdminDelete($id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('admin');
    }


    //===========================================ĐĂNG KÍ, ĐĂNG NHẬP====================================================

    public function getSignUp()
    {
        return view('product.dangki');
    }

    public function getSignIn()
    {
        return view('product.dangnhap');
    }
    // Đăng kí 

    public function postSignUp(Request $req)
    {
        $this->validate($req, [
            'email' => 'required|email|unique:users,email',
            // 'password'=>'required|min:6|max:20',
            'full_name' => 'required',
            // 're_password'=>'required|same:password',
            'g-recaptcha-response' => new Captcha(),
        ], [

            'email.required' => 'Bạn chưa nhập email',
            'email.email' => 'Không đúng định dạng email',
            'email.unique' => 'Email có người sử dụng',
            // 'password.required'=>'Bạn chưa bạn chưa password',
            // 're_password.same'=>'Mật khau không giống nhau',
            // 'password.min'=>'Mật khẩu ít nhất 6 kí tự',
            // 'password.max'=>'Mật khẩu lớn nhất 20 kí tự'

        ]);
        $user = new User();
        //dd($req);
        $user->full_name = $req->full_name;
        $user->email = $req->email;
        $user->password = FacadesHash::make($req->password);
        $user->phone = $req->phone;
        $user->address = $req->address;
        $user->save();
        return redirect('/signin')->with('thanhcong', 'đăng kí thanh cong');
    }

    //Đăng nhập
    public function postSignIn(Request $req)
    {
        $this->validate($req, [
            'email' => 'required|email',
            'password' => 'required|min:6|max:20'

        ], [

            'email.required' => 'Bạn chưa nhập email',
            'email.unique' => 'Email có người sử dụng',
            'password.required' => 'Bạn chưa bạn chưa password',
            'password.min' => 'Mật khẩu ít nhất 6 kí tự',
            'password.max' => 'Mật khẩu khoong qua 20 kí tự'

        ]);
        $credentials = array('email' => $req->email, 'password' => $req->password);
        if (Auth::attempt($credentials)) {
            return redirect('/homepage')->with(['flag' => 'alert', 'message' => 'Đăng nhập thành công']);
        } else {
            return redirect()->back()->with(['flag' => 'danger', 'message' => 'Đăng nhập khong thành công']);
        }
    }

    // Đăng xuất
    public function postlogout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
    public function getsearch(Request $request)
    {
        // Tìm kiếm sản phẩm theo tên gần giống nhau
        $product = Product::where('name', 'like', '%' . $request->key . '%')
            // Hoặc tìm kiếm sp  theo giá
            ->orwhere('unit_price', $request->key)
            ->get();
        return view('product.search', compact('product'));
    }
}