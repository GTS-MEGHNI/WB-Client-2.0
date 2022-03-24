<?php

namespace Modules\Authentication\Services;

use App\Models\User;
use Carbon\Carbon;
use Exception;
use Google_Client;
use Illuminate\Support\Facades\DB;
use Throwable;

class GoogleService extends AuthService
{
    protected mixed $client;

    public function __construct()
    {
        $this->client = new Google_Client([
            //'client_id' => config('services.google.id')
        ]);
    }

    /**
     * @param array $payload
     * @throws Throwable
     */
    public function authenticate(array $payload)
    {
        $this->payload = $this->client->verifyIdToken($payload['token']);

        $this->verifyAccountExistence($this->payload['email']);

        if ($this->new_account)
            $this->recordUser();
        else
            $this->updateDataBeforeLogging($payload['token']);
    }

    public function updateDataBeforeLogging(string $token)
    {
        $this->payload = $this->client->verifyIdToken($token);
        User::unguard();
        User::where(['id' => $this->user->user_id])->update([
            'first_name' => $this->payload['given_name'],
            'last_name' => $this->payload['family_name'],
            'avatar' => $this->payload['picture'],
            'last_seen_at' => Carbon::now(),
            'updated_at' => DB::raw('updated_at')
        ]);
        User::reguard();

    }

    public function isValidToken($token): bool
    {
        try {
            return $this->client->verifyIdToken($token) != false;
        } catch (Exception) {
            return false;
        }
    }

    private function recordUser()
    {
        $this->user = User::addNewUser([
            'first_name' => $this->payload['given_name'],
            'last_name' => $this->payload['family_name'],
            'email' => $this->payload['email'],
            'social_id' => $this->payload['sub'],
            'avatar' => $this->payload['picture'],
            'provider' => 'google'
        ]);
    }


}
