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
	protected function configure(): void
	{
		parent::configure();

		$this->hook()
		     ->on('pre_get_posts', 'setArchiveOrder')
		     ->on('the_content', 'addToContent');
	}

	/**
	 * @param \WP_Query $query
	 */
	protected function setArchiveOrder(\WP_Query $query): void
	{
		if ($query->is_archive() && $query->is_tax(PostSeries::TAX_TYPE)) {
			$query->set('order', $this->getOption('tax.order'));
		}
	}

	/**
	 * Add the series info to the post content.
	 *
	 * @param string $content
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 * @throws \RuntimeException
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