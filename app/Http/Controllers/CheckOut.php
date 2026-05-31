<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Ticket;

class CheckOut extends Controller
{
    // Hàm này được gọi từ BookingController
    public function vnpay_payment(Request $request, $bill_id, $total_price)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return'); 
        $vnp_TmnCode = "N0GMJT3D"; 
        $vnp_HashSecret = "SHG6JDMIDBWWYDOTA7ZA9NTT6GET9416"; 

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $total_price * 100, // VNPay quy định nhân 100
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $request->ip(),
            "vnp_Locale" => 'vn',
            "vnp_OrderInfo" => 'Thanh toan hoa don phim. Ma HD: ' . $bill_id,
            "vnp_OrderType" => 'billpayment',
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $bill_id, // Truyền đúng mã HD vào đây
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect()->away($vnp_Url);
    }

    // Trạm đón khách trả về
    public function return_vnpay(Request $request)
    {
        $vnp_HashSecret = "SHG6JDMIDBWWYDOTA7ZA9NTT6GET9416"; 
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $bill_id = $request->vnp_TxnRef; 

        // Kiểm tra chữ ký có bị hack đổi giá trị trên URL không
        if ($secureHash == $vnp_SecureHash) {
            
            if ($request->vnp_ResponseCode == '00') {
                // THÀNH CÔNG: Cập nhật status vé từ 0 -> 1 (Từ Chờ xử lý -> Đã đặt)
                Ticket::where('bill_id', $bill_id)->update(['status' => 1]);
                
                return redirect()->route('client.book.success', $bill_id)->with('success', 'Thanh toán VNPay thành công!');
            } else {
                // THẤT BẠI: Xóa vé và hóa đơn rác đi để nhường ghế cho người khác
                Ticket::where('bill_id', $bill_id)->delete();
                Bill::where('id', $bill_id)->delete();

                return redirect()->route('home')->with('error', 'Giao dịch VNPay thất bại hoặc đã bị hủy!');
            }
        } else {
            return redirect()->route('home')->with('error', 'Chữ ký VNPay không hợp lệ (Lỗi bảo mật)!');
        }
    }
}