<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index(){
        // return User::with('role')->paginate();
        $users = User::paginate();
        return UserResource::collection($users);
    }

    public function show($id){
        // return User::find($id);
        $user = User::find($id);
        return new UserResource($user);
    }

    public function store(UserCreateRequest $request) {
        $user = User::create(
        $request->only('first_name', 'last_name', 'email', 'role_id') +
        // $user = User::create([
            // 'first_name' => $request->input('first_name'),
            // 'last_name' => $request->input('last_name'),
            // 'email' => $request->input('email'),
        //    ['
           ['password' => Hash::make(123546)
        ]);

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function update(UserUpdateRequest $request, $id) {
        $user = User::find($id);
        $user->update(
            $request->only('first_name', 'last_name', 'email', 'role_id')
        //    [
        //     'first_name' => $request->input('first_name'),
        //     'last_name' => $request->input('last_name'),
        //     'email' => $request->input('email'),
        //     'password' => Hash::make( $request->input('password') )
        // ]
        );

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id){
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }

    //-----------------
    public function user(){
        return new UserResource(Auth::user());//ver sus datos
    }

    public function updateInfo(UpdateInfoRequest $request){//actuaizar sus datos del usuario que esta logeado
        $autheticated_user = Auth::user();//busqueda del usuario
        $user = User::find($autheticated_user->id);//buscamos que el usuario este autentificada
        $user->update($request->only('first_name', 'last_name', 'email'));//actulizamos los datos que lleguen usando only
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request){
        $autheticated_user = Auth::user();
        $user = User::find($autheticated_user->id);
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);
        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
