@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                  Dashboard
                    @if(Auth::user()->isSeller == true)
                        <span><strong>Your Api Key: <?php echo Auth::user()->api_token; ?></strong></span>
                    @endif
                <div></div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

            <ul class="nav nav-pills">
              <li class="active"><a data-toggle="tab" href="#home">Account Balance</a></li>
              <li><a data-toggle="tab" href="#menu1">Transaction History</a></li>
            </ul>

            <div class="tab-content">
              <div id="home" class="tab-pane fade in active">
                <h3>Account Balance</h3>
                <h4 class="well">
                    <!-- @if( is_null(Auth::user()->account) )
                        0.00 GHS
                    @else -->

                    {{ number_format( Auth::user()->account->balance, 2 ). " " .Auth::user()->account->currency }}
                   <!--  @endif -->

                </h4>
              </div>
              <div id="menu1" class="tab-pane fade">
                <h3>History</h3>
                <p>
                    <table class="table table-hover table-striped">
                    <thead>
                      <tr>
                        <th>Details</th>
                        <th>From</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach( Auth::user()->Transaction as $history )

                            <tr>
                                <td>{{ $history->remark }}</td>
                                <td>{{ $history->sender_id }}</td>
                                <td>
                                    @if( $history->is_credit && !$history->is_debit )
                                        <span class="text text-success">+
                                    @elseif( !$history->is_credit && $history->is_debit )
                                        <span class="text text-danger">-
                                    @else
                                        <span class="hidden">
                                    @endif
                                        {{ $history->amount ." ".Auth::user()->account->currency }}
                                        </span>
                                </td>
                                <td>{{ $history->created_at->diffForHumans() }}</td>
                                <td>
                                    @if($history->is_credit)
                                        <form action="http://localhost:8000/refund?ref=<?php echo $history->sender_id ?>&amount=<?php echo $history->amount ?>&merchantref=<?php echo Auth::user()->id ?>" method="POST">
                                            <input type="submit" value="Refund" class="btn btn-danger">
                                        </form>
                                    @endif
<!--                                   <form action="http://localhost:8000/refund?ref='..'"></form>
 -->                             </td>
                              </tr>

                        @endforeach
                      
                     
                    </tbody>
                  </table>
                    
                </p>
              </div>
              
            </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
