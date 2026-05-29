<?php


class Ivy_Nav_Walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {}
	function end_lvl( &$output, $depth = 0, $args = array() ) {}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$classes = 'px-3 py-2 hover:bg-primary hover:text-white rounded';
		$output .= '<a href="' . esc_attr($item->url) . '" class="' . $classes . '">' . esc_html($item->title) . '</a>';
	}
	function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {}
}

