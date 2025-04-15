<?php

namespace Otomaties\Omnicasa\Services;

use Omnicasa\Omnicasa;
use Otomaties\Omnicasa\Database\PropertyType;
use Otomaties\Omnicasa\Database\Status;
use Otomaties\Omnicasa\Database\Substatus;
use Otomaties\Omnicasa\Database\WebId;
use Otomaties\Omnicasa\Enums\Status as StatusEnum;
use Otomaties\Omnicasa\Plugin;
use Otomaties\WpSyncPosts\Syncer;

class SyncService
{
    public function __construct(private Omnicasa $client, private Plugin $plugin)
    {
        //
    }

    public function syncProperties(): void
    {
        $syncer = new Syncer('property');

        collect($this->client->getPropertyList())
            ->filter(function ($property) {
                $syncableStatuses = collect(StatusEnum::cases())
                    ->filter(fn ($status) => $status->syncable())
                    ->keys()
                    ->toArray();

                return in_array($property->Status, $syncableStatuses);
            })
            ->each(function ($property) use ($syncer) {
                $args = [
                    'post_title' => $property->Ident,
                    'post_content' => $property->DescriptionA,
                    'post_date' => gmdate('Y-m-d H:i:s', $property->CreatedDate->getTimestamp()),
                    'post_modified' => gmdate('Y-m-d H:i:s', $property->LastChangedDate->getTimestamp()),
                    'post_status' => 'publish',
                    'meta_input' => [
                        'omnicasa_id' => $property->ID,
                    ],
                    'media' => [],
                ];

                $args['meta_input'] = array_merge($args['meta_input'], $this->buildMeta($property));

                $args['media'][] = [
                    'key' => false,
                    'featured' => true,
                    'url' => $property->LargePicture,
                    'group' => 'synced_images',
                ];

                if (isset($property->XLargePictureItems) && is_array($property->XLargePictureItems) && count($property->XLargePictureItems) > 0) {
                    foreach ($property->XLargePictureItems as $pictureItem) {
                        $args['media'][] = [
                            'key' => $pictureItem->ID,
                            'featured' => false,
                            'url' => $pictureItem->Url,
                            'group' => 'synced_images',
                        ];
                    }
                }

                $existingPostQuery = [
                    'by' => 'meta_value',
                    'key' => 'omnicasa_id',
                    'value' => $property->ID,
                ];

                $syncer->addPost($args, $existingPostQuery);
            });

        $syncer->execute();
    }

    public function syncProjects($limit = null): void
    {

        $syncer = new Syncer('project');

        collect($this->client->getProjectList())
            ->filter(function ($project) {
                return $project->PublishInternet;
            })
            ->each(function ($project) use ($syncer) {
                $args = [
                    'post_title' => $project->Name,
                    'post_content' => $project->Description,
                    'post_status' => 'publish',
                    'meta_input' => [
                        'omnicasa_id' => $project->ID,
                    ],
                    'media' => [],
                ];

                $args['meta_input'] = $this->buildMeta($project);

                $args['media'][] = [
                    'key' => false,
                    'featured' => true,
                    'url' => $project->LargePicture,
                    'group' => 'synced_images',
                ];

                if (property_exists($project, 'XLargePictureItems') && is_array($project->XLargePictureItems) && count($project->XLargePictureItems) > 0) {
                    foreach ($project->XLargePictureItems as $pictureItem) {
                        $args['media'][] = [
                            'key' => $pictureItem->ID,
                            'featured' => false,
                            'url' => $pictureItem->Url,
                            'group' => 'synced_images',
                        ];
                    }
                }

                $existingPostQuery = [
                    'by' => 'meta_value',
                    'key' => 'omnicasa_id',
                    'value' => $project->ID,
                ];

                $syncer->addPost($args, $existingPostQuery);
            });

        $syncer->execute();
    }

    public function syncStatuses($limit = null): void
    {
        foreach ($this->client->getStatusList() as $status) {
            $this->plugin->make(Status::class)->updateOrCreate([
                'id' => $status->ID,
                'name' => $status->Name ?? '',
            ], [
                'id' => $status->ID,
            ]);
        }

        foreach ($this->client->getSubstatusList() as $status) {
            $this->plugin->make(Substatus::class)->updateOrCreate([
                'id' => $status->ID,
                'name' => $status->Name ?? '',
                'short_name' => $status->ShortName ?? null,
                'marquee' => $status->Marquee ?? null,
                'show_on_map' => $status->ShowOnMap ?? false,
            ], [
                'id' => $status->ID,
            ]);
        }
    }

    public function syncPropertyTypes($limit = null): void
    {
        foreach ($this->client->getTypeOfPropertyList() as $propertyType) {
            $this->plugin->make(PropertyType::class)->updateOrCreate([
                'id' => $propertyType->ID,
                'name' => $propertyType->Name ?? '',
                'web_id' => $propertyType->WebID ?? null,
                'abbr' => $propertyType->Abbr ?? null,
                'original_abbr' => $propertyType->OriginalAbbr ?? null,
                'parent_id' => $propertyType->ParentID ?? 0,
            ], [
                'id' => $propertyType->ID,
            ]);
        }
    }

    public function syncWebIds($limit = null): void
    {
        foreach ($this->client->getWebIDList() as $item) {
            $this->plugin->make(WebId::class)->updateOrCreate([
                'id' => $item->ID,
                'web_id' => $item->WebID ?? null,
                'name' => $item->Name ?? '',
                'show_order' => $item->ShowOrder ?? null,
                'sale' => $item->Sale ?? false,
                'rent' => $item->Rent ?? false,
            ], [
                'id' => $item->ID,
            ]);
        }
    }

    private function buildMeta($property)
    {
        $meta = [];
        collect($property->getData())
            ->filter(function ($value, $key) {
                $imageKeys = [
                    'SmallPicture',
                    'SmallPictures',
                    'MediumPicture',
                    'MediumPictures',
                    'LargePicture',
                    'LargePictures',
                    'SmallPictureItem',
                    'SmallPictureItems',
                    'MediumPictureItem',
                    'MediumPictureItems',
                    'LargePictureItem',
                    'LargePictureItems',
                    'XLargePictureItem',
                    'XLargePictureItems',
                ];

                return ! in_array($key, $imageKeys);
            })
            // filter empty values
            ->filter(function ($value, $key) {
                return ! empty($value) && $value !== '-';
            })
            // Convert DateTime objects to string
            ->map(function ($value, $key) {
                if ($value instanceof \DateTime) {
                    $value = gmdate('Y-m-d H:i:s', $value->getTimestamp());
                }

                return $value;
            })
            // Convert ResponseObjects to array
            ->map(function ($value, $key) {
                if ($value instanceof \Omnicasa\Response\ResponseObject) {
                    $value = $value->getData();
                }

                return $value;
            })
            ->each(function ($value, $key) use (&$meta) {
                $meta[$key] = $value;
            });

        return $meta;
    }
}
