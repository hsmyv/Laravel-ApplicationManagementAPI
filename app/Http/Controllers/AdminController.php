<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyRequest;
use App\Http\Requests\UserRequest;
use App\Models\Applyment;
use App\Models\Category;
use App\Models\Log;
use App\Models\Template;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use UserTYpe;

class AdminController extends Controller
{
    public function get_categories()
    {
        return Category::all();
    }

    public function get_users()
    {
        return User::with('roles')->get();
    }

    public function create_category()
    {
        $category = Category::create(request()->validate(['name' => 'required']));
        return $category;
    }
    public function update_category(Category $category)
    {
        $attributes  = request()->validate(['name' => 'required']);
        $category->update($attributes);
        return response(['message' => 'Category has been updated successfully']);
    }
    public function delete_category(Category $category)
    {
        $category->delete();
        return response(['message' => 'Category has been deleted successfully']);
    }
    public function create_user(UserRequest $request)
    {

        try {
            $formfill = $request->validated();
            $user = User::create($request->except('role', 'password_confirmation'));
            $token = $user->createToken('myappToken')->plainTextToken;
            $user->syncRoles($request->role);
            $response = [
                'user' => $user,
                'token' => $token,
                'message' => "User added successfully"
            ];
        } catch (\Spatie\Permission\Exceptions\RoleDoesNotExist $e) {
            $user = User::where('id', $user->id)->firstorfail()->delete();
            return response()->error(["For 'role' only you can select: Admin, Moderator, User"], 401);
        }
        return response($response, 200);
    }
    public function update_user(Request $request, User $user)
    {
        $check = User::where('email', $request->email)->firstOrFail();

        try {
            $formfill = $request->validate([
                'name' => 'required',
                'email' =>  ['required', Rule::unique('users')->ignore($check->id),],
            ]);
            $update = $user->syncRoles($request->role);
            if ($update) {
                $user->update($formfill);
            }

            return response(['message' => 'User updated successfully']);
        } catch (\Spatie\Permission\Exceptions\RoleDoesNotExist $e) {
            return response()->error(["For 'role' only you can select: Admin, Moderator, User"], 401);
        }
    }
    public function delete_user($id)
    {   //First we find user has o not
        $hasUser = User::where('id', $id)->where('status', '1')->first();
        if($hasUser)
        {
            //We find user is admin or no
            $user = auth()->id();
            $check = User::where('id', $user)->first();
            //If user has role "Admin", so he has all permission(He can delete Moderator)
            if($check->hasRole('Admin'))
            {
                DB::update('update users set status = 0 where id = ?', ["$id"]);
                return response(['message' => 'User has been deleted successfully']);

                //else user can delete all users except Admin and Moderator
            }else{
                $admin = User::where('id', $id)->first();

                if ($admin->hasRole('Admin')) {
                    return response(['message' => "Admin can't delete"]);
                } elseif ($admin->hasRole('Moderator')) {
                    return response(['message' => "Moderator can't delete"]);
                } else {
                    DB::update('update users set status = 0 where id = ?', ["$id"]);
                    return response(['message' => 'User has been deleted successfully']);
                }
            }
        }else{
            return response(['message' => "User can't find"]);
        }
    }


    public function update_apply(ApplyRequest $request, Applyment $apply)
    {
        $formfill = $request->validated();
        $formfill['user_id'] = auth()->user()->id;
        $formfill['status'] = $request->status;
        $apply->update($formfill);
        Log::create([
            'user_id' => auth()->user()->id,
            'implement' => $request->status,
            'implementable_id' => $apply->id,
            'implementable_title' => 'Applyment'
        ]);
        return $apply;
    }

    public function index()
    {
        return $apply = Applyment::with('logs')->get();
    }

    public function get_templates()
    {
        return Template::all();
    }

    public function update_template(Request $request, $id)
    {
        $template = Template::where('id', $id)->first();
        //$admin_role = UserTYpe::Admin; //Tahir recomendations writed in the helpers.php
        $form = $request->validate([
            'subject' => 'required',
            'body'    => 'required'
        ]);

        $template->update($form);
        $response = [
            $template,
            'message' => 'Template has been updated succesfully'
        ];
        return response($response, 200);
    }
}
