<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // $customer = User::where('email', $request->ref).first();
        // $merchant = User::where('api_token', $request->merchantRef).first();
        // $amount = $request->amount;

        // return Transaction::create([
        //     'account_id'=> $customer->account->id,
        //     'is_credit' => 1,
        //     'is_debit' => 0,
        //     'amount' => $amount,
        //     'remark' = "Payment of '.$amount.'"
        // ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

     public function pay(Request $request)
    {
        $error = null;
        $has_error= false;

        $currency = Auth::user()->account->currency;
        $amount = (isset($request->amount)) ? $request->amount : 0;

        $user = User::with('account')->where('email',$request->ref)->first();
        // echo $request->merchantRef;
        // $merchant = User::with('account')->where('token', $request->merchantRef)->first();
        $merchant = User::with('account')->where('api_token', $request->merchantref)->first();

        if( $request->amount > $user->account->balance )
        {
            $error = "Insuffecient Funds";
            $has_error = true;
        }
        else
        {
            $success = "Transaction Sucessful";

            $user = User::where('email',$request->ref)->first(); 
            $merchant = User::where('api_token', $request->merchantref)->first();

            $remark = isset($request->remark) ? $request->remark : "Payment of ". $request->amount ." ". $user->account->currency;
            // This doesnt save to the database
            $new_balance = $user->account->balance - $request->amount;

            echo   $request->amount;
            $merchant_balance = $merchant->account->balance + $request->amount;
            echo $merchant_balance;


            Account::where('user_id',Auth::user()->id)->update(['balance'=> $new_balance]);
            
            Account::where('user_id',$merchant->id)->update(['balance'=> $merchant_balance]);

            Transaction::create(['account_id'=>Auth::user()->id,'amount'=>$request->amount,'is_credit' => 0, 'is_debit' => 1,'remark'=>$remark]);

            Transaction::create(['account_id'=>$merchant->id,'amount'=>$request->amount,'is_credit' => 1, 'is_debit' => 0,'remark'=>$remark]);

            return redirect('http://localhost/ecommerce/thankyou.php');
        }

        return view('payment',compact('has_error','amount','currency','success','error'));

    }
}
