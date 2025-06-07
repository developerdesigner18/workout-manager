<?php

namespace App\Http\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="role", type="string", enum={"admin", "user"}, example="user"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *     schema="Workout",
 *     type="object",
 *     title="Workout",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Power Training"),
 *     @OA\Property(property="description", type="string", example="Intensive leg day workout"),
 *     @OA\Property(property="trainer", type="string", example="John Doe"),
 *     @OA\Property(property="date", type="string", format="date-time", example="2025-06-07 18:00:00"),
 *     @OA\Property(property="slots", type="integer", example=10),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

/**
 * @OA\Schema(
 *     schema="WorkoutRequest",
 *     type="object",
 *     title="Workout Request",
 *     required={"title", "description", "trainer", "date", "slots"},
 *     @OA\Property(property="title", type="string", example="Power Training"),
 *     @OA\Property(property="description", type="string", example="Intensive leg day workout"),
 *     @OA\Property(property="trainer", type="string", example="John Doe"),
 *     @OA\Property(property="date", type="string", format="date-time", example="2025-06-07T18:00:00"),
 *     @OA\Property(property="slots", type="integer", minimum=1, maximum=100, example=10),
 *     @OA\Property(property="is_active", type="boolean", example=true)
 * )
 */

class ApiSchemas
{
    // This class is just for documentation purposes
}
