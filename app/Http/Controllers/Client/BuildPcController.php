<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\product\Product;

class BuildPcController extends Controller
{
    protected $listOption = [
        'Vi xử lý' => [
            'title'=> 'Vi xử lý',
            'image'=> 'https://firebasestorage.googleapis.com/v0/b/mongcaifood.appspot.com/o/cpu.svg?alt=media&token=77cbc8d7-f570-45a4-8bee-8d87265e39fd',
            'slug'=>'cpu',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Bo mạch chủ' => [
            'title'=> 'Bo mạch chủ',
            'image'=> 'http://static.360buyimg.com/master-of-loader/pc/img/mother-board-icon.82595f5e.svg',
            'slug'=>'mainboard',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Ram' => [
            'title'=> 'Ram',
            'image'=> 'https://firebasestorage.googleapis.com/v0/b/mongcaifood.appspot.com/o/ram.svg?alt=media&token=2ed78d9a-5622-4be9-9ef7-e7c53e3ac544',
            'slug'=>'ram',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Ổ Cứng' => [
            'title'=> 'Ổ Cứng',
            'image'=> 'https://firebasestorage.googleapis.com/v0/b/mongcaifood.appspot.com/o/ssd.svg?alt=media&token=e4be0398-ae2a-4735-b2aa-240c46039abe',
            'slug'=>'o-cung',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'VGA' => [
            'title'=> 'VGA',
            'image'=> 'https://firebasestorage.googleapis.com/v0/b/mongcaifood.appspot.com/o/vga-card.svg?alt=media&token=9f112617-13ac-4016-aa3b-6f03d53a9f9a',
            'slug'=>'vga',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Nguồn' => [
            'title'=> 'Nguồn',
            'image'=> 'https://firebasestorage.googleapis.com/v0/b/mongcaifood.appspot.com/o/power-supply.svg?alt=media&token=47f2d1e9-6927-4778-917d-cb52e7d0b027',
            'slug'=>'nguon',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Vỏ case' => [
            'title'=> 'Vỏ Case',
            'image'=> 'https://firebasestorage.googleapis.com/v0/b/mongcaifood.appspot.com/o/server.svg?alt=media&token=66b99bc2-7d47-4dd7-a1bb-b22976b54b41',
            'slug'=>'case',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Tản nhiệt' => [
            'title'=> 'Tản nhiệt',
            'image'=> 'https://firebasestorage.googleapis.com/v0/b/mongcaifood.appspot.com/o/fan.svg?alt=media&token=726a4f32-3cb8-4bff-ad7b-0428414f1443',
            'slug'=>'tan-nhiet',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Màn hình' => [
            'title'=> 'Màn hình',
            'image'=> 'https://firebasestorage.googleapis.com/v0/b/mongcaifood.appspot.com/o/monitor.svg?alt=media&token=9382ecd3-da49-4542-a9b5-8d8eb0a07128',
            'slug'=>'man-hinh',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Bàn phím' => [
            'title'=> 'Bàn phím',
            'image'=> 'http://static.360buyimg.com/master-of-loader/pc/img/keyboard-icon.87b45d1c.svg',
            'slug'=>'ban-phim',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Chuột' => [
            'title'=> 'Chuột',
            'image'=> 'http://static.360buyimg.com/master-of-loader/pc/img/mouse-icon.144f4d99.svg',
            'slug'=>'chuot',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Tai nghe' => [
            'title'=> 'Tai nghe',
            'image'=> 'https://firebasestorage.googleapis.com/v0/b/mongcaifood.appspot.com/o/headphones.svg?alt=media&token=ca5310b8-dc9f-4c2e-9b51-54c07fb4327c',
            'slug'=>'tai-nghe',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ],
        'Loa máy tính' => [
            'title'=> 'Loa máy tính',
            'image'=> 'https://firebasestorage.googleapis.com/v0/b/mongcaifood.appspot.com/o/windows-media-audio.svg?alt=media&token=7d43a7d7-6834-4ecf-99eb-5edfce667300',
            'slug'=>'loa-may-tinh',
            'pro_id'=> 0,
            'name'=>"",
            'status_variant'=> 0,
            'price'=>0,
            'discount'=>0,
            "quantity" => 0,
            'cate_slug'=>"",
            'type_slug'=>"",
            'pro_slug'=>""
        ]
    ];
    public function buildPc()
    {
        // session()->forget('buildpc');
        $data['listBuildpc'] = session()->get('buildpc', []);
        if (count($data['listBuildpc']) == 0) {
            session()->put('buildpc', $this->listOption);
        }
       return view('buildpc',$data);
    }
    public function renderProductBuild(Request $request)
    {
        $product = Product::query();
        if(isset($request->sortby)){
            if($request->sortby == "price-asc"){
                $product = $product->orderBy('price','ASC');
            }elseif($request->sortby == "price-desc"){
                $product = $product->orderBy('price','DESC');
            }elseif($request->sortby =="created-asc"){
                $product = $product->orderBy('id','DESC');
            }else{
                $product = $product; 
            }
        }
        if(isset($request->keyword)){
            $product = $product->where('name', 'like',  '%'.$request->keyword.'%');
        }
        $product = $product->where('cate_build_pc', 'like', '%'.$request->slug.'%')->paginate(10);
        $title = $request->title;
        $slug = $request->slug;
        $view = view("layouts.product.build_pc_item",compact('product','title','slug'))->render();
        return response()->json([
            'html'=>$view
        ]);
    }

    public function addProductBuildPc(Request $request)
    {
        // session()->forget('buildpc'); 
        $build = session()->get('buildpc', []);
        $product = Product::where('id',$request->proid)->first();
       
        if (isset($build[$request->title]) && $build[$request->title]['pro_id'] == $request->proid) {
            $build[$request->title]['quantity'] = $build[$request->title]['quantity'] + 1;
        }else{
            $build[$request->title] = [
                'title'=> $request->title,
                'image'=> json_decode($product->images)[0],
                'slug'=>$request->slug,
                'pro_id'=> $request->proid,
                'name'=>$product->name,
                'status_variant'=> $product->status_variant,
                'price'=>$product->price,
                'discount'=>$product->discount,
                "quantity" => 1,
                'cate_slug'=>$product->cate_slug,
                'type_slug'=>$product->type_slug,
                'pro_slug'=>$product->slug
            ];
        }
        session()->put('buildpc', $build);
        // dd($build);
        $view = view("layouts.product.build_pc_render",compact('build'))->render();
        return response()->json([
            'html'=>$view
        ]);
    }
    public function removeItemBuild(Request $request)
    {
        $build = session()->get('buildpc', []);
        if (isset($build[$request->title])){
            $build[$request->title] = $this->listOption[$request->title];
        }
        session()->put('buildpc', $build);
        // dd($build);
        $view = view("layouts.product.build_pc_render",compact('build'))->render();
        return response()->json([
            'html'=>$view
        ]);
    }
    public function plusQtyItemBuild(Request $request)
    {
        $build = session()->get('buildpc', []);
        if (isset($build[$request->title])){
            $build[$request->title]['quantity'] = $build[$request->title]['quantity'] + 1;
        }
        session()->put('buildpc', $build);
        // dd($build);
        $view = view("layouts.product.build_pc_render",compact('build'))->render();
        return response()->json([
            'html'=>$view
        ]);
    }
    public function mineQtyItemBuild(Request $request)
    {
        $build = session()->get('buildpc', []);
        if (isset($build[$request->title])){
            $build[$request->title]['quantity'] = $build[$request->title]['quantity'] - 1;
        }
        session()->put('buildpc', $build);
        // dd($build);
        $view = view("layouts.product.build_pc_render",compact('build'))->render();
        return response()->json([
            'html'=>$view
        ]);
    }
    public function addBuildToCart(Request $request)
    {
        $build = session()->get('buildpc', []);
        $cart = session()->get('cart', []);
        // dd($cart);
        foreach ($build as $item){
            if($item['pro_id'] != 0){
                if(isset($cart['pro_id'])) {
                    $cart[$item['pro_id']]['quantity'] = $cart[$item['pro_id']]['quantity'] + $request->quantity;
                } else {
                    $cart[$item['pro_id']] = [
                        "idpro" => $item['pro_id'],
                        "name" => $item['name'],
                        "variant"=>"",
                        "quantity" => $item['quantity'],
                        "price" => $item['price'],
                        'status_variant' => $item['status_variant'],
                        "discount" => $item['discount'],
                        "image" => $item['image']
                    ];
                }
                session()->put('cart', $cart);
            }
        }
        return response()->json([
            'message'=>'success'
        ]);
    }
}
