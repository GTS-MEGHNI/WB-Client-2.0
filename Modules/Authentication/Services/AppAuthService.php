<?php

namespace Modules\Authentication\Services;


use App\Models\User;
use App\Responses;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AppAuthService extends AuthService
{

    public function authenticate($credentials)
    {
        $this->verifyAccountExistence($credentials['email']);

        /*if ($this->facebook_account)
            throw new HttpResponseException(
                response()->json(Responses::emptyDebugResponseError(Responses::USING_FACEBOOK_ACCOUNT)));

        if ($this->google_account)
            throw new HttpResponseException(
                response()->json(Responses::emptyDebugResponseError(Responses::USING_GOOGLE_ACCOUNT)));
        */

        if (!Hash::check($credentials['password'], $this->user->password))
            throw new HttpResponseException(
                response()->json(Responses::emptyDebugResponseError(Responses::WRONG_PASSWORD)));

        User::unguard();
        User::where(['id' => $this->user->id])->update([
            'status' => 'connected',
            'last_seen_at' => Carbon::now(),
            'updated_at' => DB::raw('updated_at')
        ]);
        User::reguard();
    }


    /**
     * @throws Throwable
     */
    public function record(): void
    {
        $this->user = User::addNewUser(request()->get('row')->toArray());
    }
}
