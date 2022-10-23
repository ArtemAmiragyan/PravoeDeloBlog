<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use WithFaker;

    private const USER_TEST_EMAIL = 'email@email.com';

    /** @test */
    public function it_checks_guest_can_register(): void
    {
        $userRegisterData = User::factory()->make()->toArray();

        $userRegisterData['password'] = $this->faker->password(8);
        $userRegisterData['password_confirmation'] = $userRegisterData['password'];

        $this->postJson('/api/register', $userRegisterData)
            ->assertSuccessful()
            ->assertJsonStructure([
                'token',
                'user',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $userRegisterData['email'],
            'name' => $userRegisterData['name'],
        ]);
    }

    /**
     * @test
     * @dataProvider getInvalidCredentialsDataProvider
     */
    public function it_checks_invalid_credentials_returns_validation_error(array $overrideData, $errorField): void
    {
        User::factory()->create(['email' => static::USER_TEST_EMAIL]);

        $userRegisterData = User::factory()->make($overrideData)->toArray();

        $userRegisterData['password'] = $overrideData['password'] ?? $this->faker->password;
        $userRegisterData['password_confirmation'] = $overrideData['password_confirmation']
            ?? $userRegisterData['password'];

        $this->postJson('/api/register', $userRegisterData)
            ->assertStatus(422)
            ->assertJsonValidationErrorFor($errorField);

        $this->assertDatabaseMissing('users', [
            'email' => $userRegisterData['email'],
            'name' => $userRegisterData['name'],
        ]);
    }

    /** @return array */
    private function getInvalidCredentialsDataProvider(): array
    {
        return [
            'required_email' => [['email' => null], 'email'],
            'invalid_email' => [['email' => Str::random(10)], 'email'],
            'invalid_email_length' => [['email' => Str::random(246) . '@email.com'], 'email'],
            'unique_email' => [['email' => static::USER_TEST_EMAIL], 'email'],

            'required_password' => [['password' => ''], 'password'],
            'invalid_password' => [['password' => true], 'password'],
            'invalid_password_length_max' => [['password' => Str::random(256)], 'password'],
            'invalid_password_length_min' => [['password' => '123'], 'password'],

            'required_password_confirmation' => [
                ['password' => '12345678', 'password_confirmation' => ''],
                'password_confirmation',
            ],

            'invalid_password_confirmation' => [
                ['password' => '12345678', 'password_confirmation' => '123456789'],
                'password_confirmation',
            ],

            'required_name' => [['name' => ''], 'name'],
            'invalid_name' => [['name' => true], 'name'],
            'invalid_name_length_max' => [['name' => Str::random(256)], 'name'],
        ];
    }
}
