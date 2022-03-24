<?php

namespace Modules\Authentication\Services;

use App\Models\User;
use App\Utility;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Modules\Authentication\Entities\RegisterLog;
use Throwable;

class QuickAuthService extends AuthService
{

    /**
     * @param array $payload
     * @return array
     * @throws Throwable
     */
    public function checkAccountExistence(array $payload) : array {
        $user = User::where(['email' => $payload['email']])->first();
        $user_found = $user !== null;
        $service = new RegisterService();
        $service->sendPasscode($payload);
        return Utility::remove_array_shape_tag([
            'found' => $user_found,
            'token' => $service->token
        ]);
    }


    /**
     * @param array $payload
     * @throws Throwable
     */
    public function record(array $payload)
    {
        /* purge log row */
        RegisterLog::find(request()->bearerToken())->delete();
        request()->get('row')->password = bcrypt($payload['password']);
        request()->get('row')->save();
        $this->user = User::addNewUser(request()->get('row')->toArray());
    }

    /**
     * @return \Illuminate\Http\Response|JsonResponse
     * @throws Throwable
     */
    public function verifyAccount(): \Illuminate\Http\Response|JsonResponse
    {
        $this->user = User::where(['email' => request()->get('row')->email])->first();
        if ($this->user != null) {
            RegisterLog::find(request()->bearerToken())->delete();
            return Response::json($this->respondWithToken());
        } else
            return Response::noContent();
    }
}
