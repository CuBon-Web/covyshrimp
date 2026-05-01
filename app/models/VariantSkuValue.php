<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VariantSkuValue extends Model
{
    protected $table = "variant_sku_value";
    public function getPriceProductVariant($proid){
        $data = $this->where('product_id',$proid)->first();
        return number_format($data->price);
    }
}
