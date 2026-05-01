<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\ServiceCate;

class Project extends Model
{
    protected $table = "projects";
    public function cateService()
    {
        return $this->hasOne(ServiceCate::class,'id','cate_id');
    }
    public function saveProject($request)
    {
    	$id = $request->id;
        if($id != ""){
            $query = Project::where([
                'id' => $id
             ])->first();
            
            if ($query) {
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->content = json_encode($request->content);
                $query->cate_id = $request->cate_id;
                $query->description = json_encode($request->description);
                $query->location = $request->location;
                $query->scale = $request->scale;
                $query->operate = $request->operate;
                $query->images = json_encode($request->images);
                $query->status = $request->status;
                $query->save();
            }else{
                $query = new Project();
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->content = json_encode($request->content);
                $query->cate_id = $request->cate_id;
                $query->description = json_encode($request->description);
                $query->location = $request->location;
                $query->scale = $request->scale;
                $query->operate = $request->operate;
                $query->images = json_encode($request->images);
                $query->status = $request->status;
                $query->save();
            }
            
        }else{
                $query = new Project();
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->content = json_encode($request->content);
                $query->cate_id = $request->cate_id;
                $query->description = json_encode($request->description);
                $query->location = $request->location;
                $query->scale = $request->scale;
                $query->operate = $request->operate;
                $query->images = json_encode($request->images);
                $query->status = $request->status;
                $query->save();
            
        }
        return $query;
    }
}
