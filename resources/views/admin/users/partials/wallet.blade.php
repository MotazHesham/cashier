  <div class="row">
    <div class="col-md-5">
        <div class="c-callout c-callout-info b-t-1 b-r-1 b-b-1">
            <small class="text-muted">{{ trans('cruds.wallet.balance')}}</small><br>
            <strong class="h4">EGP {{ $user->current_balance() ?? 0.00 }} </strong>
        </div>
    </div>
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                Wallet History
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class=" table table-bordered table-striped table-hover datatable">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    Type
                                </th>
                                <th>
                                    Amount
                                </th>
                                <th>
                                    Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transactions as $key => $transaction)
                                <tr >
                                    <td>

                                    </td>
                                    <td>
                                        {{ $transaction->type ?? '' }}
                                    </td>
                                    <td>
                                        {{ $transaction->amount ?? '' }}
                                        <br>
                                        @foreach((object)$transaction->meta as $key => $meta)
                                            @if($key == 'order')
                                              <button type="button" onclick="showOrderDetails('{{$meta}}')" class="btn btn-success btn-xs">{{$meta}}</button>
                                            @elseif($key == 'info')
                                              <!-- do nothing -->
                                            @else
                                              <span class="badge bg-info text-white">{{ $meta }}</span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $transaction->created_at
                                          ? \Carbon\Carbon::parse($transaction->created_at)->format(config('panel.date_format') . ' ' .config('panel.time_format'))
                                          : null }}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $transactions->links() }}
            </div>
        </div>

    </div>
</div>
