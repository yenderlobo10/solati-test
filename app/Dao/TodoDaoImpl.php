<?php

namespace App\Dao;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

class TodoDaoImpl implements TodoDao
{
    function create(int $userID, string $title, string $description = null): Todo
    {
        return Todo::created([
            'title' => $title,
            'description' => $description,
            'finished' => false,
            'user_id' => $userID
        ]);
    }


    function all(): Collection
    {
        return Todo::all();
    }

    function allByUser(string $userId): Collection
    {
        return Todo::all()->where('user_id', $userId);
    }


}
