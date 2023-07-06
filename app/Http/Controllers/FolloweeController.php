<?php

namespace App\Http\Controllers;

use App\Models\Followee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class FolloweeController extends Controller
{
    public function index():JsonResponse
    {

$followers = auth()->user()->followers;
        return response()->json([
            'count'=>$followers->count(),
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
