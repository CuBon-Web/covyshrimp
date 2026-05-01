<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Session,View;
use App\models\website\Setting;
use App\models\website\Banner;
use Cart,Auth;
use App\models\PageContent;
use Laravel\Dusk\DuskServiceProvider;
use App\models\product\Category;
use App\models\language\Language;
use App\models\blog\BlogCategory;
use App\models\ServiceCate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) 
        {
            if(Auth::guard('customer')->user() != null){
                $profile = Auth::guard('customer')->user();
            }else{
                $profile = "";
            }
            $language_current = Session::get('locale');

            $setting = Cache::remember('shared:setting:first', 600, function () {
                return Setting::first();
            });
            $lang = Cache::remember('shared:languages:all', 3600, function () {
                return Language::get();
            });
            $pageContent = Cache::remember('shared:page-content:' . ($language_current ?: app()->getLocale()), 600, function () use ($language_current) {
                return PageContent::where([
                    'language' => $language_current ?: app()->getLocale(),
                    'status' => 1
                ])->get();
            });
            $categoryhome = Cache::remember('shared:category-home', 600, function () {
                $categories = Category::with([
                'tagCate'=> function ($query) {
                    $query->with(['tags'])->where('status',1)->orderBy('id','DESC'); 
                },
                'typeCate' => function ($query) {
                    $query->with(['typetwo'])->where('status',1)->orderBy('id','DESC')->select('cate_id','id', 'name','avatar','slug','cate_slug'); 
                },
                'product' => function ($query) {
                    $query->select([
                            'id',
                            'category',
                            'name',
                            'discount',
                            'price',
                            'images',
                            'slug',
                            'cate_slug',
                            'type_slug',
                            'status_variant',
                        ])
                        ->with(['cate:id,slug,name'])
                        ->where('status', 1)
                        ->orderBy('id', 'DESC')
                        ->take(10);
                },
            ])->where('status',1)->orderBy('id','ASC')->get(['id','name','imagehome','avatar','slug','content']);

                $products = $categories->pluck('product')->flatten();
                $this->attachVariantPriceRange($products);
                return $categories;
            });

            $banner = Cache::remember('shared:banner:active', 600, function () {
                return Banner::where(['status'=>1])->get(['id','image','link','title','description']);
            });
            $cartcontent = session()->get('cart', []);
            $viewold = session()->get('viewoldpro', []);
            $compare = session()->get('compareProduct', []);
            $servicehome = Cache::remember('shared:service-home:active', 600, function () {
                return ServiceCate::where('status',1)->get();
            });
            $blogCate = Cache::remember('shared:blog-categories', 600, function () {
                return BlogCategory::with([
                'typeCate' => function ($query){
                    $query->select('id','slug','name','avatar','category_slug');
                }
            ])
            ->where('status',1)
            ->orderBy('id','DESC')
            ->get(['id','name','slug','avatar'])->map(function ($query) {
                $query->setRelation('listBlog', $query->listBlog->take(6));
                return $query;
            });
            });
            $view->with([
                
                'setting' => $setting,
                'pageContent' => $pageContent,
                'lang' => $lang,
                'banner'=>$banner,
                'profile' =>$profile,
                'categoryhome'=> $categoryhome,
                'cartcontent'=>$cartcontent,
                'viewold'=>$viewold,
                'compare'=>$compare,
                'blogCate'=>$blogCate,
                'servicehome'=> $servicehome
                ]);    
        });  
    }

    private function attachVariantPriceRange($products): void
    {
        $products = collect($products);
        if ($products->isEmpty()) {
            return;
        }

        $variantProductIds = $products
            ->where('status_variant', 1)
            ->pluck('id')
            ->filter()
            ->values()
            ->all();

        if (empty($variantProductIds)) {
            return;
        }

        $ranges = \App\models\VariantSkuValue::query()
            ->selectRaw('product_id, MIN(price) as min_price, MAX(price) as max_price')
            ->whereIn('product_id', $variantProductIds)
            ->groupBy('product_id')
            ->get()
            ->keyBy('product_id');

        foreach ($products as $product) {
            $range = $ranges->get($product->id);
            $product->variant_min_price = $range ? (float) $range->min_price : null;
            $product->variant_max_price = $range ? (float) $range->max_price : null;
        }
    }
}
