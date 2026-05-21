<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardNavigationTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->adminUser = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin'
        ]);
        
        // Create regular user
        $this->regularUser = User::factory()->create([
            'role' => 'user'
        ]);
    }

    /** @test */
    public function admin_dashboard_loads_correctly()
    {
        // Create some test data for dashboard metrics
        Order::factory()->count(5)->create();
        Product::factory()->count(3)->create();
        ContactMessage::factory()->count(2)->create(['is_read' => false]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        
        // Verify dashboard contains key metrics
        $response->assertSee('Dashboard');
        $response->assertSee('Total Orders');
        $response->assertSee('Total Revenue');
        $response->assertSee('Pending Orders');
        $response->assertSee('Total Users');
    }

    /** @test */
    public function admin_navigation_links_are_present()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        
        // Verify all required navigation links are present
        $response->assertSee('Dashboard');
        $response->assertSee('Products');
        $response->assertSee('Orders');
        $response->assertSee('Users');
        $response->assertSee('Messages');
        $response->assertSee('Reports');
        
        // Verify navigation links have correct routes
        $response->assertSee(route('admin.dashboard'));
        $response->assertSee(route('admin.products.index'));
        $response->assertSee(route('admin.orders.index'));
        $response->assertSee(route('admin.users.index'));
        $response->assertSee(route('admin.messages.index'));
        $response->assertSee(route('admin.reports.index'));
    }

    /** @test */
    public function admin_can_navigate_between_admin_pages()
    {
        // Test navigation to Products page
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.products.index'));
        $response->assertStatus(200);

        // Test navigation to Orders page
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.orders.index'));
        $response->assertStatus(200);

        // Test navigation to Users page
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.users.index'));
        $response->assertStatus(200);

        // Test navigation to Messages page
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.messages.index'));
        $response->assertStatus(200);

        // Test navigation to Reports page
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.reports.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function active_navigation_highlighting_works()
    {
        // Test Dashboard active state
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));
        $response->assertSee('nav-link active', false);

        // Test Products active state - check for active class on products link
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.products.index'));
        $response->assertSee('class="nav-link active"', false);

        // Test Orders active state - check for active class on orders link
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.orders.index'));
        $response->assertSee('class="nav-link active"', false);

        // Test Users active state - check for active class on users link
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.users.index'));
        $response->assertSee('class="nav-link active"', false);

        // Test Messages active state - check for active class on messages link
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.messages.index'));
        $response->assertSee('class="nav-link active"', false);

        // Test Reports active state - check for active class on reports link
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.reports.index'));
        $response->assertSee('class="nav-link active"', false);
    }

    /** @test */
    public function non_admin_users_cannot_access_admin_dashboard()
    {
        $response = $this->actingAs($this->regularUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    /** @test */
    public function non_admin_users_cannot_access_admin_pages()
    {
        $adminRoutes = [
            'admin.dashboard',
            'admin.products.index',
            'admin.orders.index',
            'admin.users.index',
            'admin.messages.index',
            'admin.reports.index'
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($this->regularUser)
                ->get(route($route));
            
            $response->assertStatus(403);
        }
    }

    /** @test */
    public function unauthenticated_users_are_redirected_to_login()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function admin_layout_includes_proper_styling_and_structure()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        
        // Verify admin layout structure
        $response->assertSee('McD Admin');
        $response->assertSee('sidebar');
        $response->assertSee('fas fa-tachometer-alt'); // Dashboard icon
        $response->assertSee('fas fa-hamburger'); // Products icon
        $response->assertSee('fas fa-shopping-cart'); // Orders icon
        $response->assertSee('fas fa-users'); // Users icon
        $response->assertSee('fas fa-envelope'); // Messages icon
        $response->assertSee('fas fa-chart-bar'); // Reports icon
    }

    /** @test */
    public function dashboard_displays_correct_metrics()
    {
        // Create test data
        $orders = Order::factory()->count(3)->create([
            'payment_status' => 'paid',
            'total_amount' => 100000
        ]);
        
        $pendingOrders = Order::factory()->count(2)->create([
            'order_status' => 'pending'
        ]);
        
        $products = Product::factory()->count(5)->create();
        $users = User::factory()->count(4)->create(['role' => 'user']);
        $unreadMessages = ContactMessage::factory()->count(3)->create(['is_read' => false]);

        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        
        // Verify metrics are displayed (check for the numbers in formatted form)
        $response->assertSee('5'); // Total orders (3 + 2)
        $response->assertSee('2'); // Pending orders  
        $response->assertSee('5'); // Total products
        $response->assertSee('4'); // Total users (excluding admin)
        
        // Check for revenue display (formatted with Rp and commas)
        $response->assertSee('Rp');
        $response->assertSee('300.000'); // Total revenue formatted
    }
}