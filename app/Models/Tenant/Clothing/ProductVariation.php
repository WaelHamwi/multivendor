<?php

namespace App\Models\Tenant\Clothing;

use Illuminate\Database\Eloquent\Model;


class ProductVariation extends Model
{
    protected $fillable = ['product_id', 'variation_option_ids', 'price', 'quantity'];

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
