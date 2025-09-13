@extends(config('filament-users.email.layout', 'filament-users::layouts.email'))

@section('content')
    <p>
        {!! $content !!}
    </p>
@endsection
