<?php

namespace Otomaties\Omnicasa\Modules;

use Otomaties\Omnicasa\Enums\ContactRequestFields;
use Otomaties\Omnicasa\Modules\Abstracts\Module;

class Redirects extends Module
{
    public function init()
    {
        $this->loader->addAction('init', $this, 'propertyRedirectRule');
		$this->loader->addAction('query_vars', $this, 'omnicasaQueryVars');
		$this->loader->addAction('template_include', $this, 'propertyRedirect');

    }

    public function propertyRedirectRule(): void
    {
		add_rewrite_rule(
			'^property/([0-9]+)/?$',
			'index.php?property_omnicasa_id=$matches[1]',
			'top'
		);
    }

	public function omnicasaQueryVars($vars): array
	{
		$vars[] = 'property_omnicasa_id';
		return $vars;
	}

	public function propertyRedirect($template)
	{
		global $wp_query;

		if (isset($wp_query->query_vars['property_omnicasa_id'])) {
			$propertyId = $wp_query->query_vars['property_omnicasa_id'];
			$properties = collect(get_posts([
				'post_type' => 'property',
				'meta_query' => [
					[
						'key' => 'omnicasa_id',
						'value' => $propertyId,
						'compare' => '=',
					],
				],
			]));
			$property = $properties->first();

			if ($property) {
				wp_redirect(get_permalink($property->ID), 301);
				exit;
			} else {		
				$wp_query->set_404();
				status_header(404);
				nocache_headers();
		
				return get_404_template();
			}
		}

		return $template;
	}
	
}
