<tr>
    <th>
        {{ trans('cruds.user.fields.name') }}
    </th>
    <td>
        {{ $user->name }}
    </td>
</tr>
<tr>
    <th>
        {{ trans('cruds.user.fields.email') }}
    </th>
    <td>
        {{ $user->email }}
    </td>
</tr>
<tr>
    <th>
        {{ trans('cruds.user.fields.phone') }}
    </th>
    <td>
        {{ $user->phone }}
    </td>
</tr>
<tr>
    <th>
        {{ trans('cruds.user.fields.identity') }}
    </th>
    <td>
        @foreach($user->identity as $key => $media)
            <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                <img src="{{ $media->getUrl('thumb') }}">
            </a>
        @endforeach
    </td>
</tr>
