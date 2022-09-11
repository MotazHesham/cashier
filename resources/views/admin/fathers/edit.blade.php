@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.father.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.fathers.update", [$father->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <input type="hidden" name="user_id" value="{{ $father->user->id}}" id="">

            @include('admin.users.partials.edit')


            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
