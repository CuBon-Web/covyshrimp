<?php

namespace App\Http\Controllers\Api\Tag;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\models\tag\Tags;

class TagController extends Controller
{
    public function add(Request $request, Tags $category)
    {
        $data = $category->saveTags($request);
        return response()->json([
    		'message' => 'Save Success',
    		'data'=> $data
    	],200);
    }
    public function list(Request $request)
    {
        $keyword = $request->keyword;
        if($keyword == ""){
            $data = Tags::with(['cateTag','catePro'])->orderBy('id','DESC')->get();
        }else{
            $data = Tags::where('name', 'LIKE', '%'.$keyword.'%')->orderBy('id','DESC')->get()->toArray();
        }
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function edit($id)
    {
        $data = Tags::where(['id'=>$id])->first();
        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }
    public function delete( $id)
    {
        $query = Tags::find($id);
        $query->delete();
        return response()->json(['message'=>'Delete Success']);
    }
}
