@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.father.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.fathers.store") }}" enctype="multipart/form-data">
            @csrf

            @include('admin.users.partials.create')

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
