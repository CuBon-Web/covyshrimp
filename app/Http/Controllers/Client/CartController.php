<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\product\Product;
use Cart,Auth,Redirect,DB;
use App\models\Bill\BillDetail;
use App\models\Bill\Bill;
use Mail;
use App\Mail\DemoMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\models\website\Setting;

class CartController extends Controller
{
    private function getNotificationEmails()
    {
        $setting = Setting::first(['email']);
        if (!$setting || empty($setting->email)) {
            return [];
        }

        return collect(explode(',', (string)$setting->email))
            ->map(function ($email) {
                return trim($email);
            })
            ->filter(function ($email) {
                return $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL);
            })
            ->values()
            ->all();
    }

    private function sendBillNotificationMail(Bill $bill, array $cart)
    {
        $emails = $this->getNotificationEmails();
        if (empty($emails)) {
            Log::warning('Order notification skipped: settings.email is empty or invalid', [
                'code_bill' => $bill->code_bill,
            ]);
            return;
        }

        $data = ['cus' => $bill, 'bill' => $cart];
        Mail::to($emails)->send(new DemoMail($data));
    }

    private function getCartLinePrice(array $item)
    {
        if ((int)($item['status_variant'] ?? 0) === 1) {
            return (int)($item['price'] ?? 0);
        }
        if ((int)($item['discount'] ?? 0) > 0) {
            return (int)($item['discount'] ?? 0);
        }
        return (int)($item['price'] ?? 0);
    }

    private function reduceProductQtyByBill(Bill $bill)
    {
        $billDetails = BillDetail::where('code_bill', $bill->code_bill)->get();
        foreach ($billDetails as $detail) {
            $product = Product::find($detail->code_product);
            if (!$product) continue;
            if ($product->qty > $detail->qty) {
                $product->qty = $product->qty - $detail->qty;
            } else {
                $product->qty = 0;
            }
            $product->save();
        }
    }

    private function buildPayosSignature(array $data, $checksumKey)
    {
        ksort($data);
        $pairs = [];
        foreach ($data as $key => $value) {
            $pairs[] = $key . '=' . $value;
        }
        return hash_hmac('sha256', implode('&', $pairs), $checksumKey);
    }

    private function createPayosPaymentLink(Bill $bill, array $cart, Request $request)
    {
        $clientId = env('PAYOS_CLIENT_ID');
        $apiKey = env('PAYOS_API_KEY');
        $checksumKey = env('PAYOS_CHECKSUM_KEY');
        if (!$clientId || !$apiKey || !$checksumKey) {
            throw new \Exception('Thiếu cấu hình PAYOS_CLIENT_ID/PAYOS_API_KEY/PAYOS_CHECKSUM_KEY');
        }

        $orderCode = (int)$bill->code_bill;
        $description = 'DH' . $orderCode;
        $returnUrl = route('payos.return');
        $cancelUrl = route('payos.cancel');

        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'name' => mb_substr($item['name'], 0, 25),
                'quantity' => (int)($item['quantity'] ?? 1),
                'price' => $this->getCartLinePrice($item),
            ];
        }

        $signatureData = [
            'amount' => (int)$bill->total_money,
            'cancelUrl' => $cancelUrl,
            'description' => $description,
            'orderCode' => $orderCode,
            'returnUrl' => $returnUrl,
        ];

        $payload = [
            'orderCode' => $orderCode,
            'amount' => (int)$bill->total_money,
            'description' => $description,
            'items' => $items,
            'returnUrl' => $returnUrl,
            'cancelUrl' => $cancelUrl,
            'buyerName' => $bill->cus_name,
            'buyerEmail' => $bill->cus_email,
            'buyerPhone' => $bill->cus_phone,
            'buyerAddress' => $bill->cus_address,
            'signature' => $this->buildPayosSignature($signatureData, $checksumKey),
        ];

        $response = Http::withHeaders([
            'x-client-id' => $clientId,
            'x-api-key' => $apiKey,
        ])->post('https://api-merchant.payos.vn/v2/payment-requests', $payload);

        if (!$response->ok()) {
            Log::error('PayOS create payment link failed', ['response' => $response->body()]);
            throw new \Exception('Không tạo được link thanh toán PayOS');
        }

        $json = $response->json();
        if (!isset($json['data']['checkoutUrl'])) {
            Log::error('PayOS checkoutUrl missing', ['response' => $json]);
            throw new \Exception('PayOS không trả về checkoutUrl');
        }
        return $json['data']['checkoutUrl'];
    }

    private function markBillPaidByOrderCode($orderCode)
    {
        $bill = Bill::where('code_bill', $orderCode)->first();
        if (!$bill) return null;
        if ((int)$bill->statu !== 1) {
            $bill->statu = 1;
            $bill->save();
            $this->reduceProductQtyByBill($bill);
            $billDetails = BillDetail::where('code_bill', $bill->code_bill)->get()->toArray();
            $this->sendBillNotificationMail($bill, $billDetails);
        }
        return $bill;
    }

    public function checkout(){
            $data['cart'] = session()->get('cart', []);
            $data['profile'] = Auth::guard('customer')->user();
            return view('cart.checkout',$data);
        
    }
    public function postBill(Request $request){
        $profile = Auth::guard('customer')->user();
        $cart = session()->get('cart', []);
        if (count($cart) === 0) {
            return redirect()->route('listCart')->with('error', 'Giỏ hàng đang trống');
        }

        $code_bill = (int)(date('ymdHis') . rand(10, 99));
        $paymentMethod = $request->payment_method === 'online' ? 'online' : 'cod';
        DB::beginTransaction();
			try {
				$query = new Bill();
				$query->code_bill = $code_bill;
				$query->code_customer = $profile ? $profile->id : 0;
				$query->total_money = $request->total_money;
				$query->statu = 0;
				$query->note = $request->note;
                $query->payment_method = $paymentMethod;
                $query->cus_name = $request->billingName;
                $query->cus_phone = $request->billingPhone;
                $query->cus_email= $request->billingEmail;
                $query->cus_address= $request->billingAddress;
                $query->transport_price = $request->shippingMethod ? $request->shippingMethod : 0;
				$query->save();
                
					
                foreach($cart as $key => $item){
                    $billdetail = new BillDetail();
                    $billdetail->code_bill = $code_bill;
                    $billdetail->code_product = $item['idpro'];
                    $billdetail->name =$item['name'];
                    $billdetail->price = $this->getCartLinePrice($item);
                    $billdetail->qty = $item['quantity'];
                    $billdetail->images = $item['image'];
                    $billdetail->variant = $item['status_variant'] == 1 ? $item['variant'] : '';
                    $billdetail->save();
                }

                if ($paymentMethod === 'online') {
                    $checkoutUrl = $this->createPayosPaymentLink($query, $cart, $request);
                    DB::commit();
                    return redirect()->away($checkoutUrl);
                }

                $this->reduceProductQtyByBill($query);
                DB::commit();
                $this->sendBillNotificationMail($query, $cart);
                $request->session()->forget('cart');
                return view('cart.orderSuccess');
			} catch (\Throwable $e) {
			    DB::rollBack();
                Log::error('Checkout failed', ['error' => $e->getMessage()]);
                return back()->with('error','Gửi đơn hàng thất bại: ' . $e->getMessage());
			}
    }

    public function payosReturn(Request $request)
    {
        $orderCode = (int)$request->get('orderCode', 0);
        $status = strtoupper((string)$request->get('status', ''));
        if ($orderCode > 0 && $status === 'PAID') {
            $bill = $this->markBillPaidByOrderCode($orderCode);
            if ($bill) {
                session()->forget('cart');
                return view('cart.orderSuccess');
            }
        }
        return redirect()->route('checkout')->with('error', 'Thanh toán chưa hoàn tất, vui lòng thử lại.');
    }

    public function payosCancel(Request $request)
    {
        $orderCode = (int)$request->get('orderCode', 0);
        if ($orderCode > 0) {
            $bill = Bill::where('code_bill', $orderCode)->first();
            if ($bill && (int)$bill->statu === 0) {
                $bill->statu = 2;
                $bill->save();
            }
        }
        return redirect()->route('checkout')->with('error', 'Bạn đã hủy thanh toán online.');
    }

    public function payosWebhook(Request $request)
    {
        try {
            $data = $request->input('data', []);
            $code = $request->input('code');
            if ((string)$code === '00' && isset($data['orderCode'])) {
                $this->markBillPaidByOrderCode((int)$data['orderCode']);
            }
        } catch (\Throwable $e) {
            Log::error('PayOS webhook error', ['error' => $e->getMessage()]);
        }
        return response()->json(['error' => 0, 'message' => 'ok']);
    }
    public function listCart(){
        $data['cart'] = session()->get('cart', []);
        return view('cart.list',$data);
    }
    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $cart = session()->get('cart', []);
        

        if(isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $cart[$request->product_id]['quantity'] + $request->quantity;
        } else {
            $cart[$request->product_id] = [
                "idpro" => $request->product_id,
                "name" => $product->name,
                "variant"=>$request->variant,
                "quantity" => $request->quantity,
                "price" => $request->price == 0 ? $product->price : $request->price,
                'status_variant' => $product->status_variant,
                "discount" => $product->discount,
                "image" => json_decode($product->images)[0]
            ];
        }
        session()->put('cart', $cart);
        return response()->json($cart);
    }
    public function update(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json($cart);
        }
        
    }
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json($cart);
        }
    }
    public function orderSuccess()
    {
        return view('cart.orderSuccess');
    }
}
