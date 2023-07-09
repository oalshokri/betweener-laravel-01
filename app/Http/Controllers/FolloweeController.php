<?php

namespace App\Http\Controllers;

use App\Models\Followee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class FolloweeController extends Controller
{
    public function index():JsonResponse
    {
        $user = auth()->user();

        $following= collect($user->follow)->map(function ($item){
            return User::find($item->followee_id);
            });


        $followers = collect(Followee::where('followee_id',$user->id)->with('user')->get())
            ->map(function ($item){
            return User::find($item->user_id);
        });

//        $followers = Followee::where('followee_id',$user->id)->with('user')->get();


        return response()->json([
            'following_count'=>$following->count(),
            'followers_count'=>$followers->count(),
            'following'=>$following,
            'followers'=>$followers
        ]);
    }

    public function store(Request $request):JsonResponse
    {

        $validator = Validator::make($request->all(),[
            'followee_id'=>'required'
        ]);

        if($validator->fails()){
            return response()->json(
                [
                    'message'=>'something wrong',
                    'errors'=>$validator->errors()
                ], 400);
        }

        $validated = $validator->validated();
        $validated['user_id'] = auth()->user()->id;
        if( $validated['user_id']!=$validated['followee_id']){
            $followee = Followee::create($validated);

            return response()->json([
                'followee' => $followee->with('user')->find($followee->id),
            ]);
        }

        return response()->json([
            'message'=>'something wrong',
        ]);

    }
}
