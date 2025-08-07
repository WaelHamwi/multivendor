<?php
// app/Models/Tenant/Clothing/OrderItem.php
namespace App\Models\Tenant\Clothing;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'variation_id', 'quantity', 'price'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relationship with ProductVariation
    public function variation()
    {
        return $this->belongsTo(ProductVariation::class);
    }
}
