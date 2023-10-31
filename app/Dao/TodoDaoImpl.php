<?php

namespace App\Dao;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

class TodoDaoImpl implements TodoDao
{
    function create(int $userId, string $title, string $description = null): Todo
    {
        return Todo::create([
            'title' => $title,
            'description' => $description,
            'finished' => false,
            'user_id' => $userId
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

    function update(int $id, string $title, bool $finished, string $description = null): Todo
    {
        $todo = Todo::find($id);

        $todo->title = $title;
        $todo->finished = $finished;
        $todo->description = $description;

        $todo->save();
        return $todo;
    }


    function delete(int $id): int
    {
        return Todo::destroy($id);
    }

}
