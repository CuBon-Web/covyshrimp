<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\product\Product;
use App\models\product\Category;
use App\models\blog\Blog;
use App\models\product\TypeProduct;
use App\models\construction\Construction;
use  App\models\product\TypeProductTwo;
use Session;
use App\models\tag\Tags;
use App\models\tag\TagCate;
use App\models\VariantSkuValue;

class ProductController extends Controller
{
    public function allProduct()
    {
        $data['list'] = Product::where('status',1)->orderBy('id','DESC')->select('id','category','name','discount','price','images','slug','cate_slug','type_slug','status_variant','tags')
        ->paginate(12);
        $data['filter'] = TagCate::with(['tags'])->where('status', 1)->get();
        $data['title'] = "Tất cả sản phẩm";
        $data['content'] = 'none';
        return view('product.list',$data);
    }
    public function flashSale()  {
        $data['list'] = Product::where(['status'=>1,'discountStatus'=>1])
        ->orderBy('id','DESC')
        ->select('id','category','name','discount','price','images','slug','cate_slug','type_slug','description','status_variant')
        ->paginate(20);
        return view('product.flash_sale',$data);
    }
    public function allListCate($danhmuc)
    {
        $data['list'] = Product::where(['status'=>1,'cate_slug'=>$danhmuc])
        ->orderBy('id','DESC')
        ->select('id','category','name','discount','price','images','slug','cate_slug','type_slug','description','status_variant','tags')
        ->paginate(20);
        $data['cateno'] = Category::where('slug',$danhmuc)->first(['id','name','avatar','content','slug']);
        $data['filter'] = TagCate::with(['tags'])->where('cate_product_id',$data['cateno']->id)->get();

        $data['cate_slug'] = $data['cateno']->slug;
        $data['type_slug'] = "";
        $data['type_two_slug'] = "";

        $data['cate_name'] = languageName($data['cateno']->name);
        $data['type_name'] = "";
        $data['type_two_name'] = "";

        $data['title'] = languageName($data['cateno']->name);
        $data['content'] = $data['cateno']->content;
        return view('product.list',$data);
    }
    public function allListType($danhmuc,$loaidanhmuc){
        $data['list'] = Product::where(['status'=>1,'cate_slug'=>$danhmuc,'type_slug'=>$loaidanhmuc])
            ->orderBy('id','DESC')
            ->select('id','category','name','discount','price','images','slug','cate_slug','type_slug','description','status_variant','tags')
            ->paginate(20);
        $data['type'] = TypeProduct::where('slug',$loaidanhmuc)->first(['id','name','cate_id','content','slug']);
        $cate_id = $data['type']->cate_id;
        $data['cateno'] = Category::where('slug',$danhmuc)->first(['id','name','avatar','content','slug']);
        $data['filter'] = TagCate::with(['tags'])->where('cate_product_id',$data['cateno']->id)->get();
        
        $data['cate_slug'] = $data['cateno']->slug;
        $data['type_slug'] = $data['type']->slug;
        $data['type_two_slug'] = "";

        $data['cate_name'] = languageName($data['cateno']->name);
        $data['type_name'] = languageName($data['type']->name);
        $data['type_two_name'] = "";

        $data['title'] = languageName($data['type']->name);
        $data['content'] = $data['type']->content;
        return view('product.list',$data);
    }
    public function allListTypeTwo($danhmuc,$loaidanhmuc,$thuonghieu){
        $data['list'] = Product::where(['status'=>1,'cate_slug'=>$danhmuc,'type_slug'=>$loaidanhmuc,'type_two_slug'=>$thuonghieu])
            ->orderBy('id','DESC')
            ->select('id','category','name','discount','price','images','slug','cate_slug','type_slug','description','status_variant','tags')
            ->paginate(20);
        $data['typetwo'] = TypeProductTwo::where('slug',$thuonghieu)->first(['id','name','cate_id','content','slug']);
        $data['cateno'] = Category::where('slug',$danhmuc)->first(['id','name','avatar','content','slug']);
        $data['type'] = TypeProduct::where('slug',$loaidanhmuc)->first(['id','name','cate_id','content','slug']);

        $data['cate_slug'] = $data['cateno']->slug;
        $data['type_slug'] = $data['type']->slug;
        $data['type_two_slug'] = $data['typetwo']->slug;

        $data['cate_name'] = languageName($data['cateno']->name);
        $data['type_name'] = languageName($data['type']->name);
        $data['type_two_name'] = languageName($data['typetwo']->name);

        $data['filter'] = TagCate::with(['tags'])->where('cate_product_id',$data['cateno']->id)->get();
        $data['title'] = languageName($data['typetwo']->name);
        $data['content'] = $data['typetwo']->content;
        return view('product.list',$data);
    }
    public function tagCateList($tagCateSlug){

        $tagCate = TagCate::where('slug',$tagCateSlug)->first();

        $data['list'] = Product::where('status',1)
            ->where('tags', 'LIKE', '%'.$tagCateSlug.'%')
            ->paginate(12);
            $data['cateno'] = Category::where('id',$tagCate->cate_product_id)->first(['id','name','avatar','content','slug']);
            $data['filter'] = TagCate::with(['tags'])->where('cate_product_id',$data['cateno']->id)->get();
            $cate_id = $data['cateno']->id;
            $data['cate_slug'] = $data['cateno']->slug;
            $data['type_slug'] = "";
            $data['type_two_slug'] = "";
            
            $data['tag_cate'] = $tagCate->name;
            $data['tag_cate_slug'] = $tagCateSlug;
            $data['tag_name'] = '';

            $data['cate_name'] = '';
            $data['type_name'] = '';
            $data['type_two_name'] = '';

            $data['cateid'] = $cate_id;
            $data['title'] = $tagCate->name;
            $data['content'] = "";
        return view('product.list',$data);

    }
    public function tag($tag)
    {
            $tag = Tags::where('slug',$tag)->first();
            $tagCate = TagCate::where('id',$tag->cate_tag_id)->first();
            $keysearch = $tag->slug.'-'.$tagCate->slug;
            $data['list'] = Product::where('status',1)
            ->whereJsonContains('tags', $keysearch)
            ->paginate(12);
            $data['cateno'] = Category::where('id',$tag->cate_product_id)->first(['id','name','avatar','content','slug']);
            $data['filter'] = TagCate::with(['tags'])->where('cate_product_id',$data['cateno']->id)->get();
            $cate_id = $data['cateno']->id;
            $data['cate_slug'] = $data['cateno']->slug;
            $data['type_slug'] = "";
            $data['type_two_slug'] = "";

            $data['tag_cate'] = $tagCate->name;
            $data['tag_cate_slug'] = $tagCate->slug;
            $data['tag_name'] = $tag->name;
            $data['tag_slug'] = $tag->slug;
            $data['cate_name'] = '';
            $data['type_name'] = '';
            $data['type_two_name'] = '';

            $data['cateid'] = $cate_id;
            $data['title'] = $tag->name;
            $data['content'] = "";
        return view('product.list',$data);
    }
    public function CateProList($cate)
    {
        $data['list'] = Product::with('customer','cate')
        ->where('category',$cate)
        ->orderBy('id','ASC')
        ->paginate(12);
        $data['cate'] = Category::where('id',$cate)->first();
        return view('product.list',$data);
    }
    public function TypeProList($type)
    {
        $data['list'] = Product::with('customer','cate')
        ->where('type_cate',$type)
        ->orderBy('id','ASC')
        ->paginate(12);
        $cate = TypeProduct::where('id',$type)->first();
        $data['title_page'] = languageName($cate->name);
        return view('product.list',$data);
    }
    public function getproajax(Request $request)
    {
        if($request->cate == "all"){
            $product = Product::where([
                ['status', '=', 1]
            ])->limit(9)->orderBy('id','ASC')->get(['id','name','discount','price','images','status_variant']);
        }else{
            $product =  Product::where(['status'=>1,'category'=>$request->cate])
            ->orderBy('id','DESC')
            ->select('id','category','name','discount','price','images')
            ->get();
        }
        $view = view("layouts.product.getproajax",compact('product'))->render();
        return response()->json(['html'=>$view]);
    }
    public function filterProduct(Request $request)
    {
        $product = Product::query();

        if( isset($request->fillter) ){
            foreach($request->fillter as $item){
                $product = $product->orWhereJsonContains('tags', $item);
            }
        }
        if($request->keyword){
            $product = $product->where('name', 'LIKE', '%'.$request->keyword.'%');
        }

        // if(isset($request->pricemin) && isset($request->pricemax)){
        //     $product = $product->whereBetween('price', [$request->pricemin, $request->pricemax]);
        // }

        

        
        if(isset($request->sortby)){
            if($request->sortby == "price-asc"){
                $product = $product->orderBy('price','ASC');
            }elseif($request->sortby == "price-desc"){
                $product = $product->orderBy('price','DESC');
            }elseif($request->sortby =="created-oldest"){
                $product = $product->orderBy('id','ASC');
            }elseif($request->sortby =="created-desc"){
                $product = $product->orderBy('id','DESC');
            }else{
                $product = $product; 
            }
        }

        if($request->cate ){
            // dd(2);
            $product = $product->where('cate_slug',$request->cate);
        }
        if( $request->type != null ){
            // dd(1);
            $product = $product->where('type_slug',$request->type);
        }
        if($request->typetwo != null ){
            // dd(3);
            $product = $product->where('type_two_slug',$request->typetwo);
        }
        if(isset($request->pricemin) && $request->pricemin !== null && $request->pricemin !== ''){
            $product = $product->where('discount', '>=', (float) $request->pricemin);
        }
        if(isset($request->pricemax) && $request->pricemax !== null && $request->pricemax !== ''){
            $product = $product->where('discount', '<=', (float) $request->pricemax);
        }

        $product = $product
            ->where('status',1)
            ->select('id','category','name','discount','price','images','slug','cate_slug','type_slug','description','status_variant','tags')
            ->paginate(20);

        $itemsHtml = view("layouts.product.filter_grid_items",compact('product'))->render();
        $paginationHtml = $product->links()->render();
        return response()->json([
            'items_html' => $itemsHtml,
            'pagination_html' => $paginationHtml,
            'total' => $product->total(),
            'count' => $product->count()
        ]);
    }
    public function detail_product($cate,$type,$id)
    {
        
        
        $data['product'] = Product::with([
            'typecatetwo' => function ($query) {
                $query->select('id', 'name','avatar','slug'); 
            },
            'typeCate' => function ($query) {
                $query->select('id', 'name','avatar','slug'); 
            },
            'cate' => function ($query) {
                $query->where('status',1)->limit(5)->select('id','name','avatar','slug'); 
            },
        ])->where('slug',$id)->first(['id','name','images','type_cate','type_two','category','sku','discount','price','content','size','description','slug','preserve','status_variant','created_at','species','variant','cate_slug','type_slug','qty','preserve','seo_title','meta_description','focus_keyword']);
        // session()->forget('viewoldpro'); 
      
        
        $data['productlq'] = Product::where('category',$data['product']->category)->limit(8)->get(['id','category','name','status_variant','discount','price','images','slug','cate_slug','type_slug','description']);

        return view('product.detail',$data);
    }
    public function autosearchcomplete(Request $request)
    {
        $data = Product::where("name","LIKE",'%'.$request->keyword.'%')->select('id','category','name','discount','price','images','slug','cate_slug','type_slug','description','status_variant')->orderBy('id','DESC')
                  ->limit(8)->get();
        $view = view("layouts.product.search_render",compact('data'))->render();
        return response()->json([
            'html'=>$view
        ]);
    }
    public function compare(Request $request)
    {
        // session()->forget('compareProduct');
        $id = $request->id;
        $data['product'] = Product::where('id',$id)->first(['id','name','images','category','discount','price','size','cate_slug','status_variant','type_slug','slug']);
        $compare = session()->get('compareProduct', []);
        if(isset($compare[$id])) {
            session()->put('compareProduct', $compare);
            return response()->json([
                'message' => 'exist'
            ]);
        }
        else {
            if(empty($compare)){
                $compare[$id] = [
                    "idpro" => $id,
                    "name" => $data['product']->name,
                    'image'=> json_decode($data['product']->images)[0],
                    'status_variant'=> $data['product']->status_variant,
                    'price'=> $data['product']->price,
                    'size'=>json_decode($data['product']->size),
                    'discount'=> $data['product']->discount,
                    'cate_slug'=>$data['product']->cate_slug,
                    'type_slug'=>$data['product']->type_slug,
                    'pro_slug'=> $data['product']->slug,
                    'cate'=>$data['product']->category
                ];
            }else{
                foreach($compare as $val){
                    if($data['product']->category != $val['cate']){
                        return response()->json([
                            'data'=> [],
                            'message' => 'error'
                        ]);
                    }
                }
                if(count($compare) == 2){
                    return response()->json([
                        'data'=> [],
                        'message' => 'limit3'
                    ]);
                }else{
                    $compare[$id] = [
                        "idpro" => $id,
                        "name" => $data['product']->name,
                        'image'=> json_decode($data['product']->images)[0],
                        'status_variant'=> $data['product']->status_variant,
                        'price'=> $data['product']->price,
                        'size'=>json_decode($data['product']->size),
                        'discount'=> $data['product']->discount,
                        'cate_slug'=>$data['product']->cate_slug,
                        'type_slug'=>$data['product']->type_slug,
                        'pro_slug'=> $data['product']->slug,
                        'cate'=>$data['product']->category
                    ];
                }
                
            }
            session()->put('compareProduct', $compare);
            $compareProduct = session()->get('compareProduct', []);
            
            return response()->json([
                'data'=> $compareProduct,
                'qty'=> count($compareProduct),
                'message' => 'success'
            ]);
            
        }
        
    }
    public function removeCompare(Request $request)
    {
        if($request->id) {
            $compare = session()->get('compareProduct');
            if(isset($compare[$request->id])) {
                unset($compare[$request->id]);
                session()->put('compareProduct', $compare);
            }
            $list = session()->get('compareProduct', []);
            $view = view("layouts.product.compare",compact('list'))->render();
            return response()->json(['html'=>$view]);
        }

        
    }
    public function compareList()
    {
        $data['list'] = session()->get('compareProduct', []);
        return view('compare.product',$data);
    }
    public function searchResult(Request $request)
    {
        $keyword = $request->keywordsearch;
         $data['product'] = Product::where('name', 'LIKE', '%'.$keyword.'%')->where('status',1)
         ->paginate(18);
         $data['keyword'] = $keyword;
         return view('product.search_result',$data);
    }
    public function getSku(Request $request)
    {
        $data = VariantSkuValue::where(['product_id'=>$request->id,'version'=>$request->value])->first();
        return response()->json([
            'data'=> $data,
            'message' => 'success'
        ]);
    }
}
