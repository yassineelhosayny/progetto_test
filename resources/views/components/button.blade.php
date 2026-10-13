@props([
	'data' => null,
])

@if(is_array($data) && !empty($data['title']))
	<a href="{{ $data['actual_url'] ?? '#' }}" class="inline-flex items-center justify-center px-8 py-3 border-2 border-teal-400 text-teal-400 font-medium text-lg rounded-none hover:bg-teal-400 hover:text-slate-800 transition-all duration-300 tracking-wide">
        {{ $data['title'] }}
    </a>
@endif