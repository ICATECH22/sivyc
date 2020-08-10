<?php

namespace App\Http\Controllers\adminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Permission;
use App\Models\Rol;
use App\Models\Unidad;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $usuarios = User::PAGINATE(25);
        return view('layouts.pages_admin.users_permisions', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ubicacion = Unidad::groupBy('ubicacion')->GET(['ubicacion']);
        // crear formulario usuario
        return view('layouts.pages_admin.users_create', compact('ubicacion'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //checar que no exista un usario con el correo electrónico que se piensa introducir
        $user = User::where('email', '=', $request->get('emailInput'))->first();
        if ($user === null) {
            # usuario no encontrado
            User::create([
                'name' => $request->get('nameInput'),
                'email' => $request->get('emailInput'),
                'password' => Hash::make($request->get('passwordInput')),
                'puesto' => $request->get('puestoInput'),
                'unidad' => $request->get('capacitacionInput')
            ]);
            // si funciona redireccionamos
            return redirect()->route('usuario_permisos.index')->with('success', 'NUEVO USUARIO AGREGADO!');
        } else {
            # usuario encontrado
            return redirect()->back()->withErrors(['EL CORREO ELECTRÓNICO ASOCIADO A ESTA CUENTA YA SE ENCUENTRA EN LA BASE DE DATOS']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $idUsuario = base64_decode($id);
        $roles = Rol::all();
        $usuario = User::findOrfail($idUsuario);
        return view('layouts.pages_admin.users_permissions_profile', compact('usuario', 'roles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $iduser = base64_decode($id);
        $usuario = User::findOrfail($iduser);
        return view('layouts.pages_admin.users_profile', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
