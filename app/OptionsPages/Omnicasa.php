<?php

namespace Otomaties\Omnicasa\OptionsPages;

use Otomaties\Omnicasa\Enums\ContactRequestFields;
use Otomaties\Omnicasa\OptionsPages\Abstracts\OptionsPage as AbstractsOptionsPage;
use Otomaties\Omnicasa\OptionsPages\Contracts\OptionsPage;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Omnicasa extends AbstractsOptionsPage implements OptionsPage
{
    protected string $slug = 'otomaties-omnicasa-sync-settings';

    public function __construct()
    {
        $this->title = __('Omnicasa', 'otomaties-omnicasa-sync');
        $this->menuTitle = __('Omnicasa', 'otomaties-omnicasa-sync');
    }

    protected function fields(FieldsBuilder $fieldsBuilder) : FieldsBuilder
    {
        $fieldsBuilder
            ->addTab('contact', [
                'placement' => 'left',
                'label' => __('Contact', 'otomaties-omnicasa-sync'),
            ]);
            
        $htmlForms = function_exists('hf_get_forms') ? hf_get_forms() : [];

        $availableContactfields = array_map(function ($field) {
            return $field->name;
        }, ContactRequestFields::cases());
        
        $fields = [];
        if (function_exists('hf_get_forms') && get_field('omnicasa_htmlform', 'option')) {
            $form = hf_get_form(get_field('omnicasa_htmlform', 'option'));
            $fields = $form->get_required_fields();
        }

        $fieldsBuilder
            ->addSelect('omnicasa_htmlform', [
                'label' => __('Form', 'otomaties-omnicasa-sync'),
                'choices' => array_reduce($htmlForms, function ($carry, $form) {
                    $carry[] = [$form->id => $form->title];
                    return $carry;
                }, []),
                'allow_null' => true,
            ]);
        
        if (count($fields) > 0) {
            $fieldMappingGroup = $fieldsBuilder
                ->addGroup('omnicasa_htmlform_field_mapping', [
                    'label' => __('Form', 'otomaties-omnicasa-sync'),
                ]);
            foreach ($availableContactfields as $field) {
                $fieldMappingGroup->addSelect($field, [
                    'label' => __($field, 'otomaties-omnicasa-sync'),
                    'choices' => $fields,
                    'allow_null' => true,
                ]);
            }
            $fieldMappingGroup->endGroup();
        }

        return $fieldsBuilder;
    }
}
