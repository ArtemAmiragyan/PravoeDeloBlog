<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    public User $loggedUser;

    /**
     * @param User|null $user
     *
     * @return $this
     */
    protected function signIn(User $user = null): TestCase
    {
        $this->loggedUser = $user ?: User::factory()->create();

        $this->actingAs($this->loggedUser);

        return $this;
    }
}
