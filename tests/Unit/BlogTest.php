<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_blog_with_valid_data()
    {
        // Crea un usuario al que pertenecerá el blog
        $user = User::factory()->create();

        // Crea un blog asociado al usuario
        $blog = Blog::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Blog Title',
            'description' => 'This is a test description for the blog.',
            'image_url' => 'https://example.com/image.jpg',
        ]);

        // Verifica que el blog se creó en la base de datos
        $this->assertDatabaseHas('blogs', [
            'title' => 'Test Blog Title',
            'description' => 'This is a test description for the blog.',
            'image_url' => 'https://example.com/image.jpg',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        // Crea un usuario y un blog asociado a ese usuario
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);

        // Verifica la relación
        $this->assertInstanceOf(User::class, $blog->user);
        $this->assertEquals($user->id, $blog->user->id);
    }
}
