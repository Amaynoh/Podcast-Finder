<?php

namespace App\Http\Controllers;

use App\Models\Podcast;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Http\Resources\PodcastResource;

/**
 * @OA\Tag(
 *     name="Podcasts",
 *     description="Endpoints pour gérer les podcasts"
 * )
 */
class PodcastController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/podcasts",
     *     tags={"Podcasts"},
     *     summary="Lister tous les podcasts",
     *     @OA\Response(
     *         response=200,
     *         description="Liste paginée des podcasts"
     *     )
     * )
     */
    public function index()
    {
        $podcasts = Podcast::with('host')->latest()->paginate(10);
        return PodcastResource::collection($podcasts);
    }

    /**
     * @OA\Get(
     *     path="/api/podcasts/{id}",
     *     tags={"Podcasts"},
     *     summary="Afficher un podcast",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true
     *     ),
     *     @OA\Response(response=200, description="Détails du podcast"),
     *     @OA\Response(response=404, description="Podcast non trouvé")
     * )
     */
    public function show($id)
    {
        $podcast = Podcast::with(['host', 'episodes'])->findOrFail($id);
        return new PodcastResource($podcast);
    }

    /**
     * @OA\Post(
     *     path="/api/podcasts",
     *     tags={"Podcasts"},
     *     summary="Créer un nouveau podcast",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"title", "image", "audio"},
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="audio", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Podcast créé")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'required|file',
            'audio'       => 'required|file',
        ]);

        // Upload image et audio Cloudinary
        $imageUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        $audioUrl = Cloudinary::uploadFile($request->file('audio')->getRealPath())->getSecurePath();

        $podcast = Podcast::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image_url'   => $imageUrl,
            'audio_url'   => $audioUrl,
            'host_id'     => $request->user()->id,
        ]);

        return new PodcastResource($podcast);
    }

    /**
     * @OA\Put(
     *     path="/api/podcasts/{id}",
     *     tags={"Podcasts"},
     *     summary="Modifier un podcast",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $podcast = Podcast::findOrFail($id);

        if ($request->user()->role === 'host' && $podcast->host_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized']);
        }

        $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'image'       => 'nullable|file',
        ]);

        if ($request->hasFile('image')) {
            $podcast->image_url = Cloudinary::upload(
                $request->image->getRealPath()
            )->getSecurePath();
        }

        $podcast->update($request->only('title', 'description'));

        return new PodcastResource($podcast);
    }

    /**
     * @OA\Delete(
     *     path="/api/podcasts/{id}",
     *     tags={"Podcasts"},
     *     summary="Supprimer un podcast",
     *     security={{"sanctum":{}}}
     * )
     */
    public function destroy(Request $request, $id)
    {
        $podcast = Podcast::findOrFail($id);

        if ($request->user()->role === 'host' && $podcast->host_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized']);
        }

        $podcast->delete();

        return response()->json(['message' => 'Podcast supprimé']);
    }
}

