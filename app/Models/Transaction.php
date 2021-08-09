<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id_payer',
        'user_id_payee',
        'amount',
        'type'
    ];

    public function payer()
    {
        return $this->belongsTo(User::class, 'user_id_payer');
    }

    public function payee()
    {
        return $this->belongsTo(User::class, 'user_id_payee');
    }
}
