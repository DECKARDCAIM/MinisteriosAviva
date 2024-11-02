<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_name_email_subject_and_message()
    {
        // Envía una solicitud vacía para ver si se activan las validaciones
        $response = $this->postJson('/contact', []);

        // Verifica que los campos obligatorios muestren errores de validación
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'email', 'subject', 'message']);
    }

    /** @test */
    public function it_requires_a_valid_email()
    {
        // Envía un correo no válido
        $response = $this->postJson('/contact', [
            'name' => 'Test User',
            'email' => 'correo-no-valido',
            'subject' => 'Consulta',
            'message' => 'Este es un mensaje de prueba',
        ]);

        // Verifica que el campo 'email' muestre error de validación
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
public function it_creates_a_contact_message()
{
    // Datos de prueba para un mensaje válido
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'subject' => 'Consulta',
        'message' => 'Este es un mensaje de prueba',
    ];

    // Realiza la petición para crear el mensaje
    $response = $this->postJson('/contact', $data);

    // Verifica que la respuesta sea exitosa
    $response->assertStatus(200);
    $response->assertJson(['success' => '¡Mensaje enviado con éxito!']);

    // Confirma que el mensaje fue creado en la base de datos
    $this->assertDatabaseHas('contact_messages', $data);
}

}
