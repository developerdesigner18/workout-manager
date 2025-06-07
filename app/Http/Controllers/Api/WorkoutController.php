<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkoutRequest;
use App\Http\Requests\UpdateWorkoutRequest;
use App\Http\Resources\WorkoutResource;
use App\Models\Workout;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Workouts",
 *     description="API Endpoints for workout management"
 * )
 */
class WorkoutController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/workouts",
     *     summary="Get user's workouts",
     *     tags={"Workouts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="is_active",
     *         in="query",
     *         description="Filter by active status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of workouts",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="/app/Http/Schemas/ApiSchemas/Workout")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Workout::byUser(auth()->id())
            ->orderBy('date', 'asc');

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $workouts = $query->get();

        return WorkoutResource::collection($workouts);
    }

    /**
     * @OA\Post(
     *     path="/api/workouts",
     *     summary="Create a new workout",
     *     tags={"Workouts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="/app/Http/Schemas/ApiSchemas/WorkoutRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Workout created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Workout created successfully."),
     *             @OA\Property(property="data", ref="/app/Http/Schemas/ApiSchemas/Workout")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function store(StoreWorkoutRequest $request)
    {
        $workout = Workout::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'message' => 'Workout created successfully.',
            'data' => new WorkoutResource($workout),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/workouts/{id}",
     *     summary="Get a specific workout",
     *     tags={"Workouts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Workout ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Workout details",
     *         @OA\JsonContent(ref="/app/Http/Schemas/ApiSchemas/Workout")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Workout not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function show(Workout $workout)
    {
        $this->authorize('view', $workout);

        return new WorkoutResource($workout);
    }

    /**
     * @OA\Put(
     *     path="/api/workouts/{id}",
     *     summary="Update a workout",
     *     tags={"Workouts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Workout ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="/app/Http/Schemas/ApiSchemas/WorkoutRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Workout updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Workout updated successfully."),
     *             @OA\Property(property="data", ref="/app/Http/Schemas/ApiSchemas/Workout")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Workout not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function update(UpdateWorkoutRequest $request, Workout $workout)
    {
        $workout->update($request->validated());

        return response()->json([
            'message' => 'Workout updated successfully.',
            'data' => new WorkoutResource($workout),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/workouts/{id}",
     *     summary="Delete a workout",
     *     tags={"Workouts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Workout ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Workout deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Workout deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Workout not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy(Workout $workout)
    {
        $this->authorize('delete', $workout);

        $workout->delete();

        return response()->json([
            'message' => 'Workout deleted successfully.',
        ]);
    }
}
