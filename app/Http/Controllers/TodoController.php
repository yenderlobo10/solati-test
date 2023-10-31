<?php

namespace App\Http\Controllers;

use App\Dao\TodoDao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{

    function __construct(protected TodoDao $todoDao)
    {
    }

    function all(): JsonResponse
    {
        $todos = $this->todoDao->all();
        return response()->json($todos);
    }

    function allByUser(string $userId): JsonResponse
    {
        $todos = $this->todoDao->allByUser($userId);
        return response()->json($todos);
    }

    function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:5',
        ]);

        $this->todoDao->create(
            Auth::user()->id,
            '',
            null
        );
    }
}
