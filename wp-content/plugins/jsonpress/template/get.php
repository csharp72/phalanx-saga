<?php
defined('ABSPATH') or die();

$api_get = get_query_var('get');
$array = Array();

$args = isset($_GET['args']) ? (array)$_GET['args'] : Array();
if( 'wp_list_categories' == $api_get ) {
	$args['echo'] = false;
	$array['results'] = wp_list_categories($args);
	jsonpress_json_output($array); 
}
else if( 'categories' == $api_get ) {
	$array['results'] = get_categories($args);
	jsonpress_json_output($array);
}

else if( 'wp_list_pages' == $api_get ) {
	$args['echo'] = false;
	$array['results'] = wp_list_pages($args);
	jsonpress_json_output($array);
}

else if( 'wp_nav_menu' == $api_get ) {
	$args['echo'] = false;
	$array['results'] = wp_nav_menu($args);
	jsonpress_json_output($array);
}

else if( 'wp_get_nav_menu_items' == $api_get ) {
	$menu_id = isset($_GET['menu_id']) ? (int)$_GET['menu_id'] : 0;
	$menu_items = wp_get_nav_menu_items($menu_id , $args);
	$menu = '';
	
	foreach( (array)$menu_items as $items) {
		$menu .=  '<li><a href="'.$items->url.'">'.$items->title.'</a></li>';
	}
	

	$array['results'] =  $menu;
	
	jsonpress_json_output($array);
}

