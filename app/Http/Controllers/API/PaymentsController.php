<?php

namespace App\Http\Controllers\API;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentsController extends BaseController
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
            'recipient_account_id' => 'required|integer',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $status = 'ERROR';
        $user =  auth('api')->user();

        $payment = Payment::create([
            'sender_account_id' => $user->id,
            'recipient_account_id' => $request->recipient_account_id,
            'amount' => $request->amount
        ]);

        if($payment) {
            $status = 'OK';
        }

        $success = [
            'payment_id' => $payment->id,
            'status' => $status,
            'amount' => $payment->amount,
            'sender_account_id' => $payment->sender_account_id,
            'recipient_account_id' => $payment->recipient_account_id
        ];

        return $this->sendResponse($success, 'Payment has been created');
    }
}
