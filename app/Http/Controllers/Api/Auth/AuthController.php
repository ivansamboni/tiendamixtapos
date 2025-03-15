<?php

namespace App\Http\Controllers\Api\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{

    public function index()
    {
        $user = User::orderBy('nombres', 'asc')
            ->paginate(20);
        return response()->json($user);
    }

    public function show(string $id)
    {
        $user = User::find($id);
        return response($user);
    }

    public function update(Request $request, User $user)
    {

        $request->validate([
            'tipoidentificacion' => 'required',
            'numidentificacion' => 'required|unique:users,numidentificacion,' . $request->id,
            'nombres' => 'required',
            'email' => 'nullable|email|unique:users,email,' . $request->id,
            'role' => 'required',
        ]);

        $user = User::find($request->id);
        $user->tipoidentificacion = $request->tipoidentificacion;
        $user->numidentificacion = $request->numidentificacion;
        $user->nombres = $request->nombres;
        $user->apellidos = $request->apellidos;
        $user->telefono = $request->telefono;
        $user->email = $request->email;
        $user->direccion = $request->direccion;
        $user->role = $request->role;
        $user->save();

        return response($user, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipoidentificacion' => 'required',
            'numidentificacion' => 'required|unique:users',
            'nombres' => 'required',
            'telefono' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required',
        ]);
        $user = User::create($request->all());

        return response($user, Response::HTTP_CREATED);
    }

    public function datauser(Request $request)
    {
        $user = auth()->user();

        return response()->json($user);
    }

    public function updateprofile(Request $request)
    {
        // Obtener el usuario autenticado
        $user = auth()->user();

        // Validar la entrada del usuario
        $request->validate([
            'tipoidentificacion' => 'required',
            'numidentificacion' => 'required|unique:users,numidentificacion,' . $user->id,
            'nombres' => 'required',
            'telefono' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Actualizar los datos del usuario
        //$user->update($request->all());
        $user->fill($request->all())->save();

        return response($user, Response::HTTP_OK);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response('No autorizado', Response::HTTP_UNAUTHORIZED);
        }
        $user = User::where('email', $request['email'])->firstOrfail();
        $token = $user->createToken('auth_token')->plainTextToken;
        $data = [
            'message' => 'Authorized :' . $user->name,
            'accessToken' => $token,
            'token_type' => 'Bearer',
            'id' => $user->id,
            'tipoidentificacion' => $user->tipoidentificacion,
            'numidentificacion' => $user->numidentificacion,
            'nombres' => $user->nombres,
            'apellidos' => $user->apellidos,
            'role' => $user->role,
        ];

        return response($data, Response::HTTP_ACCEPTED);
    }

    public function logout(Request $request)
    {

        $user = $request->user();

        $user->tokens()->delete();

        return ['message' => 'Usuario cerró sesión'];
    }
}
