<?php

namespace Tests\Property;

use App\Models\User;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 2: Authentication with Valid Credentials**
 * **Validates: Requirements 1.3**
 *
 * For any registered user with valid credentials,
 * submitting the login form should result in an authenticated session.
 */
class AuthenticationPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(50);
    }

    /**
     * Property 2: Authentication with Valid Credentials
     *
     * For any registered user with valid credentials,
     * submitting the login form should result in an authenticated session.
     */
    public function testAuthenticationWithValidCredentialsCreatesSession(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999),
                Generator\choose(1000, 9999)
            )
            ->then(function (int $emailSuffix, int $passSuffix) {
                $email = "authuser{$emailSuffix}@example.com";
                $password = "TestPass{$passSuffix}!";

                // Create user with known password
                $user = User::create([
                    'name' => 'Test User',
                    'email' => $email,
                    'phone' => '+1234567890',
                    'password' => Hash::make($password),
                    'role' => 'user',
                ]);

                // Attempt login
                $response = $this->post('/login', [
                    'email' => $email,
                    'password' => $password,
                ]);

                // Should redirect (successful login)
                $response->assertRedirect('/');

                // User should be authenticated
                $this->assertAuthenticatedAs($user);

                // Logout for next iteration
                $this->post('/logout');
            });
    }

    /**
     * Property 2 (Inverse): Invalid credentials should not authenticate
     *
     * For any registered user, attempting to login with wrong password
     * should not create an authenticated session.
     */
    public function testAuthenticationWithInvalidCredentialsFails(): void
    {
        $this
            ->forAll(
                Generator\choose(1, 999999),
                Generator\choose(1000, 4999),
                Generator\choose(5000, 9999)
            )
            ->then(function (int $emailSuffix, int $correctPassSuffix, int $wrongPassSuffix) {
                $email = "invalidauth{$emailSuffix}@example.com";
                $correctPassword = "CorrectPass{$correctPassSuffix}!";
                $wrongPassword = "WrongPass{$wrongPassSuffix}!";

                // Create user with correct password
                User::create([
                    'name' => 'Test User',
                    'email' => $email,
                    'phone' => '+1234567890',
                    'password' => Hash::make($correctPassword),
                    'role' => 'user',
                ]);

                // Attempt login with wrong password
                $response = $this->post('/login', [
                    'email' => $email,
                    'password' => $wrongPassword,
                ]);

                // Should have validation error
                $response->assertSessionHasErrors('email');

                // User should not be authenticated
                $this->assertGuest();
            });
    }
}
