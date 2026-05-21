<?php

namespace Tests\Property;

use App\Models\User;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 1: User Registration Creates Account**
 * **Validates: Requirements 1.1**
 *
 * For any valid registration data (email, password, name, phone),
 * submitting the registration form should result in a new user record
 * in the database with matching information.
 */
class UserRegistrationPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(100);
    }

    /**
     * Property 1: User Registration Creates Account
     *
     * For any valid registration data, submitting the registration form
     * should result in a new user record in the database with matching information.
     */
    public function testUserRegistrationCreatesAccountWithMatchingData(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    fn($name) => strlen($name) >= 2 && strlen($name) <= 255,
                    Generator\names()
                ),
                Generator\map(
                    fn($n) => "user{$n}@example.com",
                    Generator\choose(1, 999999)
                ),
                Generator\suchThat(
                    fn($phone) => strlen($phone) >= 5 && strlen($phone) <= 20,
                    Generator\map(
                        fn($n) => '+1' . str_pad((string)$n, 10, '0', STR_PAD_LEFT),
                        Generator\choose(1000000000, 9999999999)
                    )
                ),
                Generator\suchThat(
                    fn($pass) => strlen($pass) >= 8,
                    Generator\map(
                        fn($n) => 'Password' . $n . '!',
                        Generator\choose(1000, 9999)
                    )
                )
            )
            ->then(function (string $name, string $email, string $phone, string $password) {
                // Ensure email is unique for this test
                $email = uniqid() . $email;

                $response = $this->post('/register', [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => $password,
                    'password_confirmation' => $password,
                ]);

                // Should redirect to login on success
                $response->assertRedirect(route('login'));

                // User should exist in database
                $this->assertDatabaseHas('users', [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'role' => 'user',
                ]);

                // Verify password was hashed correctly
                $user = User::where('email', $email)->first();
                $this->assertNotNull($user);
                $this->assertTrue(Hash::check($password, $user->password));
            });
    }

    /**
     * Property 1 (Edge Case): Duplicate email registration should fail
     *
     * For any existing user email, attempting to register with the same email
     * should fail and not create a new user.
     */
    public function testDuplicateEmailRegistrationFails(): void
    {
        $this
            ->forAll(
                Generator\names(),
                Generator\map(
                    fn($n) => "duplicate{$n}@example.com",
                    Generator\choose(1, 999999)
                )
            )
            ->then(function (string $name, string $email) {
                // Create existing user
                $existingUser = User::factory()->create(['email' => $email]);
                $initialCount = User::count();

                // Attempt to register with same email
                $response = $this->post('/register', [
                    'name' => $name,
                    'email' => $email,
                    'phone' => '+1234567890',
                    'password' => 'Password123!',
                    'password_confirmation' => 'Password123!',
                ]);

                // Should have validation error
                $response->assertSessionHasErrors('email');

                // User count should not have increased
                $this->assertEquals($initialCount, User::count());
            });
    }
}
