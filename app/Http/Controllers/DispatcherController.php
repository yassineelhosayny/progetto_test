<?php

namespace App\Http\Controllers;

use App\Services\DispatcherService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DispatcherController extends Controller
{
    public function __construct(
        protected DispatcherService $dispatcherService
    ) {
    }

    public function dispatch(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tag' => ['required', 'string', 'max:100'],
        ]);

        return $this->dispatcherService->handle($validated['tag']);
    }
}