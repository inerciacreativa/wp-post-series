<?php

namespace ic\Plugin\PostSeries;

use ic\Framework\Plugin\PluginClass;

/**
 * Class Frontend
 *
 * @package ic\Plugin\PostSeries
 *
 * @method PostSeries getPlugin()
 */
class Frontend extends PluginClass
{

	/**
	 * @inheritdoc
	 */
	protected function onCreation()
	{
		$this->setHook()->on('pre_get_posts', function (\WP_Query $query) {
			if ($query->is_archive() && $query->is_tax(PostSeries::TAX_TYPE)) {
				$query->set('order', $this->getOption('tax.order'));
			}
		})->on('the_content', 'addToContent');
	}

	/**
	 * Add the series info to the post content.
	 *
	 * @param string $content
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function addToContent(string $content): string
	{
		if (!is_main_query() || $this->getOption('show.position') === 'none' || !is_singular($this->getOption('tax.posts'))) {
			return $content;
		}

		$post     = get_post();
		$template = $this->getPlugin()->getTemplate($post);

		if ($this->getOption('show.position') === 'after') {
			$content .= $template;
		} else {
			$content = $template . $content;
		}

		return $content;
	}

}