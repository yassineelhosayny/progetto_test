<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function index(Request $request): Response
    {
        $locale = app()->getLocale();

        $projects = Project::published()
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->through(fn (Project $project): array => $this->serializeProject($project, $locale));

        return Inertia::render('Projects/Index', [
            'projects' => $projects,
            'locale' => $locale,
            'page' => [
                'title' => $locale === 'it' ? 'Progetti' : 'Projects',
                'description' => $locale === 'it'
                    ? 'Scopri i nostri progetti.'
                    : 'Explore our projects.',
            ],
        ]);
    }

    public function show(string $slug): Response
    {
        $locale = app()->getLocale();

        $project = Project::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProjects = Project::published()
            ->where('id', '!=', $project->id)
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get()
            ->map(fn (Project $relatedProject): array => $this->serializeProject($relatedProject, $locale))
            ->values();

        return Inertia::render('Projects/Show', [
            'project' => $this->serializeProject($project, $locale, false),
            'relatedProjects' => $relatedProjects,
            'locale' => $locale,
            'indexUrl' => route('projects.index'),
        ]);
    }

    private function serializeProject(Project $project, string $locale, bool $summary = true): array
    {
        $description = $this->translation($project, 'description', $locale);

        return [
            'id' => $project->id,
            'title' => $this->translation($project, 'title', $locale),
            'slug' => $project->slug,
            'description' => $description,
            'content' => $summary ? null : $this->translation($project, 'content', $locale),
            'featured_image' => $project->featured_image,
            'gallery' => $summary ? [] : ($project->gallery ?? []),
            'status' => $project->status,
            'published_at' => optional($project->published_at)->toIso8601String(),
            'url' => $project->public_url,
            'meta_title' => $this->translation($project, 'meta_title', $locale) ?: $this->translation($project, 'title', $locale),
            'meta_description' => $this->translation($project, 'meta_description', $locale) ?: $description,
            'meta_keywords' => $this->translation($project, 'meta_keywords', $locale),
        ];
    }

    private function translation(Project $project, string $field, string $locale): string
    {
        try {
            $value = $project->getTranslation($field, $locale, false)
                ?: $project->getTranslation($field, 'it', false);

            if (! empty($value)) {
                return $value;
            }

            $translations = $project->getTranslations($field);

            if (is_array($translations) && count($translations) > 0) {
                return (string) (reset($translations) ?: '');
            }
        } catch (\Throwable $e) {
            $raw = $project->getAttributes()[$field] ?? null;

            if (is_string($raw)) {
                $decoded = json_decode($raw, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return (string) ($decoded[$locale] ?? $decoded['it'] ?? reset($decoded) ?: '');
                }

                return $raw;
            }
        }

        return '';
    }
}
