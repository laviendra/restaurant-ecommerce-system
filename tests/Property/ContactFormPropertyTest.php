<?php

namespace Tests\Property;

use App\Models\ContactMessage;
use App\Models\User;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 19: Contact Form Message Persistence**
 * **Validates: Requirements 9.4**
 *
 * For any contact form submission, the message should be saved in the database.
 */
class ContactFormPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(100);
    }

    /**
     * Property 19: Contact Form Message Persistence
     *
     * For any valid contact form submission, the message should be saved
     * in the database with all provided information.
     */
    public function testContactFormMessageIsPersisted(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    fn($name) => strlen($name) >= 2 && strlen($name) <= 255,
                    Generator\names()
                ),
                Generator\map(
                    fn($n) => "contact{$n}@example.com",
                    Generator\choose(1, 999999)
                ),
                Generator\suchThat(
                    fn($subject) => strlen($subject) >= 3 && strlen($subject) <= 255,
                    Generator\map(
                        fn($n) => "Subject " . $n,
                        Generator\choose(1, 999999)
                    )
                ),
                Generator\suchThat(
                    fn($message) => strlen($message) >= 10 && strlen($message) <= 5000,
                    Generator\map(
                        fn($n) => "This is a test message number " . $n . " with some content.",
                        Generator\choose(1, 999999)
                    )
                )
            )
            ->then(function (string $name, string $email, string $subject, string $message) {
                $initialCount = ContactMessage::count();

                $response = $this->post(route('contact.store'), [
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                ]);

                // Should redirect back with success message
                $response->assertRedirect(route('contact.index'));
                $response->assertSessionHas('success');

                // Message count should have increased by 1
                $this->assertEquals($initialCount + 1, ContactMessage::count());

                // Message should exist in database with correct data
                $this->assertDatabaseHas('contact_messages', [
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                    'is_read' => false,
                ]);
            });
    }

    /**
     * Property 19b: Authenticated user contact form associates user_id
     *
     * For any authenticated user submitting a contact form,
     * the message should be associated with their user_id.
     */
    public function testAuthenticatedUserContactFormAssociatesUserId(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    fn($subject) => strlen($subject) >= 3 && strlen($subject) <= 255,
                    Generator\map(
                        fn($n) => "Auth Subject " . $n,
                        Generator\choose(1, 999999)
                    )
                ),
                Generator\suchThat(
                    fn($message) => strlen($message) >= 10 && strlen($message) <= 5000,
                    Generator\map(
                        fn($n) => "Authenticated user message " . $n . " with content.",
                        Generator\choose(1, 999999)
                    )
                )
            )
            ->then(function (string $subject, string $message) {
                // Create and authenticate a user
                $user = User::factory()->create();

                $response = $this->actingAs($user)->post(route('contact.store'), [
                    'name' => $user->name,
                    'email' => $user->email,
                    'subject' => $subject,
                    'message' => $message,
                ]);

                // Should redirect back with success message
                $response->assertRedirect(route('contact.index'));

                // Message should be associated with the user
                $this->assertDatabaseHas('contact_messages', [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'subject' => $subject,
                    'message' => $message,
                ]);
            });
    }

    /**
     * Property 19c: Contact form validation rejects empty required fields
     *
     * For any contact form submission with empty required fields,
     * the submission should fail and no message should be saved.
     */
    public function testContactFormValidationRejectsEmptyFields(): void
    {
        $this
            ->forAll(
                Generator\choose(0, 3) // Which field to make empty
            )
            ->then(function (int $emptyField) {
                $initialCount = ContactMessage::count();

                $data = [
                    'name' => 'Valid Name',
                    'email' => 'valid@example.com',
                    'subject' => 'Valid Subject',
                    'message' => 'This is a valid message with enough content.',
                ];

                // Make one field empty based on the random choice
                $fields = ['name', 'email', 'subject', 'message'];
                $data[$fields[$emptyField]] = '';

                $response = $this->post(route('contact.store'), $data);

                // Should have validation errors
                $response->assertSessionHasErrors($fields[$emptyField]);

                // No new message should be created
                $this->assertEquals($initialCount, ContactMessage::count());
            });
    }
}
