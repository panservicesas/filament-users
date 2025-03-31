<div>
    @if($getState())
        @foreach (json_decode($getState()) as $role)
            <x-filament::badge>
                {{ Str::headline($role->name) }}
            </x-filament::badge>
        @endforeach
    @endif
</div>
