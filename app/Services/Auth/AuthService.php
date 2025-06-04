<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Contracts\Auth\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function login(array $credentials): ?string
    {
        if (! Auth::attempt($credentials)) {
            return null;
        }

        /** @var User $user */
        $user = Auth::user();

        return $user->createToken('API Token')->plainTextToken;
    }

    /**
     * {@inheritDoc}
     */
    public function logout(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        /** @var User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function user(): ?User
    {
        if (! Auth::check()) {
            return null;
        }
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function refresh(): ?string
    {
        if (! Auth::check()) {
            return null;
        }

        /** @var User $user */
        $user = Auth::user();

        return $user->createToken('API Token')->plainTextToken;
    }

    /**
     * {@inheritDoc}
     */
    public function register(array $data): ?string
    {
        /** @var User|null */
        $user = User::create($data);

        if (! $user) {
            return null;
        }

        return $user->createToken('API Token')->plainTextToken;
    }
}
