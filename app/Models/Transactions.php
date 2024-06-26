<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transactions extends Model
{
    //jika di migrationnya menggunakan $table->softdeletes

    //fillable / guard
    //menentukan column wajib diisi (column yg bisa diisi dari luar)
    protected $fillable = ["product_id","order_date", "quantity"];
    //protected $guarded = ['id']

    //property opsional :
    //kalau primary key bukan id : public $primarykey = 'no'
    //kalau misal gapake timestamps di migration : public $timestamps = FALSE

     // relasi
     //nama function : samain kaya model, kata pertama huruf kecil
     //model yang PK : hasOne/ hanMany
     //panggil namaModelFk::class
     public function products()
     {
         return $this->belongsTo(Products::class);
     }



}

