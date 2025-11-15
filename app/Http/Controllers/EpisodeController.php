<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;
use App\Http\Resources\EpisodeResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

/**
 * @OA\Tag(
 *     name="Episodes",
 *     description="Endpoints pour gérer les épisodes"
 * )
 */
class EpisodeController extends Controller
{
    public function __construct()
    {
        // Toutes les routes Episodes nécessitent Sanctum
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/episodes",
     *     tags={"Episodes"},
     *     summary="Liste tous les épisodes (auth requis)",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des épisodes"
     *     )
     * )
     */
    public function index()
    {
        return EpisodeResource::collection(
            Episode::with('podcast')->latest()->get()
        );
    }

    /**
     * @OA\Post(
     *     path="/api/episodes",
     *     tags={"Episodes"},
     *     summary="Créer un épisode (upload Cloudinary)",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"podcast_id", "title", "audio_file"},
     *                 @OA\Property(property="podcast_id", type="integer"),
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="duration", type="integer"),
     *                 @OA\Property(
     *                      property="audio_file",
     *                      type="string",
     *                      format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Épisode créé")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'podcast_id'  => 'required|exists:podcasts,id',
            'title'       => 'required|string',
            'description' => 'nullable|string',
            'duration'    => 'nullable|integer',
            'audio_file'  => 'required|file|mimes:mp3,wav,m4a|max:20000'
        ]);

        // Upload Cloudinary AUDIO
        $upload = Cloudinary::uploadFile(
            $request->file('audio_file')->getRealPath(),
            ['resource_type' => 'video']   // Cloudinary utilise "video" pour audio
        );

        $audio_url = $upload->getSecurePath();

        $episode = Episode::create([
            'podcast_id'  => $data['podcast_id'],
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'duration'    => $data['duration'] ?? null,
            'audio_url'   => $audio_url
        ]);

        return new EpisodeResource($episode);
    }

    /**
     * @OA\Get(
     *     path="/api/episodes/{id}",
     *     tags={"Episodes"},
     *     summary="Afficher un épisode",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\Response(response=200, description="Détails épisode")
     * )
     */
    public function show(Episode $episode)
    {
        return new EpisodeResource($episode->load('podcast'));
    }

    /**
     * @OA\Put(
     *     path="/api/episodes/{id}",
     *     tags={"Episodes"},
     *     summary="Modifier un épisode (upload audio optionnel)",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="title", type="string"),
     *                 @OA\Property(property="description", type="string"),
     *                 @OA\Property(property="duration", type="integer"),
     *                 @OA\Property(property="audio_file", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Épisode mis à jour")
     * )
     */
    public function update(Request $request, Episode $episode)
    {
        $data = $request->validate([
            'title'       => 'nullable|string',
            'description' => 'nullable|string',
            'duration'    => 'nullable|integer',
            'audio_file'  => 'nullable|file|mimes:mp3,wav,m4a|max:20000'
        ]);

        if ($request->hasFile('audio_file')) {
            $upload = Cloudinary::uploadFile(
                $request->file('audio_file')->getRealPath(),
                ['resource_type' => 'video']
            );

            $data['audio_url'] = $upload->getSecurePath();
        }

        $episode->update($data);

        return new EpisodeResource($episode);
    }

    /**
     * @OA\Delete(
     *     path="/api/episodes/{id}",
     *     tags={"Episodes"},
     *     summary="Supprimer un épisode",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=204, description="Supprimé")
     * )
     */
    public function destroy(Episode $episode)
    {
        $episode->delete();
        return response()->json(['message' => 'Episode deleted successfully']);
    }
}
