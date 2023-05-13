<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email',
            'password' => '|required|string|min:6',
        ]);
        $userFind = User::where(column: 'email', operator: $attr['email'])->first();
        if (!$userFind) {
            return response()->json([
                'status' => 'error',
                'message' => 'Пользователь не найден'

            ], 401);
        }
        if (!Auth::attempt($attr)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Не правильный E-mail или пароль'
            ], 401);
        }
        $token = auth()->user()->createToken('__sign_token')->plainTextToken;
        $user = $this->isAuth();
        $lastLoginController = new LogAuthUserController();
        $log = $lastLoginController->store($request['device'], $request['ip'], now());
        return response()->json([
            'status' => 'success',
            'message' => 'Успешно вошли в систему',
            'token' => $token,
            'role' => $user['role'] == 1 ? 'Admin' : 'User',
            'last_login' => $log,
            'data' => $user
        ], 201);
    }

    public function isAuth()
    {
        return auth()->user();
    }
}
