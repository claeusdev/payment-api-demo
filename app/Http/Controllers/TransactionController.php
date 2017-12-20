<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Account;
use App\Transaction;
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

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return response()->json(
        //     Transaction::where('id', $id)->where('user_id', Auth::user()->id)
        // )
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


            Account::where('user_id', $user->id)->update(['balance'=> $new_balance]);
            
            Account::where('user_id',$merchant->id)->update(['balance'=> $merchant_balance]);

            Transaction::create(['account_id'=>$user->id, 'amount'=>$request->amount,'is_credit' => 0, 'is_debit' => 1,'remark'=>$remark]);

            Transaction::create(['account_id'=>$merchant->id, 'sender_id'=>$user->id,'amount'=>$request->amount,'is_credit' => 1, 'is_debit' => 0,'remark'=>$remark]);

            return redirect('http://localhost/ecommerce/thankyou.php');
        }

        return view('payment',compact('has_error','amount','currency','success','error'));

    }

    public function refund(Request $request)
    {
        $error = null;
        $has_error= false;

        $currency = Auth::user()->account->currency;
        $amount = (isset($request->amount)) ? $request->amount : 0;
        $user = User::where('id',$request->ref)->first(); 
        $merchant = User::where('id', $request->merchantref)->first();
        if(!isset($request->ref))
        {
            $error = "No user selected";
            $has_error = true;
        }
        else if( empty( User::where('id',$user->id)->get()->toArray() ) )
        {
            $error = "Account does not exist. <a href='".url('register')."'>Register here</a>";
            $has_error = true;
        }
        else
        {
            

            if(!isset($request->amount))
            {
                $error = "No amount selected";
                $has_error = true;
            }
            else
            {
                $success = "Transaction Sucessful";
                $user = User::where('id',$request->ref)->first(); 
                $merchant = User::where('id', $request->merchantref)->first();
                $remark = isset($request->remark) ? $request->remark : "Refund of ". $request->amount ." ". $user->account->currency;


                echo   $request->amount;
                $new_balance = $user->account->balance + $request->amount;
                $merchant_balance = $merchant->account->balance - $request->amount;
                echo $merchant_balance;
                

                Account::where('user_id', $user->id)->update(['balance'=>$new_balance]);
                Account::where('user_id', $merchant->id)->update(['balance'=>$merchant_balance]);

                Transaction::create(['account_id'=>$user->id, 'sender_id'=>$merchant->id,'amount'=>$request->amount,'is_credit' => 1, 'is_debit' => 0,'remark'=>$remark]);

                Transaction::create(['account_id'=>$merchant->id, 'sender_id'=>$user->id, 'amount'=>$request->amount,'is_credit' => 0, 'is_debit' => 1,'remark'=>$remark]);

                return redirect()->route('home');
            }
        }

        return view('payment',compact('has_error','amount','currency','success','error'));

    }
}
