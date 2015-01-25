<?php 
defined('ABSPATH') or die();

$result = array_filter( (array)$wp_query );
$formatted_post = Array();

if( !empty($result['is_404']) ) {
	$result['status'] = 'not found';
}

else {
	$status = Array('status'=>'found');
	$pagination = Array(
				'pagination'=> Array(
				'current_page'       => isset($result['query']['paged']) ? $result['query']['paged'] : isset($result['query_vars']['paged']) ? $result['query_vars']['paged'] : 0,
				'previous_posts_link'=> get_previous_posts_link(),
				'next_posts_link'    => get_next_posts_link(),
				'previous_posts_url' => previous_posts(false),
				'next_posts_url'     => next_posts(0,false),
				'max_num_pages'      => (isset($result['max_num_pages']) OR 0),
				'found_posts'        => $result['found_posts']
		));



	$result = $pagination + $result;
	$result = $status + $result;


	foreach((array)$result['posts'] as $index=>$posts) { 
		foreach($posts as $key=>$value) {

			if(!in_array($key,jsonpress_exclude_columns())) {
			   $formatted_post['posts'][$index][$key] = $value;
			   
					if( 'post_content' == $key ) {
						if(is_singular()) {
							$formatted_post['posts'][$index][$key] = apply_filters('the_content', $value);
							//$formatted_post['posts'][$index]['post_content'] = $value;
						}
						else {
							//excerpt 
							$formatted_post['posts'][$index][$key] = jsonpress_excerpt( $formatted_post['posts'][$index][$key] );
						}
					}
			}
			else {
				unset($formatted_post['posts'][$index][$key]);
			}
			
			$id = $formatted_post['posts'][$index]['ID'];
			
			/* $post_author = $formatted_post['posts'][$index]['post_author'];
			
			if($post_author) {
				$userdata   = get_userdata($post_author);
				$formatted_post['posts'][$index]['author']['name']         = $userdata->user_login;
				$formatted_post['posts'][$index]['author']['display_name'] = $userdata->display_name;
				//$formatted_post['posts'][$index]['author']['posts_url']    = get_author_posts_url($post_author);
			} */
			
			//permalink
			$formatted_post['posts'][$index]['permalink'] = get_permalink($id);
			$post_date          =   isset( $formatted_post['posts'][$index]['post_date'])     ? $formatted_post['posts'][$index]['post_date'] :'';
			$post_post_modified =   isset( $formatted_post['posts'][$index]['post_modified']) ? $formatted_post['posts'][$index]['post_modified'] : '';
			$formatted_post['posts'][$index]['post_date']     = mysql2date(get_option('date_format'), $post_date);
			$formatted_post['posts'][$index]['post_modified'] = mysql2date(get_option('date_format'), $post_post_modified);
			

			if(isset($formatted_post['posts'][$index]['post_type']) AND ('attachment' !== $formatted_post['posts'][$index]['post_type'])) {
				unset($formatted_post['posts'][$index]['post_mime_type']);
			}
			
			//image
			$imgs = easy_get_image('all',$id);
			$images = Array();
			foreach ( (array) $imgs as $key=>$img ) {
				$images[$key] = $img;
			}
			$formatted_post['posts'][$index]['image'] = $images;
			
			//post meta
			foreach(jsonpress_include_post_meta() as $post_meta) {
				$formatted_post['posts'][$index]['post_meta'][$post_meta] = get_post_meta($id,$post_meta,true);
			}
		}
	}
}
//unset excluded query 
foreach(jsonpress_exclude_query() as $jsonpress_exclude_query) {
	$jsonpress_exclude_query = trim($jsonpress_exclude_query);
	if(isset($result[$jsonpress_exclude_query])) {
		unset($result[$jsonpress_exclude_query]);
	}
}
//UNSET ORIGINAL POSTS OBJECT
unset($result['posts']);
///replace with modified one, eg. exclude some columns.
$result = $result+$formatted_post;

//for debugging purpose
if(isset($_GET['print_r'])) {
	echo '<pre>';
		print_r($result);
	echo '</pre>';
	exit;
}

jsonpress_json_output($result);

exit;
//END