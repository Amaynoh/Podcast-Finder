<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Host",
 *     title="Host",
 *     description="Modèle Host",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="bio", type="string", example="Podcaster et auteur"),
 *     @OA\Property(property="avatar", type="string", example="avatar.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-01-01 12:00:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-01-01 12:00:00")
 * )
 */
class Host extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bio', 'avatar'];

    public function podcasts()
    {
        return $this->hasMany(Podcast::class);
    }
}
