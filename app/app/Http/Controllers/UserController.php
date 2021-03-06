<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
    * @OA\Get(

    *  path="/users",

    *  tags={"Users"},

    *  summary="Users list",

    *  @OA\Response(response="200",

    *    description="List of users with pagination",

    *  )

    * )

    */

    public function index()
    {
        $users = User::paginate(10);
        return UserResource::collection($users);
    }

    /**
    * @OA\Get(

    *  path="/user/{user}",

    *  tags={"Users"},

    *  operationId="user_id",

    *  summary="Single user show",

    *  @OA\Response(response="200",

    *    description="User show",

    *  ),

    *  @OA\Parameter(name="user",

    *    in="path",

    *    required=true,

    *    @OA\Schema(type="integer")

    *  ),

    *  @OA\Response(response="404",

    *    description="User not found",

    *  )

    * )

    */

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    /**
    * @OA\Post(

    *  path="/user/transaction",

    *  tags={"Users"},

    *  summary="Money transferation between users",

    *  @OA\Parameter(name="value",

    *    in="query",

    *    required=true,

    *    @OA\Schema(type="number")

    *  ),

    *  @OA\Parameter(name="payer",

    *    in="query",

    *    required=true,

    *    @OA\Schema(type="integer")

    *  ),

    *  @OA\Parameter(name="payee",

    *    in="query",

    *    required=true,

    *    @OA\Schema(type="integer")

    *  ),

    *  @OA\Response(response="200",

    *    description="Transfer completed, return the payer and payee attributes on a json",

    *  ),

    *  @OA\Response(response="404",

    *    description="Payer or payee not found",

    *  ),

    *  @OA\Response(response="400",

    *    description="Something went wrong with the transaction, check message for details",

    *  ),

    * )

    */

    /**
     * Transaction
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function transaction(Request $request)
    {
        $payer = User::find($request->payer);
        if ($payer === null) {
            return response()->json([
                'message' => 'Payer not found',
            ], 404);
        }
        $payee = User::find($request->payee);
        if ($payee === null) {
            return response()->json([
                'message' => 'Payee not found',
            ], 404);
        }

        if($payer->type != 'regular'){
            return response()->json([
                'message' => 'Sellers are not allowed to send money',
            ], 400);
        }

        if($payer->total_value - $request->value < 0){
            return response()->json([
                'message' => 'Not enough money',
            ], 400);
        }

        $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        $response->json();

        if ($response['message'] != 'Autorizado'){
            return response()->json([
                'message' => 'Fail 1st Mock',
            ], 400);
        }

        $response2 = Http::get('http://o4d9z.mocklab.io/notify');
        $response2->json();

        if ($response2['message'] != 'Success'){
            return response()->json([
                'message' => 'Fail 2nd Mock',
            ], 400);
        }

        try {
            DB::transaction(function () use ($request, $payee, $payer) {
                $payee->total_value += $request->value;
                $payee->save();

                $payer->total_value -= $request->value;
                $payer->save();
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error during the transaction.',
            ], 400);
        }

        return response()->json([new UserResource($payer), new UserResource($payee)], 200);
    }
}
