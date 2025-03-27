<div>
    @if($getState())
        @foreach (json_decode($getState()) as $user)
            <x-filament::badge>
                {{ Str::headline($user->name) }}
            </x-filament::badge>
        @endforeach
    @endif
</div>
