<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Transaction extends Model
{
    //use Notifiable;

    protected $table = 'transactions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'remark', 'sender_id', 'account_id', 'is_credit','is_debit','created_at','updated_at'
    ];


    public function Account()
    {
        return $this->belongsTo('App\Account');
    }



    

}
