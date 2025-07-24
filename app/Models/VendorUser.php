<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorUser extends Model
{
    use HasFactory;

    protected $connection = 'main_db'; // Global database connection

    protected $fillable = [
        'vendor_id', 
        'user_id', 
        'role'
    ];

    // Relationship with Vendor model
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
