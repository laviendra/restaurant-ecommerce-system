<?php

namespace Tests\Property;

use App\Models\ContactMessage;
use App\Models\User;
use Eris\Generator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * **Feature: restaurant-ecommerce, Property 25: Contact Message Read Status**
 * **Validates: Requirements 16.2**
 *
 * For any contact message marked as read, the is_read status should be persisted in the database.
 */
class ContactMessageReadStatusPropertyTest extends TestCase
{
    use RefreshDatabase, TestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitTo(100);
    }

    /**
     * Property 25: Contact Message Read Status Persistence
     *
     * For any contact message, when an admin views the message detail,
     * the is_read status should be updated to true and persisted in the database.
     */
    public function testMessageReadStatusIsPersisted(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    fn($name) => strlen($name) >= 2 && strlen($name) <= 255,
                    Generator\names()
                ),
                Generator\map(
                    fn($n) => "message{$n}@example.com",
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
                        fn($n) => "Test message content " . $n . " with some text.",
                        Generator\choose(1, 999999)
                    )
                )
            )
            ->then(function (string $name, string $email, string $subject, string $messageText) {
                // Create an admin user
                $admin = User::factory()->create(['role' => 'admin']);

                // Create an unread contact message
                $message = ContactMessage::create([
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $messageText,
                    'is_read' => false,
                ]);

                // Verify message is initially unread
                $this->assertFalse($message->is_read);

                // Admin views the message detail
                $response = $this->actingAs($admin)->get(route('admin.messages.show', $message));
                $response->assertStatus(200);

                // Refresh the message from database
                $message->refresh();

                // Message should now be marked as read
                $this->assertTrue($message->is_read);

                // Verify in database
                $this->assertDatabaseHas('contact_messages', [
                    'id' => $message->id,
                    'is_read' => true,
                ]);
            });
    }

    /**
     * Property 25b: Mark as Read via API Endpoint
     *
     * For any contact message, when the markAsRead endpoint is called,
     * the is_read status should be updated to true.
     */
    public function testMarkAsReadEndpointPersistsStatus(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    fn($name) => strlen($name) >= 2 && strlen($name) <= 255,
                    Generator\names()
                ),
                Generator\map(
                    fn($n) => "api{$n}@example.com",
                    Generator\choose(1, 999999)
                ),
                Generator\suchThat(
                    fn($subject) => strlen($subject) >= 3 && strlen($subject) <= 255,
                    Generator\map(
                        fn($n) => "API Subject " . $n,
                        Generator\choose(1, 999999)
                    )
                )
            )
            ->then(function (string $name, string $email, string $subject) {
                // Create an admin user
                $admin = User::factory()->create(['role' => 'admin']);

                // Create an unread contact message
                $message = ContactMessage::create([
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => 'Test message content for API test.',
                    'is_read' => false,
                ]);

                // Verify message is initially unread
                $this->assertFalse($message->is_read);

                // Call the markAsRead endpoint
                $response = $this->actingAs($admin)->patch(route('admin.messages.mark-as-read', $message));
                $response->assertRedirect();

                // Refresh the message from database
                $message->refresh();

                // Message should now be marked as read
                $this->assertTrue($message->is_read);

                // Verify in database
                $this->assertDatabaseHas('contact_messages', [
                    'id' => $message->id,
                    'is_read' => true,
                ]);
            });
    }

    /**
     * Property 25c: Already Read Messages Stay Read
     *
     * For any contact message that is already read, viewing it again
     * should keep the is_read status as true.
     */
    public function testAlreadyReadMessagesStayRead(): void
    {
        $this
            ->forAll(
                Generator\suchThat(
                    fn($name) => strlen($name) >= 2 && strlen($name) <= 255,
                    Generator\names()
                ),
                Generator\map(
                    fn($n) => "read{$n}@example.com",
                    Generator\choose(1, 999999)
                )
            )
            ->then(function (string $name, string $email) {
                // Create an admin user
                $admin = User::factory()->create(['role' => 'admin']);

                // Create an already read contact message
                $message = ContactMessage::create([
                    'name' => $name,
                    'email' => $email,
                    'subject' => 'Already Read Subject',
                    'message' => 'This message was already read.',
                    'is_read' => true,
                ]);

                // Verify message is initially read
                $this->assertTrue($message->is_read);

                // Admin views the message detail again
                $response = $this->actingAs($admin)->get(route('admin.messages.show', $message));
                $response->assertStatus(200);

                // Refresh the message from database
                $message->refresh();

                // Message should still be marked as read
                $this->assertTrue($message->is_read);

                // Verify in database
                $this->assertDatabaseHas('contact_messages', [
                    'id' => $message->id,
                    'is_read' => true,
                ]);
            });
    }
}
