<?php

namespace Modules\Authentication\Services;

use App\Models\User;
use App\Responses;
use Carbon\Carbon;
use Exception;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Throwable;

class FacebookService extends AuthService
{
    /**
     * @throws Throwable
     */
    public function __construct()
    {
        try {
            $this->client = new Facebook([
                'app_id' => config('services.facebook.app_id'),
                'app_secret' => config('services.facebook.app_secret'),
            ]);
        } catch (FacebookSDKException $e) {
            throw new HttpResponseException(
                response()->json(Responses::DebugResponseError($e->getMessage(),
                    Responses::GENERAL_ERROR_FR))
            );
        }
    }

    protected function getPayload()
    {
        try {
            $this->payload = $this->client->get('/me?fields=email,first_name,last_name,picture.type(large)',
                $this->token);
        } catch (FacebookSDKException $e) {
            throw new HttpResponseException(response()
                ->json(Responses::DebugResponseError($e->getMessage(),
                    Responses::GENERAL_ERROR_FR))
            );
        }
    }

    /**
     * @param $token
     * @return bool
     * @throws Throwable
     */
    public function isValidToken($token): bool
    {
        try {
            $this->client->get('/me', $token);
            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * @throws Throwable
     */
    public function authenticate(array $payload)
    {
        $this->token = $payload['token'];
        $this->getPayload();
        $this->verifyAccountExistence($this->payload->getDecodedBody()['email']);

        // No account registered
        if ($this->new_account)
            $this->recordUser();
        else
            $this->updateUserDataBeforeLogging();
    }

    private function updateUserDataBeforeLogging()
    {
        $body = $this->payload->getDecodedBody();
        User::where(['id' => $this->user->id])->update([
            'first_name' => $body['first_name'],
            'last_name' => $body['last_name'],
            'email' => $body['email'],
            'avatar' => $body['picture']['data']['url'],
            'last_seen_at' => Carbon::now(),
            'updated_at' => DB::raw('updated_at')
        ]);
    }

    private function recordUser()
    {
        $body = $this->payload->getDecodedBody();
        $this->user = User::addNewUser([
            'first_name' => $body['first_name'],
            'last_name' => $body['last_name'],
            'email' => $body['email'],
            'social_id' => $body['id'],
            'avatar' => $body['picture']['data']['url'],
            'provider' => 'facebook'
        ]);
    }
}
