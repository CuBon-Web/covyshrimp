<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\Services;
class ServiceCate extends Model
{
    protected $table = "service_category";
    public function services()
    {
        return $this->hasMany(Services::class,'cate_id','id');
    }
    public function saveCate($request)
    {
        $id = $request->id;
        if($id != "" ){
            $query = ServiceCate::where([
                'id' => $id
             ])->first();
            if ($query) {
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->content = $request->content;
                $query->description = $request->description;
                $query->status = $request->status;
                $query->image = $request->image;
                $query->save();
            }else{
                $query = new ServiceCate();
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->content = $request->content;
                $query->status = $request->status;
                $query->description = $request->description;
                $query->image = $request->image;
                $query->save();
            }
            
        }else{
            $query = new ServiceCate();
            $query->name = $request->name;
            $query->slug = to_slug($request->name);
            $query->content = $request->content;
            $query->status = $request->status;
            $query->description = $request->description;
            $query->image = $request->image;
            $query->save();
            
        }
        return $query;
    }
}
