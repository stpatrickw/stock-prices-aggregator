<?php

namespace App\Providers;

use App\Models\User;
use \Illuminate\Auth\GenericUser;
use \Illuminate\Contracts\Auth\UserProvider;
use \Illuminate\Contracts\Auth\Authenticatable;

class ArrayUserProvider implements UserProvider
{
    public function __construct(private array $credentialsStore) {}

    public function retrieveById($identifier)
    {
        $username = $identifier;
        $password = $this->credentialsStore[$username];
        return new User([
            'name' => $username,
            'password' => $password,
        ]);
    }

    public function retrieveByToken($identifier, $token) { }

    public function updateRememberToken(Authenticatable $user, $token) { }

    public function retrieveByCredentials(array $credentials)
    {
        $username = $credentials['username'];

        if (!isset($this->credentialsStore[$username])) {
            return null;
        }

        $password = $this->credentialsStore[$username];
        return new GenericUser([
            'name' => $username,
            'password' => $password,
            'id' => $username,
        ]);
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $credentials['username'] == $user->name && $credentials['password'] == $user->getAuthPassword();
    }
}
