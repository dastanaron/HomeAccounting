<?php


namespace Tests\Unit\DB;

use App\Models;

trait createUserTrait
{
    /**
     * @return int
     */
    protected function createUser()
    {
        $user = new Models\User;
        $user->name = 'testUser';
        $user->email = 'test@email.com';
        $user->password = 'password_hash';
        $user->save();
        return $user->id;
    }
}