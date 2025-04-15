<?php

namespace Otomaties\Omnicasa\Modules;

use Otomaties\Omnicasa\Enums\ContactRequestFields;
use Otomaties\Omnicasa\Modules\Abstracts\Module;

class Admin extends Module
{
    public function init()
    {
        $this->loader->addAction('hf_form_success', $this, 'contactRequest', 10, 2);
    }

    public function contactRequest($submission, $form): void
    {
        if ($form->ID !== (int) get_field('omnicasa_htmlform', 'option')) {
            return;
        }

        $fieldMapping = get_field('omnicasa_htmlform_field_mapping', 'option');
        $args = collect(ContactRequestFields::cases())
            ->filter(function ($field) use ($fieldMapping) {
                return $fieldMapping[$field->name];
            })
            ->mapWithKeys(function ($field) use ($fieldMapping, $submission) {
                return [$field->name => $submission->data[$fieldMapping[$field->name] ?? null]];
            })
            ->toArray();

        $this->client->contactOnMe($args);
    }
}
