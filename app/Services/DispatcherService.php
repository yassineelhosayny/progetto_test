<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class DispatcherService
{
    public function handle(?string $tag): RedirectResponse
    {
        if (blank($tag)) {
            return redirect()->route('homepage.inertia');
        }

        $destination = $this->resolveDestination($tag);

        if (!$destination) {
            return redirect()->route('homepage.inertia');
        }

        if ($destination['type'] === 'route') {
            return redirect()->route(
                $destination['value'],
                $destination['params'] ?? []
            );
        }

        if ($destination['type'] === 'url') {
            return Redirect::to($destination['value']);
        }

        return redirect()->route('homepage.inertia');
    }

    protected function resolveDestination(string $tag): ?array
    {
        $map = config('qr-dispatcher.tags', []);

        return $map[$tag] ?? null;
    }
}