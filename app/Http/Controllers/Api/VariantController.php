<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Variant;
use App\models\VariantValue;
use DB;

class VariantController extends Controller
{
    public function getValue($id)
    {
        $variant = Variant::where([
            'id'=> $id
        ])
        ->first();
        $data = VariantValue::where('variant_id',$id)->get();
        return response()->json([
    		'message' => 'Save Success',
    		'data'=> $data,
            'variant_name' => $variant->name,
    	],200);
    }
    public function create(Request $request, Variant $ser)
    {
    	$data = $ser->saveVariant($request);
        return response()->json([
    		'message' => 'Save Success',
    		'data'=> $data
    	],200);
    }
    public function list(Request $request)
    {
    	$keyword = $request->keyword;
        if($keyword == ""){
            $data = Variant::with(['variantValue'])->orderBy('id','DESC')->get();
        }else{
            $data = Variant::where('name', 'LIKE', '%'.$keyword.'%')->orderBy('id','DESC')->get()->toArray();
        }
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function delete($id)
    {
        
        DB::beginTransaction();
			try {
				$query = Variant::find($id);
                $query->delete();
                $variant_value = VariantValue::where('variant_id',$id)->get();
                foreach($variant_value as $item){
                    $item->delete();
                }
				DB::commit();
			} catch (\Throwable $e) {
				DB::rollBack();
				throw $e;
			}
        return response()->json(['message'=>'Delete Success'],200);
    }
    public function edit($id)
    {
        $data = Variant::with(['variantValue'])->where([
            'id'=> $id
        ])
        ->first();
        $variant_value = [];
        foreach($data->variantValue as $key => $item){
            $variant_value[$key] = $item->name;
        }
        return response()->json([
            'data' => $data,
            'variant_value'=> $variant_value,
            'message' => 'success'
        ]);
    }
}
