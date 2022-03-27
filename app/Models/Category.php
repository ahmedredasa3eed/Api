<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public  $table = 'categories';
    public $fillable = ['name_ar','name_en','status','created_at','updated_at'];
    public  $timestamps = true;
}
