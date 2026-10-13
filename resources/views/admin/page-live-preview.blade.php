@vite(['resources/js/app.js', 'resources/css/master.css'])

<div class="border rounded-lg p-4 bg-gray-50">
    <div class="mb-4 flex items-center justify-between">
        <h4 class="font-semibold text-gray-900">Live Preview - Template: {{ ucfirst($template) }}</h4>
        <div class="text-sm text-gray-500">
            Template file: <code class="bg-gray-200 px-2 py-1 rounded">preview-templates/{{ $template }}.blade.php</code>
        </div>
    </div>
    
    <div class="border rounded bg-white shadow-sm">
        <div class="bg-gray-100 px-3 py-2 border-b text-xs font-mono text-gray-600">
            Preview rendered from: resources/views/preview-templates/{{ $template }}.blade.php
        </div>
        
        <div class="p-4">
            @if(view()->exists('preview-templates.' . $template))
                {{-- Includi il template specifico della pagina --}}
                @include('preview-templates.' . $template, ['page' => $page])
            @else
                <div class="bg-red-50 border border-red-200 rounded p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-medium text-red-800">Template Error</span>
                    </div>
                    <div class="mt-2 text-sm text-red-700">
                        Template file <strong>{{ $template }}.blade.php</strong> not found.
                    </div>
                    <div class="mt-3">
                        <p class="text-xs text-red-600">
                            Available templates in <code>resources/views/pages/templates/</code>:
                        </p>
                        <ul class="text-xs text-red-600 mt-1 list-disc list-inside">
                            @foreach(['default', 'homepage', 'portfolio', 'contact', 'about'] as $tmpl)
                                <li>{{ $tmpl }}.blade.php</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>