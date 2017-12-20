<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Account extends Model
{
     // use Notifiable;

    protected $table = 'account';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'balance', 'currency', 'user_id','created_at','updated_at'
    ];


    public function User()
    {
        return $this->belongsTo('App\User');
    }


    public function Transaction()
    {
        return $this->hasMany('App\Transaction');
    }


}
