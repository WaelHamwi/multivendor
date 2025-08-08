<?php

namespace App\Models\Tenant\Clothing;

use App\Services\Vendor\VendorDatabaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subcategory extends Model
{

    protected $connection = 'vendor__db';
    protected $table = 'subcategories';
    protected $fillable = ['category_id', 'name', 'slug'];

    public function __construct(array $attributes = [])
    {

        parent::__construct($attributes);
        app(VendorDatabaseService::class)->setVendorDatabase('vendor_clothing_db');
        $this->setConnection('vendor__db');
        
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
