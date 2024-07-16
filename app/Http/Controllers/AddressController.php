<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;

/**
 * @OA\Tag(
 *     name="Address",
 *     description="Endpoints da API para Address"
 * )
 */
class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/addresses",
     *     summary="Obter todos os endereços do usuário logado",
     *     tags={"Address"},
     *     @OA\Parameter(
     *         name="country",
     *         in="query",
     *         description="Filtrar por país",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="city",
     *         in="query",
     *         description="Filtrar por cidade",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="state",
     *         in="query",
     *         description="Filtrar por estado",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de endereços",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=23),
     *                 @OA\Property(property="street", type="string", example="Rua Principal 123"),
     *                 @OA\Property(property="city", type="string", example="Belo Horizonte"),
     *                 @OA\Property(property="state", type="string", example="Minas Gerais"),
     *                 @OA\Property(property="country", type="string", example="BR"),
     *                 @OA\Property(property="postal_code", type="string", example="12345"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z")
     *             )
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->addresses();

        $filters = ['country', 'city', 'state'];

        // Iterar sobre os filtros e aplicar à query
        foreach ($filters as $filter) {
            if ($request->has($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        $addresses = $query->get();
        return response()->json($addresses);
    }

    /**
     * @OA\Get(
     *     path="/api/address/{id}",
     *     summary="Obter endereço específico por ID",
     *     tags={"Address"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do endereço",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do endereço",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=23),
     *             @OA\Property(property="street", type="string", example="Rua Principal 123"),
     *             @OA\Property(property="city", type="string", example="Belo Horizonte"),
     *             @OA\Property(property="state", type="string", example="Minas Gerais"),
     *             @OA\Property(property="country", type="string", example="BR"),
     *             @OA\Property(property="postal_code", type="string", example="12345"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function show($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($address);
    }

    /**
     * @OA\Post(
     *     path="/api/address",
     *     summary="Criar um novo endereço",
     *     tags={"Address"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user_id", type="integer", example=23),
     *             @OA\Property(property="street", type="string", example="Avenida Brasil 456"),
     *             @OA\Property(property="city", type="string", example="Rio de Janeiro"),
     *             @OA\Property(property="state", type="string", example="Rio de Janeiro"),
     *             @OA\Property(property="country", type="string", example="BR"),
     *             @OA\Property(property="postal_code", type="string", example="12345")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Endereço criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=23),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="street", type="string", example="Avenida Brasil 456"),
     *             @OA\Property(property="city", type="string", example="Rio de Janeiro"),
     *             @OA\Property(property="state", type="string", example="Rio de Janeiro"),
     *             @OA\Property(property="country", type="string", example="BR"),
     *             @OA\Property(property="postal_code", type="string", example="12345"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $address = $user->addresses()->create($request->all());
        return response()->json($address, 201);
    }

    /**
     * @OA\Put(
     *     path="/api/address/{id}",
     *     summary="Atualizar um endereço existente",
     *     tags={"Address"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do endereço",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="street", type="string", example="Rua 7 de Setembro 200"),
     *             @OA\Property(property="city", type="string", example="São Carlos"),
     *             @OA\Property(property="state", type="string", example="São Paulo"),
     *             @OA\Property(property="country", type="string", example="BR"),
     *             @OA\Property(property="postal_code", type="string", example="98765")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Endereço atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="street", type="string", example="Rua 7 de Setembro 200"),
     *             @OA\Property(property="city", type="string", example="São Carlos"),
     *             @OA\Property(property="state", type="string", example="São Paulo"),
     *             @OA\Property(property="country", type="string", example="BR"),
     *             @OA\Property(property="postal_code", type="string", example="98765"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function update(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->update($request->all());
        return response()->json($address);
    }

    /**
     * @OA\Delete(
     *     path="/api/address/{id}",
     *     summary="Deletar um endereço",
     *     tags={"Address"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do endereço",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Endereço deletado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Liven API informs: Address deleted successfully")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();
        return response()->json(['message' => 'Liven API informs: Address deleted successfully']);
    }
}
