<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

/**
 * @OA\Info(
 *     title="Liven API",
 *     version="1.0.0"
 * )
 * @OA\Tag(
 *     name="User",
 *     description="Endpoints da API para User"
 * )
 */
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Obter dados do usuário logado",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="Dados do usuário logado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=23),
     *             @OA\Property(property="name", type="string", example="Joana da Silva"),
     *             @OA\Property(property="email", type="string", example="joana@silva.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z"),
     *             @OA\Property(
     *                 property="addresses",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     * )
     */
    public function show()
    {
        $user = Auth::user();
        $user->load('addresses');
        return response()->json($user);
    }

     /**
     * @OA\Put(
     *     path="/api/user",
     *     summary="Atualizar dados do usuário logado",
     *     tags={"User"},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email"},
     *              @OA\Property(property="name", type="string", example="Antônio da Silva"),
     *              @OA\Property(property="email", type="string", example="antonio@silva.com"),
     *              @OA\Property(property="password", type="string", example="newpassword12345"),
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dados do usuário logado atualizados",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=23),
     *             @OA\Property(property="name", type="string", example="Antônio da Silva"),
     *             @OA\Property(property="email", type="string", example="antonio@silva.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-16T17:30:16.000000Z"),
     *             @OA\Property(
     *                 property="addresses",
     *                 type="array",
     *                 @OA\Items(type="object")
     *             )
     *         )
     *     ),
     * )
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->only(['name', 'email']);
        
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *     path="/api/user",
     *     summary="Deletar a conta do usuário logado",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="Liven API informs: User deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     security={{"bearerAuth": {}}}
     * )
     */
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();
        return response()->json(['message' => 'Liven API informs: User deleted successfully']);
    }
}
