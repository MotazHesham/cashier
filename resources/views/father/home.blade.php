@extends('layouts.father')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                      <div class="col-md-6">
                        <div class="c-callout c-callout-info b-t-1 b-r-1 b-b-1 text-center">
                            <small class="text-muted">{{ trans('cruds.wallet.balance')}}</small><br>
                            <strong class="h2">EGP {{ $user->current_balance() ?? 0.00 }} </strong>
                        </div>

                        <h2 class="">{{ trans('cruds.wallet.students_balance') }}</h2>
                        <div class="row">
                          @foreach($sons as $son)
                            @php
                              $student_id = $son->student ? $son->student->id : 0;
                            @endphp
                            <div class="col-md-4">
                              <a href="{{route('father.students.show',$student_id)}}" style="color:black">
                                <div class="c-callout c-callout-info b-t-1 b-r-1 b-b-1 text-center">
                                    <small class="text-muted">{{ $son->name }}</small><br>
                                    <strong class="h2">EGP {{ $son->current_balance() ?? 0.00 }} </strong>
                                </div>
                                </a>
                            </div>
                          @endforeach
                        </div>

                        <hr>
                        <div class="card">
                          <div class="card-header">
                            التحويل بين الحسابات
                          </div>
                          <div class="card-body">
                              <form  action="{{ route('father.payments.transfer') }}" method="post">
                                @csrf
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required" for="from">{{ trans('cruds.wallet.fields.from') }}</label>
                                        <select class="form-control select2 {{ $errors->has('from') ? 'is-invalid' : '' }}" name="from" id="from" required>
                                          <option value disabled {{ old('from', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                            <option value="{{auth()->id()}}" {{ old('from') == auth()->id() ? 'selected' : '' }}>My Account</option>
                                            @foreach($sons as $son)
                                                <option value="{{ $son->id }}" {{ old('from') == $son->id ? 'selected' : '' }}>{{ $son->name }}</option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('from'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('from') }}
                                            </div>
                                        @endif
                                    </div>
                                  </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                          <label class="required" for="to">{{ trans('cruds.wallet.fields.to') }}</label>
                                          <select class="form-control select2 {{ $errors->has('to') ? 'is-invalid' : '' }}" name="to" id="to" required>
                                            <option value disabled {{ old('to', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                              @foreach($sons as $son)
                                                  <option value="{{ $son->id }}" {{ old('to') == $son->id ? 'selected' : '' }}>{{ $son->name }}</option>
                                              @endforeach
                                              <option value="{{auth()->id()}}" {{ old('to') == auth()->id() ? 'selected' : '' }}>My Account</option>
                                          </select>
                                          @if($errors->has('to'))
                                              <div class="invalid-feedback">
                                                  {{ $errors->first('to') }}
                                              </div>
                                          @endif
                                      </div>
                                    </div>
                                    <div class="col-md-6">

                                      <div class="form-group">
                                          <label class="required" for="amount">{{ trans('cruds.wallet.fields.amount') }}</label>
                                          <input class="form-control {{ $errors->has('amount') ? 'is-invalid' : '' }}" type="number" name="amount" id="amount" value="{{ old('amount', '') }}" step="0.01" required>
                                          @if($errors->has('amount'))
                                              <div class="invalid-feedback">
                                                  {{ $errors->first('amount') }}
                                              </div>
                                          @endif
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="form-group">
                                          <button class="btn btn-success btn-block mt-4" type="submit">
                                              {{ trans('cruds.wallet.transfer') }}
                                          </button>
                                      </div>
                                    </div>
                                </div>
                              </form>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
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

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
