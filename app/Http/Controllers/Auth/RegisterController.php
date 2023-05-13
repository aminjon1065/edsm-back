<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'region' => 'required|string',
            'position' => 'required|string',
            'department' => 'required|string',
            'rank' => 'required|string',
            'signature' => 'image',
            'avatar' => 'image',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()->toArray(),
            ], 422);
        }
        $data = [
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'full_name' => $request['first_name'] . ' ' . $request['last_name'],
            'email' => $request["email"],
            'password' => Hash::make($request["password"]),
            'position' => $request["position"],
            'rank' => $request["rank"],
            'department' => $request["department"],
            'region' => $request['region'],
            'uuid' => (string) \Str::uuid(),
            'permission' => $request['permission']
        ];
        if ($validator->valid()) {
            if ($request->hasFile('signature')) {
                $signature = $request->file('signature');
                $signatureName = $data["first_name"] . '-' . $data['last_name'] . '-' . time() . '.' . $signature->getClientOriginalExtension();
                $signaturePath = 'public/signatures';
                $full_path = $request->file('signature')->storeAs($signaturePath, $signatureName);
//                $signature->move($signaturePath, $signatureName);
                $data['signature'] = $signatureName;
            }
        }
        if ($validator->valid()) {
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $imageName = time() . '-' . $image->getClientOriginalName();
                $imagePath = 'public/avatars';
                $image->storeAs($imagePath, $imageName);
//                $image->move($imagePath, $imageName);
                $data['avatar'] = $imageName;
            }
        }
        $user = User::create($data);
        if ($user) {
            return response()->json([
                'status' => 'success',
                'message' => 'Успешно зарегистрировано',
//                    'data' => 'token: ' . $token . ' ',
//            'data' => $token,
            ], 201);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Непредвиденная ошибка'
        ], 422);
    }
}
