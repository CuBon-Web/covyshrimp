<?php

namespace App\Http\Controllers\Api\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\product\Product;
use App\models\tag\TagCate;
use App\models\website\Setting;
use App\models\VariantSkuValue;
use DB;

class ProductController extends Controller
{
    public function listVariantSku($id)
    {
        $data = VariantSkuValue::where('product_id',$id)->get();
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function listTags($id)
    {
        $setting = Setting::first();
        $useGlobalTags = !is_null($setting) && isset($setting->use_global_tags)
            ? (int) $setting->use_global_tags === 1
            : true;

        $query = TagCate::with(['tags']);
        if (!$useGlobalTags) {
            $query->where('cate_product_id', $id);
        }

        $data = $query->get();
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function create(Request $request, Product $product)
    {
        $data = $product->createOrEdit($request);
        return response()->json([ 'data' => $data, 'message' => 'success'], 200);
    }
    public function list(Request $request)
    {
        $query = Product::query()
            ->leftJoin('product_category', function($join){
                $join->on('product_category.id','=','products.category');
            })
            ->select('products.*','product_category.name as cate');

        if($request->keyword){
            $query->where('products.name', 'LIKE', '%'.$request->keyword.'%');
        }
        if($request->category){
            $query->where('products.category', $request->category);
        }
        if($request->type_cate){
            $query->where('products.type_cate', $request->type_cate);
        }
        if($request->type_two){
            $query->where('products.type_two', $request->type_two);
        }
        if($request->price_sort && in_array($request->price_sort, ['asc', 'desc'])){
            $query->orderBy('products.price', $request->price_sort);
        }
        if($request->created_sort && in_array($request->created_sort, ['asc', 'desc'])){
            $query->orderBy('products.created_at', $request->created_sort);
        }else{
            $query->orderBy('products.created_at', 'desc');
        }
        $query->orderBy('products.id','DESC');
        $data = $query->get();
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function edit($id)
    {
        $data = Product::where([
            'id'=> $id
        ])
        ->first();
        return response()->json([
            'data' => $data,
            'message' => 'success'
        ]);
    }
    public function delete($id)
    {
        $query = Product::where(['id'=>$id])
        ->first();
        $this->removeProductAndAssets($query);
       
        return response()->json([
            'message' => 'Delete success'
        ]); 
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];
        if(!is_array($ids) || count($ids) == 0){
            return response()->json([
                'message' => 'Danh sách sản phẩm trống'
            ], 422);
        }
        DB::beginTransaction();
        try {
            $products = Product::whereIn('id', $ids)->get();
            foreach ($products as $product) {
                $this->removeProductAndAssets($product);
            }
            DB::commit();
            return response()->json([
                'message' => 'Bulk delete success'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Bulk delete failed'
            ], 500);
        }
    }
    public function bulkStatus(Request $request)
    {
        $ids = $request->ids ?? [];
        $status = $request->status;
        if(!is_array($ids) || count($ids) == 0){
            return response()->json([
                'message' => 'Danh sách sản phẩm trống'
            ], 422);
        }
        if($status !== 0 && $status !== 1 && $status !== '0' && $status !== '1'){
            return response()->json([
                'message' => 'Trạng thái không hợp lệ'
            ], 422);
        }
        Product::whereIn('id', $ids)->update([
            'status' => (int)$status
        ]);
        return response()->json([
            'message' => 'Bulk status success'
        ]);
    }
    public function bulkDuplicate(Request $request)
    {
        $ids = $request->ids ?? [];
        if(!is_array($ids) || count($ids) == 0){
            return response()->json([
                'message' => 'Danh sách sản phẩm trống'
            ], 422);
        }
        DB::beginTransaction();
        try {
            $products = Product::whereIn('id', $ids)->get();
            foreach ($products as $product) {
                $this->cloneProduct($product);
            }
            DB::commit();
            return response()->json([
                'message' => 'Bulk duplicate success'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Bulk duplicate failed'
            ], 500);
        }
    }
    public function duplicate($id)
    {
        DB::beginTransaction();
        try {
            $query = Product::where(['id' => $id])->firstOrFail();
            $newProduct = $this->cloneProduct($query);

            DB::commit();
            return response()->json([
                'data' => $newProduct,
                'message' => 'Duplicate success'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Duplicate failed'
            ], 500);
        }
    }
    private function removeProductAndAssets($product)
    {
        if(!$product){
            return;
        }
        if($product->images){
            $imgArr = json_decode($product->images);
            foreach($imgArr as $i){
                $file= str_replace('http://localhost:8080','',$i);
                $filename = public_path().$file;
                if(file_exists( public_path().$file ) ){
                    \File::delete($filename);
                }
            }
        }
        VariantSkuValue::where('product_id',$product->id)->delete();
        $product->delete();
    }
    private function cloneProduct($product)
    {
        $newProduct = $product->replicate();

        $baseName = $product->name . ' (Copy)';
        $newProduct->name = $baseName;

        $baseSlug = to_slug($baseName);
        $slug = $baseSlug;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $newProduct->slug = $slug;
        $newProduct->save();

        $variantSkuValues = VariantSkuValue::where('product_id', $product->id)->get();
        foreach ($variantSkuValues as $variantSku) {
            $newVariantSku = $variantSku->replicate();
            $newVariantSku->product_id = $newProduct->id;
            $newVariantSku->sku = $newProduct->slug . rand();
            $newVariantSku->save();
        }

        return $newProduct;
    }
}
