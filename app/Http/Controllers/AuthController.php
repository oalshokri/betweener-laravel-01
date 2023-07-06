<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\Double;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
//        dd($request->name);
//        dd($request->toArray());

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|unique:users|email',
            'password'=>'required|confirmed'
        ]);

        if($validator->fails()){
            return response()->json(
                [
                    'message'=>'something wrong',
                    'errors'=>$validator->errors()
                ]
            );
        }

        $validated = $validator->validated();

        $user = new User;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);

        $user->save();

        return response()->json([
            'message'=>'user created successfully',
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ],201);
    }


    public function login(Request $request):JsonResponse
    {

        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if($validator->fails()){
            return response()->json(
                [
                    'message'=>'something wrong',
                    'errors'=>$validator->errors()
                ], 400);
        }

        $validated = $validator->validated();

        if(!Auth::attempt($validated)){
            return response()->json([
                'message' => 'something went wrong',
            ],400);
        }

        return response()->json([
            'user' => auth()->user(),
            'token' => auth()->user()->createToken('secret')->plainTextToken
        ]);
    }

}
