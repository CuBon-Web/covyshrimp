<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\Bill\Bill;
use App\models\Bill\BillDetail;
use Carbon\Carbon;
use App\Customer;
use Spatie\Analytics\Period;
use App\models\product\Product;
use App\models\blog\Blog;
use  App\models\product\Category;
use DB;

class HomeController extends Controller
{
    public function chart(Request $request)
    {
        $dayStart = $request->dateStart && $request->dateEnd ? $request->dateStart : Carbon::now()->subDay(6)->toDateString();
        $dayEnd = $request->dateStart && $request->dateEnd ? $request->dateEnd : Carbon::now()->toDateString();

        $start = strtotime($dayStart);
        $end1 = strtotime($dayEnd);
        $interval   = 1*24*60*60;
        
        $chunks = array();
        for($time=$start; $time<=$end1; $time+=$interval){
            $chunks[] = date('Y-m-d', $time);
        }
        $data = [];
        $summary = Bill::whereBetween(DB::raw('DATE(updated_at)'), [$dayStart, $dayEnd])
            ->selectRaw('DATE(updated_at) as day, SUM(total_money) as total_money')
            ->groupBy('day')
            ->pluck('total_money', 'day')
            ->toArray();

        foreach ($chunks as $day) {
            $data[] = [
                'label' => Carbon::parse($day)->format('d/m'),
                'value' => (string) ((int) ($summary[$day] ?? 0)),
            ];
        }

        return response()->json([
            'data'=> $data,
            'message' => 'success'
        ]);
    }
    public function revenue()
    {
        $obj = new \stdClass;
        $today = Carbon::now()->toDateString();
        $totalrevenue = $billForDay = Bill::where('updated_at', 'like', '%' .$today . '%')
        ->get();
        $new_customer = 0;
        foreach($totalrevenue as $item){
            $email_custom_build = $item->cus_email;
            $checkIsset = Customer::where('email',$email_custom_build)->first();
            if(!$checkIsset){
                $new_customer = ++$new_customer;
            }
        }
        $new_register_custom = Customer::where('updated_at', 'like', '%' .$today . '%')->get();
        if(count($new_register_custom) > 0){
            $new_customer = $new_customer + count($new_register_custom);
        }
        $obj->revenue = number_format($totalrevenue->sum('total_money'));
        $obj->total_bill = count($totalrevenue);
        $obj->new_cus = $new_customer;
        return response()->json([
            'data'=> $obj,
            'message' => 'success'
        ]);
    }
    public function statisticalBill()
    {
        $obj = new \stdClass;
        $obj->chua_thanh_toan = Bill::where('statu', 0)->count();
        $obj->chua_giao_hang = Bill::where('statu', 1)->count();
        $obj->dang_giao_hang = Bill::where('statu', 2)->count();
        $obj->chua_hoan_tat = $obj->chua_thanh_toan + $obj->chua_giao_hang;
        return response()->json([
            'data'=> $obj,
            'message' => 'success'
        ]);
    }
    public function overview()
    {
        $today = Carbon::now()->toDateString();
        $todayRevenue = (int) Bill::whereDate('updated_at', $today)->sum('total_money');
        $todayBill = (int) Bill::whereDate('updated_at', $today)->count();

        $orderStatus = [
            'draft' => Bill::where('statu', 0)->count(),
            'confirmed' => Bill::where('statu', 1)->count(),
            'shipping' => Bill::where('statu', 2)->count(),
        ];

        $topProducts = BillDetail::query()
            ->select('code_product', 'name', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(qty * price) as total_amount'))
            ->groupBy('code_product', 'name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->name,
                    'total_qty' => (int) $item->total_qty,
                    'total_amount' => (int) $item->total_amount,
                ];
            });

        return response()->json([
            'data' => [
                'kpi' => [
                    'today_revenue' => $todayRevenue,
                    'today_bill' => $todayBill,
                    'product_total' => Product::count(),
                    'blog_total' => Blog::count(),
                    'customer_total' => Customer::count(),
                ],
                'order_status' => $orderStatus,
                'top_products' => $topProducts,
            ],
            'message' => 'success'
        ]);
    }
    public function searchNavbar(Request $request)
    {
        $keyword = $request->keyword;
        $data = [];
        if($keyword){
            $product = Product::where('name', 'like', '%' .$keyword . '%')->where('language','vi')->get();
            $customer = Customer::where('name', 'like', '%' .$keyword . '%')->get();
            // $bill = Bill::where('cus_name','like', '%' .$keyword . '%')->get();
            if(count($product)>0){
                foreach($product as $item){
                    $cate = Category::where('id',$item->category)->where('language','vi')->first();
                    $obj = new \stdClass;
                    $obj->name = $item->name;
                    $obj->id = $item->code;
                    $obj->image = $item->images ? json_decode($item->images)[0] : '';
                    $obj->option =  $cate->name;
                    $obj->type =  'product';
                    $data[]=$obj;
                }
            }
            if(count($customer)>0){
                foreach($customer as $item){
                    $obj = new \stdClass;
                    $obj->name = $item->name;
                    $obj->id = $item->id;
                    $obj->image = 'https://cube.elemecdn.com/3/7c/3ea6beec64369c2642b92c6726f1epng.png';
                    $obj->option =  $cate->email;
                    $obj->type =  'customer';
                    $data[]=$obj;
                }
            }
            if(isset($bill) && count($bill)>0){
                foreach($bill as $item){
                    $obj = new \stdClass;
                    $obj->name = $item->cus_name;
                    $obj->id = $item->code_bill;
                    $obj->image = 'https://st4.depositphotos.com/16262510/21432/v/1600/depositphotos_214326750-stock-illustration-bill-vector-icon-isolated-on.jpg';
                    $obj->option =  $item->created_at;
                    $obj->type =  'bill';
                    $data[]=$obj;
                }
            }
        }
        return response()->json([
            'data'=> $data,
            'message' => 'success'
        ]);
    }
}
