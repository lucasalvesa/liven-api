<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class UserControllerTest extends TestCase
{
    /**
     * O Laravel cria o teste usando o trait RefreshDatabase.
     * Porém a cada execução das suites de teste, esse trait apaga e recria o banco de dados todo do zero.
     * Aqui então optei por usar o trait DatabaseTransactions para envolver cada teste em uma transação de banco de dados.
     * As transações são revertidas ao final de cada teste, garantindo que nenhuma alteração seja persistida.
     */
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // Cria um usuário de teste
        $this->user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        // Gera um token JWT para o usuário
        $this->token = JWTAuth::fromUser($this->user);
    }

    public function test_user_can_view_their_profile()
    {
        // Fazer a requisição para o endpoint de visualização de perfil com o token JWT
        $response = $this->getJson('/api/user', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Verificar se a resposta está correta
        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->user->id,
                'email' => $this->user->email,
            ]);
    }

    public function test_user_can_update_their_profile()
    {
        // Fazer a requisição para o endpoint de atualização de perfil com o token JWT
        $response = $this->putJson('/api/user', [
            'name' => 'New Name',
            'email' => 'newemail@example.com',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Verificar se a resposta está correta
        $response->assertStatus(200)
            ->assertJson([
                'name' => 'New Name',
                'email' => 'newemail@example.com',
            ]);

        // Verificar se os dados foram atualizados no banco de dados
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'New Name',
            'email' => 'newemail@example.com',
        ]);
    }

    public function test_user_can_delete_their_account()
    {
        // Fazer a requisição para o endpoint de exclusão de conta com o token JWT
        $response = $this->deleteJson('/api/user', [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        // Verificar se a resposta está correta
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Liven API informs: User deleted successfully',
            ]);

        // Verificar se o usuário foi deletado do banco de dados
        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
        ]);
    }
}
