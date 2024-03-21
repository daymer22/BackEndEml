<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
        ]);

        $existeCorreo = Users::where('email', $request->email)->first();

        if ($existeCorreo) {
            return response()->json(['message' => 'El correo electrónico ya está registrado'], 200);
        }

        // Crear un nuevo usuario con los datos proporcionados
        $usuario = new Users();
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->direccion = $request->direccion;
        $usuario->deleteUsers = false; // Establecer el valor predeterminado del campo deleteUsers
        $usuario->save();

        return response()->json(['message' => 'Usuario creado exitosamente', 'usuario' => $usuario], 201);
    }

    public function index()
    {
        // Consultar todos los usuarios
        $usuarios = Users::where('deleteUsers', false)->get();
        // Devolver la lista de usuarios
        return response()->json(['usuarios' => $usuarios], 200);
    }

    public function show($id)
    {
        // Buscar el usuario por su ID
        $usuario = Users::find($id);

        // Verificar si el usuario fue encontrado
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Devolver los detalles del usuario
        return response()->json(['usuario' => $usuario], 200);
    }

    public function update(Request $request, $id)
    {
        // Validar los campos de entrada
        $request->validate([
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
        ]);

        // Verificar si el correo electrónico ya está registrado en otro usuario (excluyendo el usuario actual por su ID)
        $existeCorreo = Users::where('email', $request->email)->where('id', '!=', $id)->first();

        if ($existeCorreo) {
            return response()->json(['message' => 'El correo electrónico ya está registrado en otro usuario'], 200);
        }

        // Buscar el usuario por su ID
        $usuario = Users::findOrFail($id);

        // Actualizar los campos del usuario
        $usuario->update([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        return response()->json(['message' => 'Usuario actualizado exitosamente', 'usuario' => $usuario], 201);
    }

    public function destroy($id)
    {
        // Buscar el usuario por su ID
        $usuario = Users::find($id);

        // Verificar si el usuario fue encontrado
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        // Cambiar el estado de deleteUsers a true
        $usuario->deleteUsers = true;
        $usuario->save();

        // Devolver una respuesta de éxito
        return response()->json(['message' => 'Usuario eliminado exitosamente'], 200);
    }
}
