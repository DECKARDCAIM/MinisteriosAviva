<?php

namespace Tests\Feature;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_valid_email()
    {
        // Intenta enviar un correo no válido
        $response = $this->postJson('/subscriber', [
            'email' => 'correo-no-valido',
        ]);

        // Verifica que se reciba un error en la validación
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_requires_a_unique_email()
    {
        // Crea un suscriptor en la base de datos
        Subscriber::create(['email' => 'test@example.com']);

        // Intenta registrar el mismo correo nuevamente
        $response = $this->postJson('/subscriber', [
            'email' => 'test@example.com',
        ]);

        // Verifica que se reciba un error en la validación de unicidad
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
public function it_creates_a_subscriber()
{
    // Datos de prueba para un correo válido
    $emailData = ['email' => 'nuevo_suscriptor@example.com'];

    // Realiza la petición de creación de suscriptor
    $response = $this->postJson('/subscriber', $emailData);

    // Verifica que la respuesta sea exitosa
    $response->assertStatus(200);
    $response->assertJson(['message' => '¡Gracias por suscribirte!']);

    // Confirma que el suscriptor fue creado en la base de datos
    $this->assertDatabaseHas('subscribers', $emailData);
}

}
