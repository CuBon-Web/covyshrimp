<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\models\blog\Blog;
use App\models\product\Category;
use App\models\blog\BlogCategory;
use App\models\blog\BlogTypeCate;
use App\models\construction\Construction;
use App\models\product\Product;
use Session;

class BlogController extends Controller
{
    public function list()
    {
        $data['blog'] = Blog::where(['status'=>1])
        ->orderBy('id','DESC')
        ->select(['id','title','image','description','created_at','slug'])
        ->paginate(12);
        $data['title_page'] = 'Tin tức cập nhật';
        return view('blog.list',$data);
    }
    public function listCateBlog($slug)
    {
        $data['blog'] = Blog::where(['status'=>1,'category'=>$slug])
        ->orderBy('id','DESC')
        ->select(['id','title','image','description','created_at','slug'])
        ->paginate(9);
        $cate = BlogCategory::where('slug', $slug)->first(['name']);

        $data['cate_name'] = languageName($cate->name);
        $data['type_name'] = '';
        $data['title_page'] = languageName($cate->name);
        return view('blog.listBlogCate',$data);
    }
    public function listTypeBlog($slug)
    {
        $data['blog'] = Blog::where(['status'=>1,'type_cate'=>$slug])
        ->orderBy('id','DESC')
        ->select(['id','title','image','description','created_at','slug'])
        ->paginate(9);
        
        $cate = BlogTypeCate::where('slug', $slug)->first(['name','category_slug']);
        $cateBlog = BlogCategory::where('slug', $cate->category_slug)->first(['name']);
        $data['cate_name'] = languageName($cateBlog->name);
        $data['type_name'] = languageName($cate->name);

        $data['title_page'] = languageName($cate->name);
        return view('blog.listBlogCate',$data);
    }
    public function detailBlog($slug)
    {
        $data['blog_detail'] = Blog::with('cate')->where(['slug' => $slug])->first();
        $data['bloglq'] = Blog::where(['status' => 1,'category'=>$data['blog_detail']->category])->limit(10)->get();
        $data['blognew'] = Blog::where(['status' => 1])->orderBy('id','DESC')->limit(10)->get();
        $data['prevBlog'] = Blog::where('status', 1)
            ->where('id', '<', $data['blog_detail']->id)
            ->orderBy('id', 'DESC')
            ->first(['id', 'slug', 'title']);
        $data['nextBlog'] = Blog::where('status', 1)
            ->where('id', '>', $data['blog_detail']->id)
            ->orderBy('id', 'ASC')
            ->first(['id', 'slug', 'title']);
        return view('blog.detail',$data);
    }
    public function search_blog(Request $request)
    {
        $keyword = $request->keyword;
        $code = Session::get('locale');
        $arr = [];
        $arrb = [];
        $arrOpt = [];
        //search option
        $productOpt =  Blog::where('status',1)
        ->get(['title','image','description','created_at','slug'])
        ->toArray();
        foreach($productOpt as $key => $item){
            $fielName = json_decode($item['title']);
            foreach($fielName as $i){
                if(strpos(strtolower(stripVN($i->content)), strtolower(stripVN($keyword))) !== false && $i->lang_code == $code){
                    array_push($arr,$productOpt[$key]);
                }
            }
        }
        $data['keyword'] = $request->keyword;
        $data['countproduct'] = count($arr);
        $data['resultPro'] = $arr;
        return view('search_result',$data);
    }
}
