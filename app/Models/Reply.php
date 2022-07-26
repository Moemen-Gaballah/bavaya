<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;
    protected $fillable = [
        'reply',
        'user_id',
        'ticket_id',
    ];

    public function ticket(){
        return $this->belongsTo(Reply::class);
    }
}
