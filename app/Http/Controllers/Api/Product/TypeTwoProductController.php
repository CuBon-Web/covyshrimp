<?php

namespace App\Http\Controllers\Api\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\models\product\TypeProductTwo;

class TypeTwoProductController extends Controller
{
    public function add(Request $request, TypeProductTwo $category)
    {
        $data = $category->saveTypeTwo($request);
        return response()->json([
    		'message' => 'Save Success',
    		'data'=> $data
    	],200);
    }
    public function list(Request $request)
    {
        $keyword = $request->keyword;
        if($keyword == ""){
            $data = TypeProductTwo::leftJoin('product_category', function($join){
                $join->on('product_category.id','=','product_type_two.cate_id');
            })
            ->leftJoin('product_type', function($join){
                $join->on('product_type.id','=','product_type_two.type_id');
            })
            ->select('product_type_two.*','product_category.name as cate','product_type.name as type')->orderBy('id','DESC')->get();
        }else{
            $data = TypeProductTwo::where('product_type_two.name', 'LIKE', '%'.$keyword.'%')
            ->leftJoin('product_category', function($join){
                $join->on('product_category.id','=','product_type_two.cate_id');
            })
            ->leftJoin('product_type', function($join){
                $join->on('product_type.id','=','product_type_two.type_id');
            })
            ->orderBy('id','DESC')->select('product_type_two.*','product_category.name as cate','product_type.name as type')->get()
            ->toArray();
        }
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function edit($id)
    {
        $data = TypeProductTwo::where(['id'=>$id])->first();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }
    public function delete( $id)
    {
        $query = TypeProductTwo::find($id);
        $file= str_replace('http://localhost:8080','',$query->avatar);
        $filename = public_path().$file;
        if(file_exists( public_path().$file ) ){
            \File::delete($filename);
        }
        $query->delete();
        return response()->json(['message'=>'Delete Success']);
    }
    public function findType($cate_id)
    {
        $query = TypeProductTwo::where(['type_id'=>$cate_id,'status'=>1])->get();
        return response()->json([
            'message' => 'success',
            'data' => $query
        ], 200);
    }
}
