<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

class LinkController extends Controller
{
    public function index():JsonResponse
    {
        return response()->json([
            'links'=>auth()->user()->links
        ]);
    }
    public function store(Request $request):JsonResponse
    {

        $validator = Validator::make($request->all(),[
            'title'=>'required',
            'link'=>'required',
            'username'=>'nullable',
            'isActive'=>'numeric'
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
        $link = Link::create($validated);

        return response()->json([
            'link' => $link->with('user')->find($link->id),
        ]);
    }

    public function update(Link $link,Request $request):JsonResponse
    {

        $validator = Validator::make($request->all(),[
            'title'=>'required',
            'link'=>'required',
            'username'=>'nullable',
            'isActive'=>'numeric'
        ]);

        if($validator->fails()){
            return response()->json(
                [
                    'message'=>'something wrong',
                    'errors'=>$validator->errors()
                ], 400);
        }

        $validated = $validator->validated();

        if($link->update($validated)){
            return response()->json([
                'message'=>'updated successfully',
            ]);
        }else{
            return response()->json([
                'message'=>'something went wrong',
            ]);
        }


    }

    public function delete(Link $link):JsonResponse
    {

        if($link->delete()){
            return response()->json([
                'message'=>'deleted successfully',
            ]);
        }else{
            return response()->json([
                'message'=>'something went wrong',
            ]);
        }

    }
}
