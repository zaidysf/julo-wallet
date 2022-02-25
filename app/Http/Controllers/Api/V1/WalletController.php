<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    /**
     * Create a new WalletController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth:api', 'wallet'], ['except' => ['enable']]);
    }

    /**
     * Enable user's wallet.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enable()
    {
        $data = User::find(auth()->id());
        $data->wallet_status = 1;
        $data->save();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Disable user's wallet.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function disable()
    {
        $data = User::find(auth()->id());
        $data->wallet_status = 0;
        $data->save();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * create a wallet transaction.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:0,1',
            'amount' => 'required|numeric',
            'reference_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->get('type') == 1 && auth()->user()->wallet_balance < $request->get('amount')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient Balance',
                'data' => auth()->user()
            ]);
        }

        $data = WalletTransaction::create([
            'user_id' => auth()->id(),
            'transaction_type' => $request->get('type'),
            'amount' => $request->get('amount'),
            'reference_id' => $request->get('reference_id'),
        ]);

        $data = User::find(auth()->id());
        $data->wallet_balance += $request->get('type') == 1 ? -1 * $request->get('amount') : $request->get('amount');
        $data->save();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}
