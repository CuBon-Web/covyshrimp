<?php

namespace App\Http\Controllers\Api\Bill;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\Bill\BillDetail;
use App\models\Bill\Bill;
use Carbon\Carbon;

class BillController extends Controller
{
    public function add(Request $request, Bill $bill)
    {
        $data = $bill->saveBill($request);
        return response()->json([
        	'data'=>$data,
        	'message'=>'success'
        ]);
    }
    public function draft(Request $request)
    {
        $keyword = trim((string) $request->keyword);
        $status = $request->status;
        $paymentMethod = $request->payment_method;

        $query = Bill::leftJoin('customer', function($join){
                $join->on('customer.id','=','bill.code_customer');
            })
            ->select('bill.*','customer.name as customer_name');

        if ($status !== '' && $status !== null) {
            $query->where('bill.statu', $status);
        }

        if ($paymentMethod !== '' && $paymentMethod !== null) {
            $query->where('bill.payment_method', $paymentMethod);
        }

        if ($keyword !== '') {
            $query->where(function ($subQuery) use ($keyword) {
                $subQuery->where('bill.code_bill', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('bill.cus_name', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('bill.cus_phone', 'LIKE', '%'.$keyword.'%')
                    ->orWhere('bill.cus_email', 'LIKE', '%'.$keyword.'%');
            });
        }

        $dateRange = $request->date_range;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        if ($dateRange === 'today') {
            $query->whereDate('bill.created_at', Carbon::today());
        } elseif ($dateRange === 'yesterday') {
            $query->whereDate('bill.created_at', Carbon::yesterday());
        } elseif ($dateRange === 'last_7_days') {
            $query->whereBetween('bill.created_at', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()]);
        } elseif ($dateRange === 'last_30_days') {
            $query->whereBetween('bill.created_at', [Carbon::now()->subDays(29)->startOfDay(), Carbon::now()->endOfDay()]);
        }

        if (!empty($startDate)) {
            $query->whereDate('bill.created_at', '>=', $startDate);
        }
        if (!empty($endDate)) {
            $query->whereDate('bill.created_at', '<=', $endDate);
        }

        $data = $query->orderBy('bill.id','DESC')->get();

        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function detail($code, Bill $bill)
    {
        $data = $bill->getDetail($code);
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function changeStatus(Request $request)
    {
        $data = Bill::where('code_bill',$request->code_bill)->first();
        $data->statu = $request->status;
        $data->save();
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
}
