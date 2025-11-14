<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    use HasFactory;
    protected $fillable = ['title','description','image','host_id',];

    public function host() {
    return $this->belongsTo(User::class, 'host_id');
}
    public function episodes() {
    return $this->hasMany(Episode::class);
}

}
