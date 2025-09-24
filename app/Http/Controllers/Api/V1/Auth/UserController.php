<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Api\V1\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function userInfo(Request $request)
    {
        try {
        $user = Auth::user();
        return $this->success(200,'User Information',$user);
    }
    catch(Exception $e){
        Log::error('UserController::userInfo', ['error' => $e->getMessage()]);
        throw $e;
        }
    }

}
