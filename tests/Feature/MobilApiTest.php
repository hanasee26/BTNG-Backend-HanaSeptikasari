<?php

namespace Tests\Feature;

use App\Models\Mobil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MobilApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_can_get_list_of_all_cars(): void
    {
        $response = $this->getJson('/api/mobil');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'nama',
                        'brand',
                        'harga',
                        'tahun',
                        'transmisi',
                        'bahan_bakar',
                        'status',
                        'foto',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ])
            ->assertJson([
                'message' => 'Daftar mobil berhasil diambil',
            ]);

        $this->assertGreaterThanOrEqual(10, count($response->json('data')));
    }

    public function test_can_search_cars_by_name(): void
    {
        $response = $this->getJson('/api/mobil?search=Avanza');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        $this->assertStringContainsString('Avanza', $data[0]['nama']);
    }

    public function test_can_filter_cars_by_brand_and_status(): void
    {
        $response = $this->getJson('/api/mobil?brand=Toyota&status=tersedia');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertNotEmpty($data);
        foreach ($data as $item) {
            $this->assertEquals('Toyota', $item['brand']);
            $this->assertEquals('tersedia', $item['status']);
        }
    }

    public function test_can_get_single_car_by_id(): void
    {
        $mobil = Mobil::first();

        $response = $this->getJson("/api/mobil/{$mobil->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Detail mobil berhasil diambil',
                'data' => [
                    'id' => $mobil->id,
                    'nama' => $mobil->nama,
                    'brand' => $mobil->brand,
                ],
            ]);
    }

    public function test_returns_404_when_car_not_found(): void
    {
        $response = $this->getJson('/api/mobil/99999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Data mobil tidak ditemukan',
            ]);
    }

    public function test_can_create_new_car(): void
    {
        $payload = [
            'nama' => 'Yaris Cross 1.5 S HV',
            'brand' => 'Toyota',
            'harga' => 440000000,
            'tahun' => 2024,
            'transmisi' => 'CVT',
            'bahan_bakar' => 'Hybrid',
            'status' => 'tersedia',
            'foto' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341',
        ];

        $response = $this->postJson('/api/mobil', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Data mobil berhasil ditambahkan',
                'data' => [
                    'nama' => 'Yaris Cross 1.5 S HV',
                    'brand' => 'Toyota',
                    'harga' => 440000000,
                ],
            ]);

        $this->assertDatabaseHas('mobils', [
            'nama' => 'Yaris Cross 1.5 S HV',
            'brand' => 'Toyota',
        ]);
    }

    public function test_create_validation_fails_with_invalid_data(): void
    {
        $response = $this->postJson('/api/mobil', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nama', 'brand', 'harga', 'tahun', 'transmisi', 'bahan_bakar', 'status', 'foto']);
    }

    public function test_can_update_existing_car(): void
    {
        $mobil = Mobil::first();

        $payload = [
            'nama' => 'Avanza 1.5 G CVT Facelift',
            'brand' => 'Toyota',
            'harga' => 285000000,
            'tahun' => 2024,
            'transmisi' => 'CVT',
            'bahan_bakar' => 'Bensin',
            'status' => 'tersedia',
            'foto' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341',
        ];

        $response = $this->putJson("/api/mobil/{$mobil->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data mobil berhasil diperbarui',
                'data' => [
                    'id' => $mobil->id,
                    'nama' => 'Avanza 1.5 G CVT Facelift',
                    'harga' => 285000000,
                ],
            ]);

        $this->assertDatabaseHas('mobils', [
            'id' => $mobil->id,
            'nama' => 'Avanza 1.5 G CVT Facelift',
        ]);
    }

    public function test_can_patch_existing_car_partially(): void
    {
        $mobil = Mobil::first();

        $payload = [
            'status' => 'sold',
        ];

        $response = $this->patchJson("/api/mobil/{$mobil->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data mobil berhasil diperbarui',
                'data' => [
                    'id' => $mobil->id,
                    'status' => 'sold',
                ],
            ]);

        $this->assertDatabaseHas('mobils', [
            'id' => $mobil->id,
            'status' => 'sold',
        ]);
    }

    public function test_update_returns_404_when_car_not_found(): void
    {
        $response = $this->putJson('/api/mobil/99999', [
            'nama' => 'Unknown',
            'brand' => 'Unknown',
            'harga' => 100000000,
            'tahun' => 2024,
            'transmisi' => 'Manual',
            'bahan_bakar' => 'Bensin',
            'status' => 'tersedia',
            'foto' => 'https://example.com/car.jpg',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Data mobil tidak ditemukan',
            ]);
    }

    public function test_can_delete_car(): void
    {
        $mobil = Mobil::first();

        $response = $this->deleteJson("/api/mobil/{$mobil->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data mobil berhasil dihapus',
            ]);

        $this->assertDatabaseMissing('mobils', [
            'id' => $mobil->id,
        ]);
    }

    public function test_delete_returns_404_when_car_not_found(): void
    {
        $response = $this->deleteJson('/api/mobil/99999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Data mobil tidak ditemukan',
            ]);
    }

    public function test_scramble_docs_endpoint_is_accessible(): void
    {
        $response = $this->get('/docs/api');
        $response->assertStatus(200);

        $jsonResponse = $this->get('/docs/api.json');
        $jsonResponse->assertStatus(200);
        $jsonResponse->assertJsonStructure(['openapi', 'info', 'paths']);
    }
}
