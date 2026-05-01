<?php

namespace App\models\tag;

use Illuminate\Database\Eloquent\Model;
use App\models\tag\TagCate;
use App\models\product\Category;

class Tags extends Model
{
    protected $table = "tags";
    public function FunctionName() : Returntype {
        
    }
    public function cateTag()
    {
        return $this->hasOne(TagCate::class,'id','cate_tag_id');
    }
    public function catePro()
    {
        return $this->hasOne(Category::class,'id','cate_product_id');
    }
    public function saveTags($request)
    {
        $cat = TagCate::where('id',$request->cate_tag_id)->first();
        $id = $request->id;
        if($id != "" ){
            $query = Tags::where([
                'id' => $id
             ])->first();
            if ($query) {
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->cate_tag_id = $request->cate_tag_id;
                $query->cate_tag_slug = $cat ? $cat->slug : '';
                $query->cate_product_id = $cat->cate_product_id;
                $query->status = $request->status;
                $query->save();
            }else{
                $query = new Tags();
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->cate_tag_id = $request->cate_tag_id;
                $query->cate_tag_slug = $cat ? $cat->slug : '';
                $query->cate_product_id = $cat->cate_product_id;
                $query->status = $request->status;
                $query->save();
            }
        }else{
            $query = new Tags();
            $query->name = $request->name;
            $query->slug = to_slug($request->name);
            $query->cate_tag_id = $request->cate_tag_id;
            $query->cate_tag_slug = $cat ? $cat->slug : '';
            $query->cate_product_id = $cat->cate_product_id;
            $query->status = $request->status;
            $query->save();
        }
        return $query;
    }
}
