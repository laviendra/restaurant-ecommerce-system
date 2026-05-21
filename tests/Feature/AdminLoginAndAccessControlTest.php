<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Test Admin Login and Access Control
 * 
 * Tests the complete admin login flow and access control mechanisms
 * to ensure admin users can access admin routes and non-admin users cannot.
 * 
 * Requirements: 1.2, 1.3, 4.5
 */
class AdminLoginAndAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user (matching seeder credentials)
        $this->adminUser = User::create([
            'name' => 'Admin McD',
            'email' => 'admin@mcd.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create regular user
        $this->regularUser = User::create([
            'name' => 'Test User',
            'email' => 'user@mcd.com',
            'password' => Hash::make('user123'),
            'phone' => '081234567891',
            'address' => 'Jakarta, Indonesia',
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Test admin login with correct credentials
     * Requirements: 1.2 - Admin user should be able to login and redirect to admin dashboard
     */
    public function testAdminLoginWithValidCredentials(): void
    {
        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/login', [
                'email' => 'admin@mcd.com',
                'password' => 'admin123',
            ]);

        // Should redirect to home page (Laravel default after login)
        $response->assertRedirect('/');
        
        // User should be authenticated as admin
        $this->assertAuthenticatedAs($this->adminUser);
        
        // Verify user is admin
        $this->assertTrue(auth()->user()->isAdmin());
    }

    /**
     * Test admin can access admin dashboard after login
     * Requirements: 1.2 - Admin should redirect to admin dashboard
     */
    public function testAdminCanAccessDashboardAfterLogin(): void
    {
        // Login as admin
        $this->actingAs($this->adminUser);

        // Access admin dashboard
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /**
     * Test non-admin user cannot access admin routes
     * Requirements: 1.3 - Non-admin users should get 403 error
     */
    public function testNonAdminUserCannotAccessAdminRoutes(): void
    {
        // Login as regular user
        $this->actingAs($this->regularUser);

        // Try to access admin dashboard
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(403);
        $response->assertSee('Unauthorized. Admin access required.');
    }

    /**
     * Test guest user cannot access admin routes
     * Requirements: 1.3 - Unauthenticated users should be redirected to login
     */
    public function testGuestUserCannotAccessAdminRoutes(): void
    {
        // Try to access admin dashboard without authentication
        $response = $this->get('/admin/dashboard');

        // Should redirect to login page
        $response->assertRedirect('/login');
    }

    /**
     * Test admin middleware protects all admin routes
     * Requirements: 4.5 - Admin middleware should protect admin routes
     */
    public function testAdminMiddlewareProtectsAllAdminRoutes(): void
    {
        $adminRoutes = [
            '/admin/dashboard',
            '/admin/products',
            '/admin/orders',
            '/admin/users',
            '/admin/messages',
            '/admin/reports',
        ];

        // Test with regular user
        $this->actingAs($this->regularUser);

        foreach ($adminRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(403, "Route {$route} should be protected from regular users");
        }

        // Test with admin user
        $this->actingAs($this->adminUser);

        foreach ($adminRoutes as $route) {
            $response = $this->get($route);
            // Should not be 403 (could be 200 or other valid response)
            $response->assertStatus(200, "Route {$route} should be accessible to admin users");
        }
    }

    /**
     * Test admin login with invalid credentials fails
     * Requirements: 1.2 - Invalid credentials should not authenticate
     */
    public function testAdminLoginWithInvalidCredentialsFails(): void
    {
        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/login', [
                'email' => 'admin@mcd.com',
                'password' => 'wrongpassword',
            ]);

        // Should have validation errors
        $response->assertSessionHasErrors('email');
        
        // Should not be authenticated
        $this->assertGuest();
    }

    /**
     * Test admin middleware correctly identifies admin users
     * Requirements: 4.5 - Admin middleware should work correctly
     */
    public function testAdminMiddlewareCorrectlyIdentifiesAdminUsers(): void
    {
        // Test that admin user passes middleware
        $this->actingAs($this->adminUser);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);

        // Test that regular user fails middleware
        $this->actingAs($this->regularUser);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);

        // Test that guest user is redirected to login
        auth()->logout();
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test User model isAdmin() method works correctly
     * Requirements: 4.5 - User model should correctly identify admin users
     */
    public function testUserModelIsAdminMethodWorksCorrectly(): void
    {
        // Admin user should return true
        $this->assertTrue($this->adminUser->isAdmin());
        
        // Regular user should return false
        $this->assertFalse($this->regularUser->isAdmin());
        
        // User with no role should return false
        $userWithoutRole = User::create([
            'name' => 'No Role User',
            'email' => 'norole@test.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'address' => 'Test Address',
            // role is null by default
        ]);
        
        $this->assertFalse($userWithoutRole->isAdmin());
    }
}