<?php

namespace Tests\Feature;

use Database\Seeders\DemoContentSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            RolePermissionSeeder::class,
            UserSeeder::class,
            DemoContentSeeder::class,
        ]);
    }

    public function test_home_page_loads(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_blog_index_loads(): void
    {
        $this->get('/blog')->assertStatus(200);
    }

    public function test_services_index_loads(): void
    {
        $this->get('/services')->assertStatus(200);
    }

    public function test_contact_page_loads(): void
    {
        $this->get('/contact')->assertStatus(200);
    }

    public function test_sitemap_returns_xml(): void
    {
        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));
        $response->assertSee('<urlset', false);
    }

    public function test_contact_form_creates_message(): void
    {
        $this->post('/contact', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Hello',
            'message' => 'This is a sample message body.',
        ])->assertRedirect('/contact')->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'john@example.com',
            'subject' => 'Hello',
        ]);
    }

    public function test_published_article_is_accessible(): void
    {
        $this->get('/blog/sample-article-1')->assertStatus(200);
    }
}
