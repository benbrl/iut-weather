<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaceUser extends Model
{
    use HasFactory;
    protected $table = 'place_user';
    protected $fillable = ['place_id', 'user_id', 'is_favorite', 'send_forecast'];
    
    

    public $timestamps = false;
    public function place() {
        return $this->belongsTo(AddFavoriteCity::class, 'place_id');
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    

}