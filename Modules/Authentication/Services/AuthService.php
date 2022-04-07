<?php

namespace Modules\Authentication\Services;

use App\Models\User;
use Throwable;

class AuthService
{
    protected bool $facebook_account = false;
    protected bool $google_account = false;
    protected bool $wb_account = false;
    protected bool $new_account = false;
    public ?User $user;
    protected string $user_id;
    protected mixed $payload;
    protected mixed $client;
    protected string $token;

    protected function verifyAccountExistence($email) {

        $this->user = User::where(['email' => $email])->first();

        if($this->user) {
            if ($this->user->facebookAccount())
                $this->facebook_account = true;
            elseif ($this->user->googleAccount())
                $this->google_account = true;
            else
                $this->wb_account = true;
        } else
            $this->new_account = true;
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function respondWithToken(): array
    {
        dd($this->user);
        return $this->user->resource();
    }
}
