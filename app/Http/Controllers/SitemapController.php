<?php

namespace App\Http\Controllers;

use App\models\blog\Blog;
use App\models\product\Product;

class SitemapController extends Controller
{
    public function index()
    {
        $blogLastmod = Blog::max('updated_at');
        $productLastmod = Product::max('updated_at');

        $items = [
            [
                'loc' => url('/sitemaps/static.xml'),
                'lastmod' => now()->toAtomString(),
            ],
            [
                'loc' => url('/sitemaps/blog.xml'),
                'lastmod' => $this->toAtomOrNow($blogLastmod),
            ],
            [
                'loc' => url('/sitemaps/product.xml'),
                'lastmod' => $this->toAtomOrNow($productLastmod),
            ],
        ];

        $xml = view('sitemaps.index', compact('items'))->render();
        return $this->xmlResponse($xml);
    }

    public function staticPages()
    {
        $urls = [
            [
                'loc' => url('/'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => route('allProduct'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => route('allListBlog'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => route('aboutUs'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => route('lienHe'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ],
        ];

        $xml = view('sitemaps.urlset', compact('urls'))->render();
        return $this->xmlResponse($xml);
    }

    public function blog()
    {
        $blogs = Blog::where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        $urls = $blogs->map(function ($blog) {
            return [
                'loc' => route('detailBlog', ['slug' => $blog->slug]),
                'lastmod' => optional($blog->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        })->values()->all();

        $xml = view('sitemaps.urlset', compact('urls'))->render();
        return $this->xmlResponse($xml);
    }

    public function product()
    {
        $products = Product::where('status', 1)
            ->whereNotNull('slug')
            ->where('slug', '<>', '')
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'cate_slug', 'type_slug', 'updated_at']);

        $urls = $products->map(function ($product) {
            return [
                'loc' => route('detailProduct', [
                    'cate' => $product->cate_slug ?: 'san-pham',
                    'type' => $product->type_slug ?: 'loai',
                    'id' => $product->slug,
                ]),
                'lastmod' => optional($product->updated_at)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        })->values()->all();

        $xml = view('sitemaps.urlset', compact('urls'))->render();
        return $this->xmlResponse($xml);
    }

    private function xmlResponse($xml)
    {
        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function toAtomOrNow($value)
    {
        if (!$value) {
            return now()->toAtomString();
        }
        return date('c', strtotime($value));
    }
}
