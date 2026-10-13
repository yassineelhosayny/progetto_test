@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <form action="{{ route('search.index') }}" method="GET" class="mb-4">
            <input
                type="text"
                name="q"
                value="{{ $q }}"
                placeholder="Cerca pagine, news, eventi..."
                class="form-control"
            >
        </form>

        @if ($q !== '')
            <p class="mb-3">
                Risultati per: <strong>{{ $q }}</strong>
            </p>

            @forelse ($results as $result)
                <article class="mb-4 pb-4 border-bottom">
                    <div class="mb-1 text-muted text-uppercase small">
                        {{ $result['type'] }}
                    </div>

                    <h2 class="h5 mb-2">
                        <a href="{{ $result['url'] }}">
                            {{ $result['title'] }}
                        </a>
                    </h2>

                    @if (!empty($result['description']))
                        <p class="mb-2">
                            {{ $result['description'] }}
                        </p>
                    @endif
                </article>
            @empty
                <p>Nessun risultato trovato.</p>
            @endforelse

            {{ $results->links() }}
        @endif
    </div>
@endsection