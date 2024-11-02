<?php

namespace Tests\Feature;

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_a_list_of_blogs_in_json_format()
    {
        // Crear algunos blogs de prueba
        Blog::factory()->count(3)->create();

        // Llamar al método index y verificar la respuesta
        $response = $this->getJson('/blogs');

        // Comprobar que el estado es 200 y que el JSON contiene los blogs
        $response->assertStatus(200);
        $response->assertJsonCount(3); // Verifica que se devuelvan los 3 blogs
    }

    /** @test */
public function it_returns_a_specific_blog_by_id()
{
    // Crear un blog de prueba
    $blog = Blog::factory()->create();

    // Llamar al método show con el ID del blog
    $response = $this->getJson("/blogs/{$blog->id}");

    // Verificar que la respuesta sea 200 y contenga los datos del blog
    $response->assertStatus(200);
    $response->assertJson([
        'id' => $blog->id,
        'title' => $blog->title,
        'description' => $blog->description,
        'image_url' => $blog->image_url,
    ]);
}

/** @test */
public function it_returns_404_if_blog_not_found()
{
    // Llamar al método show con un ID inexistente
    $response = $this->getJson('/blogs/999');

    // Verificar que la respuesta sea 404 y contenga el mensaje de error
    $response->assertStatus(404);
    $response->assertJson(['message' => 'Blog not found']);
}

}
