<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store', 'resend']);
        $this->middleware('auth:api')->except(['store', 'resend']);
        $this->middleware('scope:manage_account')->only(['show', 'update']);
        $this->middleware('can:view,user')->only(['show']);
        $this->middleware('can:update,user')->only(['update']);
        $this->middleware('can:delete,user')->only(['destroy']);
    }

    public function index()
    {
        $users = User::all();
        // return response()->json(['data'=>$users]);
        return $this->showAll($users);
    }



    /*
    public function show($id)
    {
        $user = User::findOrFail($id);
        // return response()->json($user);
        return $this->showOne($user, 200);
    }
    */
   
   public function show(User $user)
   {
       return $this->showOne($user, 200);
   }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'admin' => 'required',
        ]);

        $data['verified'] = 0;
        $data['verify_token'] = str_random(40);
        $data['password'] = bcrypt($data['password']);


        $user = User::create($data);
        return response()->json([
            'data' => $data,
            'message' => 'ok, user created!'
        ]);
    }


    public function fwe()
    {
        $data = $this->validate($request, [
            'name' => 'required',
        ]);
        $model->fill($data);
        if ($model->isClean()) {
            // error reponse
        }
        $model->save();
        // success reponse
    }

    public function update(Request $request, $user)
    {
        $user = User::findOrFail($user);

        $data = $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($request->has('name') && $user->name != $request->name) {
            // some validation
            // $user->name = $request->name;
            // $user->save();
        }
        $data['password'] = bcrypt($data['password']);

        $user->fill($data);

        // 如果用户的数据没有改变
        if ($user->isClean()) {
            // return response()->json(['error'=>'you need to specify a different value to update'], 422);
            return $this->errorRespond('you need to specify a different value to update', 422);
        }
        
        $user->save();

        return response()->json([
            'message' => 'ok, data saved!',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message'=>'ok, droped', 'data'=>$user]);
    }

    public function destroy2(User $user)
    {
        $user->delete();
        return response()->json(['message'=>'ok, droped', 'data'=>$user]);
    }

    public function verify($token)
    {
        $user = User::where('verify_token', $token)->firstOrFail();
        $user->verified = 1;
        $user->verify_token = null;
        $user->save();

        return $this->showMessage('the account has been verified succesfully');
    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorRespond('this user is already verified', 409);
        }

        retry(5, function () use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        });

        return $this->showMessage('the verification email has been resend');
    }
}
