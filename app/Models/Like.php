<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    //migrationでTimestampを削除してあるが、
    //そのままではPOSTメソッドでcreated_atとupdated_atが自動で追加されてしまうので、falseにする
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
