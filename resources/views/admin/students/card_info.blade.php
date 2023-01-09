<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    <div style="display: flex; flex-direction: row;justify-content: center;">
        <div style="width: 20%; text-align: center; padding: 17px;">
            <img src="{{$user->photo ? $user->photo->getUrl('preview') : ''}}" class="rounded" width="100" >
            <h3>{{ $user->name }}</h3>
            <h5>{{ $student->grade ? \App\Models\Student::GRADE_SELECT[$student->grade] : '' }} - {{ $student->class ? \App\Models\Student::CLASS_SELECT[$student->class] : '' }}</h5>
        </div>
        <div style="padding: 36px 20px;">
            {!! QrCode::size(120)->generate($user->id) !!}
            {{-- <img src="{{$setting->logo ? $setting->logo->getUrl('')  : ''}}" width="50" alt=""> --}}
        </div>
    </div>

</body>
</html>
