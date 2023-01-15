<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyRequest;
use App\Http\Requests\UserRequest;
use App\Models\Applyment;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function register(UserRequest $request)
    {
        $welcomeTemplate = DB::table('templates')->where('id', 1)->first();

        $formfill = $request->validated();
        $formfill['status'] = '1';
        $user = User::create($formfill);
        $formfill['role']   = 'User';
        $user->syncRoles($formfill['role']);
        //sendUserEmail($user, $request->password, $welcomeTemplate);
        $token = $user->createToken('myappToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'message' => "Registration Successfully"
        ];

        if ($user) {
        } else {
            $user = User::where('id', $user->id)->firstOrFail()->delete();
        }

        return response($response, 200);
    }

    public function login(Request $request)
    {

        $formfill = $request->validate([
            'email' => 'required|string|exists:users,email',
            'password' => 'required|string'
        ]);
        $check = User::where('email', $request->email)->first();
        if($check->status == '1')
        {
        try {
            $user = User::where('email', $formfill['email'])->first();
        } catch (\Illuminate\Database\QueryException $th) {
            return response(['message' => "Not found user"]);
        }

        if (!$user || !Hash::check($formfill['password'], $user->password)) {
            return response([
                'message' => "Invalid Credentials"
            ], 401);
        }

        $token = $user->createToken('myappToken')->plainTextToken;
        $response = [
            'token' => $token,
            'user'  => $user
        ];

        if (Auth()->attempt($formfill)) {
            session()->regenerate();
            return response($response, 201);
        }
        }else{
            return response(['message' => 'Your account has been deleted!']);
        }
    }


    public function create_apply(ApplyRequest $request)
    {
        // user_id error-u hell etmek ucun: routes->API-deki auth:sanctum aciq olmalidir
        $formfill = $request->validated();
        $formfill['user_id'] = auth()->user()->id;
        $applyment = Applyment::create($formfill);
        return $applyment;
    }
}
