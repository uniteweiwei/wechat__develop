<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Requests;

class Order extends Model
{
    protected $primaryKey = 'oid';
    public $timestamps = false;
    protected $fillable=['xm','tel','address'];


}
