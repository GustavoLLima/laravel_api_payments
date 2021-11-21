<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::paginate(10);
        return UserResource::collection($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

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
     * Transaction
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function transaction(Request $request)
    {
        //var_dump($request->value); exit();
        $payer = User::findOrFail($request->payer);
        $payee = User::findOrFail($request->payee);

        // echo "Total before transaction:";
        // echo "   ";
        // echo $payer->total_value;
        // echo "   ";
        // echo $payee->total_value;
        // echo "   ";
        // var_dump($payer->total_value);
        // var_dump($payee->total_value);

        if($payer->type == 'regular') {
            if($payer->total_value - $request->value >= 0) {
                $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
                $response->json();
                //var_dump($response['message']);
                if ($response['message'] == 'Autorizado') {
                    $response2 = Http::get('http://o4d9z.mocklab.io/notify');
                    $response2->json();
                    //var_dump($response2['message']);

                    if ($response2['message'] == 'Success') {
                        $payee->total_value += $request->value;
                        $payee->save();

                        $payer->total_value -= $request->value;
                        $payer->save();

                        // echo "Transaction done";
                        // echo "   ";

                        // echo "Total after transaction:";
                        // echo "   ";
                        // echo $payer->total_value;
                        // echo "   ";
                        // echo $payee->total_value;
                        // echo "   ";

                        // var_dump($payer->total_value);
                        // var_dump($payee->total_value);

                        return response()->json([
                            'success' => 'true',
                            'message' => new UserResource($payer),
                        ]);

                        
                    } else {
                        // Tratar
                        // echo "Fail 2nd Mock";
                        //abort(403, 'Fail 2nd Mock');
                        // return response()->json([
                        //     'error_message' => 'Fail 2nd Mock',
                        // ]);
                        return response()->json([
                            'success' => 'false',
                            'message' => 'Fail 2nd Mock',
                        ]);
                    }
                } else {
                    // Tratar
                    // echo "Fail 1st Mock";
                    //abort(403, 'Fail 1st Mock');
                    // return response()->json([
                    //     'error_message' => 'Fail 1st Mock',
                    // ]);
                    return response()->json([
                        'success' => 'false',
                        'message' => 'Fail 1st Mock',
                    ]);
                }
            } else {
                // Tratar
                //echo "Not enough money";
                //abort(403, 'Not enough money');
                // return response()->json([
                //     'error_message' => 'Not enough money',
                // ]);
                return response()->json([
                    'success' => 'false',
                    'message' => 'Not enough money',
                ]);
            }
        } else {
            // Tratar
            // echo "Sellers are not allowed to send money";
            //abort(403, 'Sellers are not allowed to send money');
            // return response()->json([
            //     'error_message' => 'Sellers are not allowed to send money',
            // ]);
            return response()->json([
                'success' => 'false',
                'message' => 'Sellers are not allowed to send money',
            ]);

        }       

        exit();

        //
        // $user = User::findOrFail($id);
        // return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
