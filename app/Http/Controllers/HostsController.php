<?php

namespace App\Http\Controllers;

use App\Models\Host;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

/**
 * @OA\Tag(
 *     name="Hosts",
 *     description="Gestion des animateurs (hosts)"
 * )
 */
class HostsController extends Controller
{
    public function __construct()
    {
        // Toutes les opérations nécessitent un token Sanctum
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/hosts",
     *     summary="Lister tous les animateurs",
     *     tags={"Hosts"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Liste des hosts")
     * )
     */
    public function index()
    {
        return Host::all();
    }

    /**
     * @OA\Post(
     *     path="/api/hosts",
     *     summary="Créer un animateur (avatar Cloudinary)",
     *     tags={"Hosts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="bio", type="string"),
     *                 @OA\Property(property="avatar", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Host créé")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'bio'    => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        // Upload Cloudinary si image
        $avatar_url = null;

        if ($request->hasFile('avatar')) {
            $upload = Cloudinary::uploadFile(
                $request->file('avatar')->getRealPath(),
                ['folder' => 'podcast/hosts']
            );

            $avatar_url = $upload->getSecurePath();
        }

        $host = Host::create([
            'name'   => $validated['name'],
            'bio'    => $validated['bio'] ?? null,
            'avatar' => $avatar_url
        ]);

        return response()->json($host, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/hosts/{id}",
     *     summary="Afficher un host",
     *     tags={"Hosts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\Response(response=200, description="Host trouvé"),
     *     @OA\Response(response=404, description="Non trouvé")
     * )
     */
    public function show(Host $host)
    {
        return $host;
    }

    /**
     * @OA\Put(
     *     path="/api/hosts/{id}",
     *     summary="Modifier un animateur",
     *     tags={"Hosts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="bio", type="string"),
     *                 @OA\Property(property="avatar", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Host mis à jour")
     * )
     */
    public function update(Request $request, Host $host)
    {
        $validated = $request->validate([
            'name'   => 'nullable|string|max:255',
            'bio'    => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        if ($request->hasFile('avatar')) {
            $upload = Cloudinary::uploadFile(
                $request->file('avatar')->getRealPath(),
                ['folder' => 'podcast/hosts']
            );

            $validated['avatar'] = $upload->getSecurePath();
        }

        $host->update($validated);

        return response()->json($host);
    }

    /**
     * @OA\Delete(
     *     path="/api/hosts/{id}",
     *     summary="Supprimer un animateur",
     *     tags={"Hosts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\Response(response=204, description="Supprimé")
     * )
     */
    public function destroy(Host $host)
    {
        $host->delete();
        return response()->json(null, 204);
    }
}
