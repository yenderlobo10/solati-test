<?php

namespace App\Dao;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

interface TodoDao
{

    function create(int $userId, string $title, string $description = null): Todo;

    function update(int $id, string $title, bool $finished, string $description = null): Todo;

    function all(): Collection;

    function allByUser(string $userId): Collection;

    function delete(int $id): int;
}
