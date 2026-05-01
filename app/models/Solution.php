<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Solution extends Model
{
    protected $table = "solution";
    public function saveSolution($request)
    {
    	$id = $request->id;
        if($id != ""){
            $query = Solution::where([
                'id' => $id
             ])->first();
            
            if ($query) {
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->content = json_encode($request->content);
                $query->description = json_encode($request->description);
                $query->status = $request->status;
                $query->images = json_encode($request->images);
                $query->save();
            }else{
                $query = new Solution();
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->content = json_encode($request->content);
                $query->description = json_encode($request->description);
                $query->status = $request->status;
                $query->images = json_encode($request->images);
                $query->save();
            }
            
        }else{
                $query = new Solution();
                $query->name = $request->name;
                $query->slug = to_slug($request->name);
                $query->content = json_encode($request->content);
                $query->description = json_encode($request->description);
                $query->status = $request->status;
                $query->images = json_encode($request->images);
                $query->save();
            
        }
        return $query;
    }
}
