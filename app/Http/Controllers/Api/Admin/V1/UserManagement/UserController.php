<?php

namespace App\Http\Controllers\Api\Admin\V1\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword   = $request->input('keyword');
        $order     = $request->input('order_by', 'name');
        $direction = $request->input('order_direction', 'asc');

        $users = User::query()
            ->when($keyword, function ($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                      ->orWhere('username', 'like', "%{$keyword}%")
                      ->orWhere('email', 'like', "%{$keyword}%");
            })
            ->orderBy($order, $direction)
            ->paginate(10)
            ->withQueryString();

        return new UserCollection($users);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->noContent(); 
    }
}
