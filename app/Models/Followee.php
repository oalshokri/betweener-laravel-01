<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Followee extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
//    protected $visible = ['followee_id','user'];

        public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'followee_id');
    }

}
