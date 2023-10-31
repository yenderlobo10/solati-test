<?php

namespace App\Dao;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

interface TodoDao
{

    function create(int $userID, string $title, string $description = null): Todo;

    function all(): Collection;

    function allByUser(string $userId): Collection;

    function delete(string $id);
}
