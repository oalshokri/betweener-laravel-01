<?php

namespace App\Http\Controllers;

use App\Models\ActiveSharing;
use App\Models\User;
use app\Services\NotificationHelper;
use Illuminate\Http\Request;


class ShareController extends Controller
{

    public function activeShare(User $user,Request $request)
    {
        $activeSharing = new ActiveSharing();
        $activeSharing->user_id = $user->id;
        $activeSharing->type = $request->type;
        $activeSharing->save();


        return response()->json([
            'activeSharing' => $activeSharing
        ]);


    }
    public function deleteActiveShare(User $user)
    {
        $activeSharingUser = ActiveSharing::where('user_id',$user->id)->first();

        return response()->json([
            'isDeleted' => $activeSharingUser->delete()
        ]);

    }

    public function getNearestSender(User $user){
        $activeSharingUsers = ActiveSharing::where('type','sender')->with('user')->get();
        $nearestUsers = [];
        foreach ($activeSharingUsers as $loopUser) {

                $distance = $this->points_on_earth($loopUser->user->lat, $loopUser->user->long, $user->lat, $user->long);
                if ($distance <= 5.0) {
                    $loopUser->distance = $distance;
                    $nearestUsers[] = $loopUser;
//                    $data = [
//                        "message" => [
//                            "token" => $loopUser->fcm,
//                            "notification" => [
//                                "body" => "This is an FCM notification message!",
//                                "title" => "FCM Message"
//                            ]
//                        ]
//                    ];
//                    NotificationHelper::sendNotification($data);
                }



        }
        return response()->json([
            'count' => count($nearestUsers),
            'nearest-users' => $nearestUsers
        ]);
    }
    public function longPressShare(User $user)
    {
        $users = User::all();
        $nearestUsers = [];
        foreach ($users as $loopUser) {
            if ($loopUser->id != $user->id) {
                $distance = $this->points_on_earth($loopUser->lat, $loopUser->long, $user->lat, $user->long);
                if ($distance <= 5.0) {
                    $loopUser->distance = $distance;
                    $nearestUsers[] = $loopUser;
                    $data = [
                        "message" => [
                            "token" => $loopUser->fcm,
                            "notification" => [
                                "body" => "This is an FCM notification message!",
                                "title" => "FCM Message"
                            ]
                        ]
                    ];
                    NotificationHelper::sendNotification($data);
                }

            }

        }
        return response()->json([
            'count' => count($nearestUsers),
            'nearest-users' => $nearestUsers
        ]);


    }

    function points_on_earth($latitudeFrom, $longitudeFrom,
                             $latitudeTo, $longitudeTo): float
    {
        $long1 = deg2rad($longitudeFrom);
        $long2 = deg2rad($longitudeTo);
        $lat1 = deg2rad($latitudeFrom);
        $lat2 = deg2rad($latitudeTo);

        //Haversine Formula
        $dlong = $long2 - $long1;
        $dlati = $lat2 - $lat1;

        $val = pow(sin($dlati / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($dlong / 2), 2);

        $res = 2 * asin(sqrt($val));

//        $radius = 3958.756;//earth radius in miles
        $radius = 6371;//earth radius in km

        return ($res * $radius);
    }


}
