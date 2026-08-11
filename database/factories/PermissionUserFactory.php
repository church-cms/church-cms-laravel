<?php

namespace Database\Factories;

use App\Models\PermissionUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionUserFactory extends Factory
{
    protected $model = PermissionUser::class;

    public function definition(): array
    {
        return [
            'user_type'  => 'App\Models\User',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
