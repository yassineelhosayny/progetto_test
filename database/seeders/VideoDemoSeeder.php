<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\VideoDemo;
use App\Models\VideoSource;
use App\Models\VideoSubcategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $client = Client::query()->updateOrCreate(
                ['slug' => 'agras'],
                [
                    'client_name' => 'Agras',
                    'is_active' => true,
                ],
            );

            $videoDemo = VideoDemo::query()->updateOrCreate(
                ['slug' => 'agras-video'],
                [
                    'client_id' => $client->id,
                    'title' => 'AgrasVideo',
                    'logo' => null,
                    'is_active' => true,
                    'requires_password' => false,
                    'password' => null,
                    'sort_order' => 1,
                ],
            );

            $categoryUno = VideoCategory::query()->updateOrCreate(
                [
                    'video_demo_id' => $videoDemo->id,
                    'name' => 'Nuova Categoria',
                ],
                [
                    'sort_order' => 1,
                    'is_active' => true,
                ],
            );

            $categoryDue = VideoCategory::query()->updateOrCreate(
                [
                    'video_demo_id' => $videoDemo->id,
                    'name' => 'Cat 2',
                ],
                [
                    'sort_order' => 2,
                    'is_active' => true,
                ],
            );

            $subcategoryUno = VideoSubcategory::query()->updateOrCreate(
                [
                    'video_category_id' => $categoryUno->id,
                    'name' => 'Nuova',
                ],
                [
                    'sort_order' => 1,
                    'is_active' => true,
                ],
            );

            $subcategoryDue = VideoSubcategory::query()->updateOrCreate(
                [
                    'video_category_id' => $categoryDue->id,
                    'name' => 'Cat2',
                ],
                [
                    'sort_order' => 1,
                    'is_active' => true,
                ],
            );

            $videoUno = Video::query()->updateOrCreate(
                [
                    'video_subcategory_id' => $subcategoryUno->id,
                    'name' => 'test1',
                ],
                [
                    'description' => null,
                    'sort_order' => 1,
                    'is_active' => true,
                ],
            );

            $videoDue = Video::query()->updateOrCreate(
                [
                    'video_subcategory_id' => $subcategoryUno->id,
                    'name' => 'Test2',
                ],
                [
                    'description' => null,
                    'sort_order' => 2,
                    'is_active' => true,
                ],
            );

            $videoTre = Video::query()->updateOrCreate(
                [
                    'video_subcategory_id' => $subcategoryDue->id,
                    'name' => 'Video 552762746',
                ],
                [
                    'description' => null,
                    'sort_order' => 1,
                    'is_active' => true,
                ],
            );

            $this->createVimeoSource(
                video: $videoUno,
                legacyVideoId: 4,
                legacyVersionId: 2,
                externalId: '548884568',
                label: 'test2',
                versionDate: '2021-07-09 12:21:00',
                isCurrent: true,
                sortOrder: 1,
            );

            $this->createVimeoSource(
                video: $videoUno,
                legacyVideoId: 4,
                legacyVersionId: 5,
                externalId: '552762746',
                label: 'test1',
                versionDate: '2021-07-02 09:25:00',
                isCurrent: false,
                sortOrder: 2,
            );

            $this->createVimeoSource(
                video: $videoDue,
                legacyVideoId: 6,
                legacyVersionId: 8,
                externalId: '552762746',
                label: '552762746',
                versionDate: '2021-07-05 00:00:00',
                isCurrent: true,
                sortOrder: 1,
            );

            $this->createVimeoSource(
                video: $videoTre,
                legacyVideoId: 8,
                legacyVersionId: 10,
                externalId: '552762746',
                label: 'Versione Vimeo',
                versionDate: '2021-07-28 14:55:00',
                isCurrent: true,
                sortOrder: 1,
            );
        });
    }

    private function createVimeoSource(
        Video $video,
        int $legacyVideoId,
        int $legacyVersionId,
        string $externalId,
        string $label,
        string $versionDate,
        bool $isCurrent,
        int $sortOrder,
    ): void {
        VideoSource::query()->updateOrCreate(
            [
                'video_id' => $video->id,
                'provider' => 'vimeo',
                'external_id' => $externalId,
            ],
            [
                'label' => $label,
                'provider_library_id' => null,
                'source_url' => "https://player.vimeo.com/video/{$externalId}",
                'thumbnail_url' => null,
                'upload_method' => 'manual',
                'upload_status' => 'ready',
                'upload_error' => null,
                'version_date' => $versionDate,
                'is_current' => $isCurrent,
                'is_active' => true,
                'sort_order' => $sortOrder,
                'metadata' => [
                    'source' => 'legacy-demo-backup',
                    'legacy_video_id' => $legacyVideoId,
                    'legacy_version_id' => $legacyVersionId,
                ],
            ],
        );
    }
}
