<?php

namespace App\Http\Controllers;

use App\Dao\TodoDao;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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

    function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|min:5',
        ]);

        $todo = $this->todoDao->create(
            Auth::user()->id,
            $request->input('title'),
            $request->input('description')
        );

        return Redirect::route('todos', ['todo' => $todo]);
    }

    function edit(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required|min:5',
        ]);

        $todo = $this->todoDao->update(
            $request->input('id'),
            $request->input('title'),
            $request->input('finished'),
            $request->input('description')
        );

        return Redirect::route('todos', ['todo' => $todo]);
    }

    function delete(int $id): JsonResponse
    {
        $id = $this->todoDao->delete($id);
        return response()->json($id);
    }
}
