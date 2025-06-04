<?php

namespace App\Services\Contracts\Auth;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * Login the user and return a token.
     *
     * @param  array<string, string>  $credentials
     * @return string|null token
     */
    public function login(array $credentials): ?string;

    /**
     * Logout the user.
     *
     * @return bool true if logout was successful, false otherwise
     */
    public function logout(): bool;

    /**
     * Get the authenticated user.
     *
     * @return User|null user
     */
    public function user(): ?User;

    /**
     * Refresh the token.
     *
     * @return string|null token
     */
    public function refresh(): ?string;

    /**
     * Register a new user.
     *
     * @param  array<string, string>  $data
     * @return string|null token
     */
    public function register(array $data): ?string;
}
