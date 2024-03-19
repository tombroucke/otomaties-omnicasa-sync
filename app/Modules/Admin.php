<?php

namespace Otomaties\Omnicasa\Modules;

use Otomaties\Omnicasa\Modules\Abstracts\Module;
use Otomaties\Omnicasa\Enums\ContactRequestFields;

class Admin extends Module
{
    public function init()
    {
        $this->loader->addAction('hf_form_success', $this, 'contactRequest', 10, 2);
    }

    public function contactRequest($submission, $form)
    {
        if ($form->ID === (int) get_field('omnicasa_htmlform', 'option')) {
            $args = [];
            $fieldMapping = get_field('omnicasa_htmlform_field_mapping', 'option');
            collect(ContactRequestFields::cases())
                ->each(function ($field) use ($fieldMapping, $submission, &$args) {
                    $fieldName = $fieldMapping[$field->name] ?? null;
                    if (!$fieldName) {
                        return;
                    }
                    $args[$field->name] = $submission->data[$fieldName];
                });
            $this->client->contactOnMe($args);
        }
    }
}
