<?php

namespace App\models\tag;

use Illuminate\Database\Eloquent\Model;
use App\models\tag\Tags;
use App\models\product\Category;

class TagCate extends Model
{
    protected $table = "tag_cate";
    public function cateProduct()
    {
        return $this->hasOne(Category::class,'id','cate_product_id');
    }
    public function tags()
    {
        return $this->hasMany(Tags::class,'cate_tag_id','id');
    }
    public function saveCate($request)
    {
        $cat = Category::where('id',$request->cate_product_id)->first('slug');
        $id = $request->id;
        if($id != "" ){
            $query = TagCate::where([
                'id' => $id
             ])->first();
            if ($query) {
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->cate_product_id = $request->cate_product_id;
                $query->cate_product_slug = $cat ? $cat->slug : '';
                $query->status = $request->status;
                $query->status_filter = $request->status_filter;
                $query->save();
            }else{
                $query = new TagCate();
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->cate_product_id = $request->cate_product_id;
                $query->cate_product_slug = $cat ? $cat->slug : '';
                $query->status = $request->status;
                $query->status_filter = $request->status_filter;
                $query->save();
            }
            
        }else{
            $query = new TagCate();
            $query->name = $request->name;
            $query->slug = to_slug($request->name);
            $query->cate_product_id = $request->cate_product_id;
            $query->cate_product_slug = $cat ? $cat->slug : '';
            $query->status = $request->status;
            $query->status_filter = $request->status_filter;
            $query->save();
            
        }
        return $query;
    }
}
