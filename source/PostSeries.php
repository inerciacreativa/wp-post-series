<?php

namespace ic\Plugin\PostSeries;

use ic\Framework\Custom\Taxonomy;
use ic\Framework\Html\Tag;
use ic\Framework\Plugin\Plugin;
use ic\Framework\Support\Template;

/**
 * Class PostSeries
 *
 * @package ic\Plugin\PostSeries
 */
class PostSeries extends Plugin
{

	public const TAX_TYPE = 'post_series';

	/**
	 * @inheritdoc
	 */
	protected function onCreation()
	{
		parent::onCreation();

		$this->setOptions([
			'tax'      => [
				'slug'    => 'series',
				'posts'   => ['post'],
				'archive' => false,
				'order'   => 'DESC',
			],
			'show'     => [
				'scheduled' => true,
				'position'  => 'after',
			],
			'template' => [
				'type' => 'php',
				'file' => 'templates/post-series',
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	protected function onInit()
	{
		$singular = _x('Series', 'singular', $this->id);
		$plural   = _x('Series', 'plural', $this->id);

		Taxonomy::create(self::TAX_TYPE, $this->getOption('tax.posts'))
		        ->nouns($singular, $plural)
		        ->rewrite($this->getOption('tax.slug'))
		        ->has_archive($this->getOption('tax.archive'))
		        ->labels([
			        'all_items'     => sprintf(__('All %s', $this->id), strtolower($plural)),
			        'add_new_item'  => sprintf(__('Add New %s', $this->id), strtolower($singular)),
			        'new_item_name' => sprintf(__('New %s Name', $this->id), strtolower($singular)),
		        ])
		        ->meta_box(false, false);
	}

	/**
	 * Get the template with the series list of the post.
	 *
	 * @param \WP_Post $post
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	public function getTemplate(\WP_Post $post): string
	{
		$series = $this->getSeries($post);

		if (!$series) {
			return '';
		}

		$posts = $this->getPosts($series, $post);
		if (\count($posts) < 2) {
			return '';
		}

		$name        = $series->name;
		$header      = __('This is post %d of %d in the series <em>&ldquo;%s&rdquo;</em>', $this->id);
		$description = term_description($series->term_id, self::TAX_TYPE);

		if ($this->getOption('tax.archive')) {
			$name = Tag::a(['href' => get_term_link($series->term_id, self::TAX_TYPE)], $name);
		}

		if ($description) {
			$description = wpautop(wptexturize($description));
		}

		$links     = $this->getLinks($post, $posts);
		$header    = sprintf($header, $links['current'], $links['total'], $name);
		$variables = array_merge($links, compact('name', 'header', 'description'));
		$template  = $this->getOption('template.file') . '.' . $this->getOption('template.type');

		return Template::render($template, $variables, $this->getAbsolutePath());
	}

	/**
	 * @param \WP_Post $post
	 * @param array    $posts
	 *
	 * @return array
	 */
	public function getLinks(\WP_Post $post, array $posts): array
	{
		$links    = array_flip($posts);
		$total    = 0;
		$current  = 0;
		$schedule = __('%s &ndash;&nbsp;<em>Scheduled for %s</em>', $this->id);

		foreach ($links as $id => $empty) {
			$total++;
			$title = get_the_title($id);

			if ($post !== null && $id === $post->ID) {
				$links[$id] = Tag::strong([], $title);
				$current    = $total;
			} else {
				if (get_post_status($id) !== 'publish') {
					$links[$id] = sprintf($schedule, $title, get_the_date('', $id));
				} else {
					$links[$id] = Tag::a(['href' => get_permalink($id)], $title);
				}
			}
		}

		return compact('links', 'total', 'current');
	}

	/**
	 * Return the series of the post.
	 *
	 * @param \WP_Post $post
	 *
	 * @return null|\WP_Term
	 */
	public function getSeries(\WP_Post $post): ?\WP_Term
	{
		$series = wp_get_post_terms($post->ID, self::TAX_TYPE);

		if (empty($series) || is_wp_error($series)) {
			return null;
		}

		return current($series);
	}

	/**
	 * Return an array with the posts for the series given.
	 *
	 * @param \WP_Term $series
	 * @param \WP_Post $post
	 * @param bool     $fields
	 *
	 * @return array
	 */
	public function getPosts(\WP_Term $series, \WP_Post $post = null, $fields = false): array
	{
		$status = ['publish'];

		if ($this->getOption('show.scheduled')) {
			$status[] = 'future';
		}

		return get_posts([
			'post_type'      => $post === null ? 'any' : $post->post_type,
			'posts_per_page' => -1,
			'fields'         => $fields ? 'all' : 'ids',
			'no_found_rows'  => true,
			'orderby'        => 'date',
			'order'          => 'asc',
			'post_status'    => $status,
			'tax_query'      => [
				[
					'taxonomy' => 'post_series',
					'field'    => 'term_id',
					'terms'    => $series->term_id,
				],
			],
		]);
	}

}