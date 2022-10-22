<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Throwable;

class LoginControllerTest extends TestCase
{
    private const USER_TEST_EMAIL = 'email@email.com';
    private const USER_TEST_PASSWORD = 'password';

    /**
     * @test
     * @throws Throwable
     */
    public function it_checks_user_can_sign_in()
    {
        $user = User::factory()->create(['password' => bcrypt(static::USER_TEST_PASSWORD)]);
        $credentials = ['email' => $user->email, 'password' => static::USER_TEST_PASSWORD];

        ['token' => $token] = $this->postJson('/api/login', $credentials)
            ->assertSuccessful()
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name'],
            ])
            ->decodeResponseJson();

        $this->getJson('/api/user', ['Authorization' => "Bearer {$token}"])
            ->assertSuccessful()
            ->assertJsonStructure([
                'id',
                'name',
            ])
            ->assertJson([
                 'id' => $user->id,
            ]);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_checks_unauthorized_user_can_not_get_user()
    {
        Auth::logout();

        $this->getJson('/api/user')
            ->assertUnauthorized();
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_checks_authorized_user_can_not_get_user()
    {
        $this->signIn();

        $this->getJson('/api/user')
            ->assertSuccessful()
            ->assertJsonStructure([
                'id',
                'name',
            ])
            ->assertJson([
                'id' => $this->loggedUser->id,
            ]);
    }

    /**
     * @test
     * @throws Throwable
     */
    public function it_checks_unauthorized_user_can_not_logout()
    {
        Auth::logout();

        $this->postJson('/api/logout')
            ->assertUnauthorized();
    }

    /**
     * @test
     * @dataProvider getInvalidCredentialsDataProvider
     */
    public function it_can_not_sign_in_with_invalid_cred(array $credentials)
    {
        User::factory()->create(['email' => static::USER_TEST_EMAIL, 'password' => bcrypt(static::USER_TEST_PASSWORD)]);

        $this->postJson('/api/login', $credentials)
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('email');
    }

    /** @return array */
    private function getInvalidCredentialsDataProvider(): array
    {
        return [
            'required' => [[null, null]],
            'invalid_password' => [[static::USER_TEST_EMAIL, 'invalid_password']],
            'invalid_email' => [['invalid@some.com', static::USER_TEST_PASSWORD]],
            'invalid_email_format' => [['invalid', static::USER_TEST_PASSWORD]],
        ];
    }
}
