<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Media;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cliente_puede_crear_reserva()
    {
        $user = User::factory()->create();
        $media = Media::factory()->create(['price_per_day' => 100]);

        $response = $this->actingAs($user)->postJson('/api/reservations', [
            'media_id' => $media->id,
            'start_date' => now()->addDays(1)->toDateString(),
            'end_date' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertStatus(200)
                    ->assertJsonStructure(['message', 'total']);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'media_id' => $media->id,
        ]);
    }
}
