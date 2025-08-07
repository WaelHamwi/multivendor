<?php

namespace App\Models\Tenant\Clothing;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'vendor__db';
    protected $fillable = ['vendor_id', 'status', 'total_amount'];

     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');  
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
