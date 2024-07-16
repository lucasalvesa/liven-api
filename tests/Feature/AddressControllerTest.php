<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\Address;

class AddressControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Cria um usuário de teste
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        
        // Gera um token JWT para o usuário
        $this->token = JWTAuth::fromUser($this->user);

        // Cria alguns endereços de teste para o usuário
        $this->addresses = Address::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'country' => 'BR',
            'city' => 'Manaus',
            'state' => 'Amazonas',
        ]);
    }

    public function test_user_can_view_all_addresses()
    {
        // Fazer a requisição para obter todos os endereços
        $response = $this->getJson('/api/addresses', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Verificar se a resposta está correta
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_user_can_view_addresses_filtered_by_country()
    {
        // Fazer a requisição para obter endereços filtrados por país
        $response = $this->getJson('/api/addresses?country=BR', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Verificar se a resposta está correta
        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_user_can_view_address_by_id()
    {
        $address = $this->addresses->first();

        // Fazer a requisição para obter um endereço específico por ID
        $response = $this->getJson('/api/address/' . $address->id, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Verificar se a resposta está correta
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $address->id,
                     'country' => 'BR',
                 ]);
    }

    public function test_user_can_create_address()
    {
        $addressData = [
            'street' => 'Avenida Paulista 1000',
            'city' => 'Salvador',
            'state' => 'Bahia',
            'country' => 'BR',
            'postal_code' => '98765',
        ];

        // Fazer a requisição para criar um novo endereço
        $response = $this->postJson('/api/address', $addressData, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Verificar se a resposta está correta
        $response->assertStatus(201)
                 ->assertJson($addressData);

        // Verificar se o endereço foi criado no banco de dados
        $this->assertDatabaseHas('addresses', $addressData);
    }

    public function test_user_can_update_address()
    {
        $address = $this->addresses->first();
        $updatedData = [
            'street' => 'Rua 7 de Setembro 200',
            'city' => 'São Carlos',
            'state' => 'São Paulo',
            'country' => 'BR',
            'postal_code' => '90001',
        ];

        // Fazer a requisição para atualizar um endereço
        $response = $this->putJson('/api/address/' . $address->id, $updatedData, [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Verificar se a resposta está correta
        $response->assertStatus(200)
                 ->assertJson($updatedData);

        // Verificar se o endereço foi atualizado no banco de dados
        $this->assertDatabaseHas('addresses', $updatedData);
    }

    public function test_user_can_delete_address()
    {
        $address = $this->addresses->first();

        // Fazer a requisição para deletar um endereço
        $response = $this->deleteJson('/api/address/' . $address->id, [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Verificar se a resposta está correta
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Liven API informs: Address deleted successfully',
                 ]);

        // Verificar se o endereço foi removido do banco de dados
        $this->assertDatabaseMissing('addresses', [
            'id' => $address->id,
        ]);
    }
}
