<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAuthUser;
use Illuminate\Http\Request;

class LogAuthUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($device, $ip, $lastAuth)
    {
        $data = LogAuthUser::create([
            'user_id' => auth()->user()->id,
            'device' => $device,
            'ip' => $ip,
            'last_auth' => $lastAuth
        ]);
        return [
            'user_id' => $data['user_id'],
            'device' => $data['device'],
            'ip' => $data['ip'],
            'last_auth' => date('d-m-Y H-i-s')
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(LogAuthUser $logAuthUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogAuthUser $logAuthUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogAuthUser $logAuthUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogAuthUser $logAuthUser)
    {
        //
    }
}
