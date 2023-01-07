@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.users.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                Users Transactions
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
                            @foreach ($transactions as $key => $transaction)
                                <tr>
                                    <td>

                                    </td>
                                    <td>
                                        {{ $transaction->type ?? '' }}
                                    </td>
                                    <td>
                                        {{ $transaction->amount ?? '' }}
                                        <br>
                                        @foreach ((object) $transaction->meta as $key => $meta)
                                            @if ($key == 'order')
                                                <button type="button" onclick="showOrderDetails('{{ $meta }}')"
                                                    class="btn btn-success btn-xs">{{ $meta }}</button>
                                            @elseif($key == 'info')
                                                <!-- do nothing -->
                                            @else
                                                <span class="badge bg-info text-white">{{ $meta }}</span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        {{ $transaction->created_at
                                            ? \Carbon\Carbon::parse($transaction->created_at)->format(
                                                config('panel.date_format') . ' ' . config('panel.time_format'),
                                            )
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

        <div class="form-group">
            <a class="btn btn-default" href="{{ route('admin.users.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>
    </div>
@endsection
