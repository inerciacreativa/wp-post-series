<?php

namespace ic\Plugin\PostSeries;

use ic\Framework\Plugin\PluginClass;
use ic\Framework\Settings\Form\Section;
use ic\Framework\Settings\Form\Tab;
use ic\Framework\Settings\Settings;
use ic\Framework\Support\Template;

/**
 * Class Backend
 *
 * @package ic\Plugin\PostSeries
 */
class Backend extends PluginClass
{

	/**
	 * @inheritdoc
	 */
	protected function onInit()
	{
		Settings::siteOptions($this->id, $this->getOptions(), $this->name)
		        ->tab(null, function (Tab $tab) {
			        $tab->section(null, function (Section $section) {
				        $section->post_types('tax.posts', __('Post types', $this->id), ['exclude' => 'attachment']);
			        })->section('show', function (Section $section) {
				        $section->title(__('Visualization options', $this->id))
				                ->checkbox('show.scheduled', __('Include scheduled posts', $this->id))
				                ->choices('show.position', __('Position of the list', $this->id), [], [
					                'before' => __('Before the content', $this->id),
					                'after'  => __('After the content', $this->id),
					                'none'   => __("Don't show", $this->id),
				                ]);
			        })->section('archive', function (Section $section) {
				        $section->title(__('Archive', $this->id))
				                ->checkbox('tax.archive', __('Enable archives for the series', $this->id))
				                ->choices('tax.order', __('Order of the posts', $this->id), [], [
					                'DESC' => __('Descending', $this->id),
					                'ASC'  => __('Ascending', $this->id),
				                ]);
			        })->section('template', function (Section $section) {
				        $section->title(__('Template', $this->id))
				                ->choices('template.type', __('Type', $this->id), [], Template::types())
				                ->text('template.file', __('Filename', $this->id), [
					                'class'       => 'regular-text code',
					                'description' => __('Do not include the extension.', $this->id),
				                ]);
			        });
		        });

		Settings::optionsPermalink($this->getOptions())
		        ->section('series', function (Section $section) {
			        $section->title(__('Custom structures for posts series', $this->id))
			                ->text('tax.slug', __('Series base', $this->id), ['class' => 'regular-text code']);
		        });

	}

}