<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Podcast extends Model
{
    use HasFactory;

    protected $fillable = ['title','description','image_url','host_id',];
    
    public function host()
    {
        return $this->belongsTo(Host::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}

