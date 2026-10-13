{{-- resources/views/components/image.blade.php --}}
{{-- Image Component with automatic path resolution --}}

@props([
    'src' => null,
    'alt' => '',
    'class' => '',
    'loading' => 'lazy'
])

@if($src)
    @php
        // Handle different image path formats
        $imagePath = $src;
        
        // If it's already a full URL, use as is
        if (str_starts_with($src, 'http://') || str_starts_with($src, 'https://')) {
            $imagePath = $src;
        }
        // If it starts with storage/, use asset()
        elseif (str_starts_with($src, 'storage/')) {
            $imagePath = asset($src);
        }
        // If it's just a filename, assume it's in storage/
        elseif (!str_starts_with($src, '/')) {
            $imagePath = asset('storage/' . $src);
        }
        // If it starts with /, use asset()
        else {
            $imagePath = asset($src);
        }
    @endphp
    
    <img src="{{ $imagePath }}" 
         alt="{{ $alt }}" 
         class="{{ $class }}"
         loading="{{ $loading }}"
         {{ $attributes }}>
@else
    <div class="bg-gray-200 flex items-center justify-center {{ $class }}" {{ $attributes }}>
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
    </div>
@endif