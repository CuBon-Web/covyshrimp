<?php

namespace App\Http\Controllers\Api\Tag;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\models\tag\TagCate;

class TagCateController extends Controller
{
    public function add(Request $request, TagCate $category)
    {
        $data = $category->saveCate($request);
        return response()->json([
    		'message' => 'Save Success',
    		'data'=> $data
    	],200);
    }
    public function list(Request $request)
    {
        $keyword = $request->keyword;
        if($keyword == ""){
            $data = TagCate::with(['cateProduct'])->orderBy('id','DESC')->get();
        }else{
            $data = TagCate::where('name', 'LIKE', '%'.$keyword.'%')->orderBy('id','DESC')->get()->toArray();
        }
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function edit($id)
    {
        $data = TagCate::where(['id'=>$id])->first();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }
    public function delete( $id)
    {
        $query = TagCate::find($id);
        $query->delete();
        return response()->json(['message'=>'Delete Success']);
    }
}
