<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function updateLocation(User $user,Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'long'=>'required|numeric',
            'lat'=>'required|numeric'
        ]);

        if($validator->fails()){
            return response()->json(
                [
                    'message'=>'something wrong',
                    'errors'=>$validator->errors()
                ], 400);
        }

        $validated = $validator->validated();

        $user->long = floatval($validated['long']);
        $user->lat = floatval($validated['lat']);
        $user->save();

        return response()->json([
            'user' => $request->all(),

        ],200);

    }

    public function updateFcm(User $user,Request $request):JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'fcm'=>'required'
        ]);

        if($validator->fails()){
            return response()->json(
                [
                    'message'=>'something wrong',
                    'errors'=>$validator->errors()
                ], 400);
        }

        $validated = $validator->validated();

        $user->fcm = $validated['fcm'];
        $user->save();

        return response()->json([
            'user' => $request->all(),
        ]);

    }

    public function search(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required'
        ]);

        if($validator->fails()){
            return response()->json(
                [
                    'message'=>'something wrong',
                    'errors'=>$validator->errors()
                ], 400);
        }

        $validated = $validator->validated();


        return response()->json([
            'user' => User::where( 'name', 'LIKE', '%' . $validated['name'] . '%' )->get(),
        ]);
    }

    public function show(User $user){



        return response()->json([
            'user'=>$user->with('links')->get()
        ]);
    }

}
