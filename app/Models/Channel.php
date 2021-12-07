<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "image",
        "admin_id"
    ];

    public function admin(){
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function users(){
        return $this->belongsToMany(User::class, "channel_user");
    }
}
