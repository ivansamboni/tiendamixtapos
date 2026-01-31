<?php

namespace App\Http\Controllers\Api\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;

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

    public function userSearch(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                foreach (['nombres', 'apellidos', 'numidentificacion', 'email'] as $field) {
                    $q->orWhere($field, 'LIKE', $searchTerm);
                }
            });
        }

        $users = $query->orderBy('nombres', 'asc')->paginate(20);

        return response()->json($users);
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
        $user = User::withTrashed()->where('numidentificacion', $request->numidentificacion)->first();

        $request->validate([
            'tipoidentificacion' => 'required',
            'numidentificacion' => [
                'required',
                Rule::unique('users', 'numidentificacion')->ignore($user?->id),
            ],
            'nombres' => 'required',
            'telefono' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'password' => 'required|min:8|confirmed',
            'role' => 'required',
        ]);

        if ($user) {
            // Restaurar usuario y actualizar datos si es necesario
            $user->restore();
            $user->update([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'password' => Hash::make($request->password), // Hashear contraseña
                'role' => $request->role,
            ]);

            return response()->json(['message' => 'Usuario restaurado y actualizado. Ahora puede iniciar sesión.']);
        }

        // Crear usuario nuevo
        $user = User::create([
            'tipoidentificacion' => $request->tipoidentificacion,
            'numidentificacion' => $request->numidentificacion,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'direccion' => $request->direccion,
            'password' => Hash::make($request->password), // Hashear contraseña
            'role' => $request->role,
        ]);

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
