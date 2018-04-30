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
	 *
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 */
	protected function initialize(): void
	{
		Settings::siteOptions($this->id(), $this->getOptions(), $this->name())
		        ->addTab(null, function (Tab $tab) {
			        $tab->addSection(null, function (Section $section) {
				        $section->post_types('tax.posts', __('Post types', $this->id()), ['exclude' => 'attachment']);
			        })->addSection('show', function (Section $section) {
				        $section->title(__('Visualization options', $this->id()))
				                ->checkbox('show.scheduled', __('Include scheduled posts', $this->id()))
				                ->choices('show.position', __('Position of the list', $this->id()), [], [
					                'before' => __('Before the content', $this->id()),
					                'after'  => __('After the content', $this->id()),
					                'none'   => __("Don't show", $this->id()),
				                ]);
			        })->addSection('archive', function (Section $section) {
				        $section->title(__('Archive', $this->id()))
				                ->checkbox('tax.archive', __('Enable archives for the series', $this->id()))
				                ->choices('tax.order', __('Order of the posts', $this->id()), [], [
					                'DESC' => __('Descending', $this->id()),
					                'ASC'  => __('Ascending', $this->id()),
				                ]);
			        })->addSection('template', function (Section $section) {
				        $section->title(__('Template', $this->id()))
				                ->choices('template.type', __('Type', $this->id()), [], Template::types())
				                ->text('template.file', __('Filename', $this->id()), [
					                'class'       => 'regular-text code',
					                'description' => __('Do not include the extension.', $this->id()),
				                ]);
			        });
		        });

		Settings::optionsPermalink($this->getOptions())
		        ->addSection('series', function (Section $section) {
			        $section->title(__('Custom structures for posts series', $this->id()))
			                ->text('tax.slug', __('Series base', $this->id()), ['class' => 'regular-text code']);
		        });

	}

}