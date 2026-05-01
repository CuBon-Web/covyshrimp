<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use App\models\VariantValue;
use DB;
class Variant extends Model
{
    protected $table = "variant";
    public function variantValue()
    {
        return $this->hasMany(VariantValue::class,'variant_id','id');
    }
    public function saveVariant($request)
    {
    	$id = $request->id;
        
        if($id != ""){
            $query = Variant::where([
                'id' => $id
             ])->first();
            DB::beginTransaction();
			try {
				if ($query) {
                    $query->name = $request->name;
                    $query->status = 1;
                    $query->save();
                }else{
                    $query = new Variant();
                    $query->name = $request->name;
                    $query->status = 1;
                    $query->save();
                }
                if($request->variant_value){
                    VariantValue::where('variant_id',$query->id)->delete();
                    foreach ($request->variant_value as $value) {
                        $findname = VariantValue::where('name',$value)->first();
                        if(!$findname){
                            $data = new VariantValue();
                            $data->variant_id = $query->id;
                            $data->name = $value;
                            $data->status = 1;
                            $data->save();
                        }
						
					}
                }
				DB::commit();
			} catch (\Throwable $e) {
				DB::rollBack();
				throw $e;
			}
        }else{
                
            DB::beginTransaction();
            try{
                $query = new Variant();
                $query->name = $request->name;
                $query->status = 1;
                $query->save();
                if($request->variant_value){
                    foreach ($request->variant_value as $value) {
                        $findname = VariantValue::where('name',$value)->first();
                        if(!$findname){
                            $data = new VariantValue();
                            $data->variant_id = $query->id;
                            $data->name = $value;
                            $data->status = 1;
                            $data->save();
                        }
						
					}
                }
                DB::commit();
            }catch (\Throwable $e) {
				DB::rollBack();
				throw $e;
			}
            
        }
        return $query;
    }
}
