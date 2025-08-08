<?php

namespace App\Models\Tenant\Clothing;

use App\Models\Vendor;
use App\Services\Vendor\VendorDatabaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $connection = 'vendor__db';
    protected $table = 'products';
    protected $fillable = ['name', 'slug', 'description', 'price', 'quantity', 'meta_title', 'meta_description', 'status'];


    public function __construct(array $attributes = [])
    {
       
        parent::__construct($attributes);
        app(VendorDatabaseService::class)->setVendorDatabase('vendor_clothing_db');
        $this->setConnection('vendor__db');
       /* $currentDatabase = DB::connection('vendor__db')->select('SELECT DATABASE()');
        dd($currentDatabase);*/
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}
