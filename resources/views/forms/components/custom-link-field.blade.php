@php
    $state = $getState() ?? [];
    $statePath = $getStatePath();
@endphp
<div class="fi-fo-field"
    x-data="{
        resource_url: @js(data_get($state, 'resource_url', '')),
        actual_url: @js(data_get($state, 'actual_url', '')),
        title: @js(data_get($state, 'title', '')),
    }"
    x-init="
        $watch('resource_url', val => {
            if(val) actual_url = val;
            $wire.set('{{ $statePath }}.resource_url', val);
            if(val) $wire.set('{{ $statePath }}.actual_url', val);
        });
        $watch('actual_url', val => {
            $wire.set('{{ $statePath }}.actual_url', val);
        });
        $watch('title', val => {
            $wire.set('{{ $statePath }}.title', val);
        });
    "
>
    <label for="{{ $statePath }}_title">Link</label>
    <x-filament::input.wrapper :attributes="$attributes">
        <x-filament::input type="text"
            x-model="title"
            id="{{ $statePath }}_title"
            dusk="{{ $statePath }}_title"
            label="Title"
            placeholder="Inserisci il titolo..." />
    </x-filament::input.wrapper>
    <x-filament::input.wrapper :attributes="$attributes">
        <x-filament::input type="text"
            x-model="actual_url"
            id="{{ $statePath }}_actual_url"
            dusk="{{ $statePath }}_actual_url"
            label="Actual URL"
            placeholder="Inserisci l'URL reale..." />
    </x-filament::input.wrapper>
    <x-filament::input.wrapper :attributes="$attributes">
        <x-filament::input.select
            x-model="resource_url"
        >
            <option value="">Seleziona una risorsa...</option>
            @foreach(config('searchable_links') as $modelClass)
                @if($modelClass::$searchable ?? false)
                    @foreach($modelClass::where('status', 'published')->get() as $item)
                        <option value="{{ $item->public_url }}">
                            {{ class_basename($modelClass) }}: {{ $item->title }}
                        </option>
                    @endforeach
                @endif
            @endforeach
        </x-filament::input.select>
    </x-filament::input.wrapper>
</div>
