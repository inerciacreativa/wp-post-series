<?php

namespace ic\Plugin\PostSeries;

/**
 * @param \WP_Post $post
 *
 * @return string
 */
function template(\WP_Post $post): string
{
	return PostSeries::getInstance()->getTemplate($post);
}

/**
 * @param \WP_Post $post
 *
 * @return null|\WP_Term
 */
function series(\WP_Post $post): ?\WP_Term
{
	return PostSeries::getInstance()->getSeries($post);
}

/**
 * @param \WP_Term      $series
 * @param \WP_Post|null $post
 *
 * @return array
 */
function posts(\WP_Term $series, \WP_Post $post = null): array
{
	return PostSeries::getInstance()->getPosts($series, $post);
}