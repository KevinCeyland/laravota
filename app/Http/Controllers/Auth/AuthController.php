<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'carte_identite' => 'required|string',
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'password' => 'required',
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $messageError = $validator->errors();
                $messageError = $messageError->getMessages();
                $first_key = array_key_first($messageError);
                $first_value = $messageError[$first_key];
                return response()->json(["messageError" => $first_value[0]]);
            }

            $validated = $validator->validated();
            $role = Role::where('libelle', '=', $validated['role'])->first();

            $user =  User::create([
                'carte_identite' => $validated['carte_identite'],
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $role->id,
            ]);

            // Si la demande d'authentification est bonne, cela renvoie un token que l'on stocke dans la variable token sinon la variable sera false
            $token = auth()->attempt($request->only('email', 'password'));
            // Si le token est false nous retournons une réponse null est une erreur 401
            if (!$token) {
                return response(null, 401);
            }
            // Nous retournons en format JSON le token.
            return response()->json([compact('token')]);
        } catch (QueryException $ex) {
            return response()->json(["messageError" => "Un problème est survenu avec la base de donnée ! "]);
        }
    }
    public function login(Request $request)
    {
        try {

            $token = Auth::attempt(['email' => $request->email, 'password' => $request->password]);

            // Si le token est false nous retournons une réponse null est une erreur 401
            if (!$token) {
                return response()->json(["messageError" => "Vos identifiants sont incorrect", "code" => 498]);
            }
            // Nous retournons en format JSON le token.
            return response()->json([compact('token')]);
        } catch (\Throwable $th) {
            return response()->json(["messageError" => "Un problème est survenu avec la base de données"]);
        }
    }
    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'email' => $user->email,
            'prenom' => $user->prenom,
            'nom' => $user->nom,
            'id' => $user->id,
            'role' => $user->role->libelle,
        ]);
    }

    public function logout()
    {
        auth()->logout();
    }
}
