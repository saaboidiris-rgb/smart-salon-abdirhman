<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_loads_successfully(): void
    {
        $this->get(route('home'))->assertOk();
    }

    public function test_services_page_lists_active_services(): void
    {
        $category = Category::factory()->create();
        $service = Service::factory()->create(['category_id' => $category->id, 'status' => 'active']);

        $this->get(route('services.index'))
            ->assertOk()
            ->assertSee($service->name);
    }

    public function test_guest_can_view_login_and_register_pages(): void
    {
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
    }

    public function test_demo_admin_credentials_are_seeded(): void
    {
        $this->seed(UserSeeder::class);

        $user = User::query()->where('email', 'admin@smartsalon.test')->first();

        $this->assertNotNull($user);
        $this->assertSame(User::ROLE_ADMIN, $user->role);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function test_guest_is_redirected_away_from_customer_dashboard(): void
    {
        $this->get(route('customer.dashboard'))->assertRedirect(route('login'));
    }

    public function test_guest_is_redirected_away_from_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }
}
