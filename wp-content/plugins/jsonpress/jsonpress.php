<?php
/*
Plugin Name: JSONPress
Author: Takien
Version: 0.3
Description: JSON API for WordPress
Author URI: http://takien.com/
*/


require_once( dirname(__FILE__).'/options/easy-options.php' );
require_once( dirname(__FILE__).'/inc/easy-get-attachments.php' );

if(!class_exists('JSONPress')) {
	class JSONPress extends EasyOptions_1_5 {
	
		var $plugin_name    = 'JSONPress';
		var $plugin_slug    = 'jsonpress';
		var $plugin_version = '0.3';
		
		var $site_domain = '';
		var $api_domain  = ''; 
		
		function init() {
			add_action( 'init', Array(&$this, 'jsonpress_endpoints_add_endpoint'));
			
			add_filter( 'site_url', Array(&$this,'jsonpress_site_url' ),200 );
			add_filter( 'home_url', Array(&$this,'jsonpress_site_url' ),200 );
			add_filter( 'template_include', Array(&$this,'jsonpress_template') ) ;
			add_action( 'pre_get_posts', Array(&$this, 'jsonpress_custom_query'),  1 );
			
			// besides posts, sometime we want to display some other stuff like wp_list_category, menus etc.
			add_filter( 'rewrite_rules_array', Array(&$this, 'jsonpress_insert_rules'));
			add_filter( 'query_vars',          Array(&$this, 'jsonpress_query_vars'));
			
			//template redirect
			add_action( 'template_redirect', Array(&$this, 'jsonpress_template_redirect'), 1 );

			register_activation_hook( __FILE__, Array(&$this, 'jsonpress_endpoints_activate') );
			register_deactivation_hook( __FILE__, Array(&$this,'jsonpress_endpoints_deactivate') );
			
			$this->site_domain = $this->option('site_domain', 'jsonpress-settings', 'example.com');
			$this->api_domain  = $this->option('api_domain',  'jsonpress-settings', 'api.example.com');
			
			add_action('admin_notices', Array(&$this, 'jsonpress_admin_notice') ); 
		}
		
		function jsonpress_endpoints_activate() {
			
			$this->jsonpress_endpoints_add_endpoint();
			flush_rewrite_rules();
		}
		 
		function jsonpress_endpoints_deactivate() {
			flush_rewrite_rules();
		}
		//check if access is from API URL.
		
		function jsonpress_api_access() {
			global $wp_query;
			if( !empty( $this->api_domain ) AND ($this->api_domain !== 'api.example.com' ) ) {
				$path = str_replace('/index.php','',$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
					if( $path == rtrim( $this->api_domain, '/' ) ) {
						return true;
					}
			}
	
			if( isset($_GET['json']) )
				return true;
				
			if (isset( $wp_query->query_vars['json']))
				return true;
		}
		
		//modify site url
		function jsonpress_site_url( $url ){
			if( $this->jsonpress_api_access() ) {
				if( !empty( $this->api_domain ) AND ($this->api_domain !== 'api.example.com' ) ) {
					$url = str_ireplace( $this->site_domain, $this->api_domain, $url );
				}
			}
			return $url;
		}
		
		//TEMPLATE location
		function jsonpress_template($template) {
			if( $this->jsonpress_api_access() ){
				$template = dirname( __FILE__ ) . '/template/index.php';
			}
			return $template;
		}
		
		//rewrite endpoint
		function jsonpress_endpoints_add_endpoint() {
		// register a "json" endpoint to be applied to posts and pages
			add_rewrite_endpoint( 'json', EP_ALL );
		}
		//modify query
		function jsonpress_custom_query( $query ) {
			if(!$this->jsonpress_api_access()) return;

			if ( isset($_GET['posts_per_page']) ) {
				$query->set( 'posts_per_page', (int)$_GET['posts_per_page'] );
				return;
			}
			
			if ( isset($_GET['post_type']) ) {
				$query->set( 'post_type', $_GET['post_type'] );
				return;
			}
			
			if ( isset($_GET['popular']) ) {
				$query->set( 'orderby', 'comment_count' );
				$query->set( 'order', 'desc' );
				return;
			}
		}
		
		//rewrite rules for /get/function_name
		function jsonpress_insert_rules($rules){
			$newrules['get/([^/]*)/?$'] = 'index.php?get=$matches[1]';
			return $newrules+$rules;
		}

		function jsonpress_query_vars($vars) {
			array_push($vars, 'get');
			return $vars;
		}
		
		function jsonpress_template_redirect() {
			if (get_query_var('json') ) return false;
			
			if($this->jsonpress_api_access() AND ('' !== get_query_var('get'))) {
				include(dirname(__FILE__).'/template/get.php');
				exit;
			}
		}
		
		function jsonpress_admin_notice() {
			$error = false;
			if(( 'example.com' == $this->site_domain ) OR empty( $this->site_domain )) {
				$error = '<p><strong>JSONPress Error: </strong> Site domain not configured.</p>';
			}
			
			if($error) {
				echo '<div class="error">'.$error.'</div>';
			}		
		}
		//easy options :D
		function start() {
			$admin_menu = Array(
				'group'           => 'jsonpress-settings',
				'menu_name'       => 'JSONPress', 
				'page_title'      => 'JSONPress Settings', 
				'menu_slug'       => 'jsonpress-settings-page',  
				'fields'          => Array(),            
				'menu_location'   => 'add_menu_page',
				'capability'      => 'activate_plugins', 
				'icon_small'      => plugins_url( 'options/images/icon-setting-small.png' , __FILE__  ),  
				'icon_big'        => plugins_url( 'options/images/icon-setting-large.png' , __FILE__  ),       
				'menu_position'   => 71,     
				'add_tab'         => true,  
				);
			$this->add_admin_menu($admin_menu);
			
			$fields = Array(
				Array(
					'name'         => 'site_domain', 
					'label'        => 'Site Domain', 
					'type'         => 'text',  
					'value'        => '',    
					'description' => ' normal site domain, lowercase, no http://, no trailling slash, eg. example.com if your install under subfolder, include it, eg. example.com/wp'
					),
				Array(
					'name'         => 'api_domain', 
					'label'        => 'API Domain', 
					'type'         => 'text',  
					'value'        => '', 
					'description' => 'API Domain, eg. api.example.com, no http://, no trailling slash, if your install under subfolder, include it, eg. api.example.com/wp. <br> NOTE: You must manually create sub domain and point it to this WordPress installation.<br>
					If API domain not configured, you can still access JSON by add query json on each URL ex. example.com/?json=1' 
					),
				Array(
					'name'         => 'exclude_columns', 
					'label'        => 'Exclude Columns', 
					'type'         => 'textarea',
					'style'        => ' style="width:400px;height:200px" ',
					'value'        => "ping_status
post_password       
to_ping
pinged
post_content_filtered
filter
guid
post_date_gmt
post_modified_gmt
post_status
comment_status
post_name
post_parent
menu_order
post_type
post_modified
post_author", 
					'description' => 'One column each line, Database table columns (wp_posts) displayed above will not shown in the JSON output.<br>
					Or you can exlude columns via URL. <strong>api.example.com?p=1&exclude_columns=post_title,post_content,etc</strong>' 
					),
					Array(
					'name'         => 'exclude_query', 
					'label'        => 'Exclude Query', 
					'type'         => 'textarea',
					'style'        => ' style="width:400px;height:200px" ',
					'value'        => "query_vars
request
tax_query
meta_query
queried_object
post
current_post
current_comment", 
					'description' => 'One column each line, query keys displayed above will not shown in the JSON output.' 
					),
				
				);
			$this->add_fields('jsonpress-settings',$fields);
			
			$admin_menu2 = Array(
				'group'           => 'jsonpress-about', 
				'menu_name'       => 'About',
				'page_title'      => 'About', 
				'menu_slug'       => 'jsonpress-about',  
				'menu_location'   => 'add_sub_menu_page',
				'capability'      => 'activate_plugins',
				'page_callback'   => 'about_jsonpress',
				'parent_slug'     => 'jsonpress-settings-page',    
				'icon_small'      => plugins_url( 'options/images/about-small.png' , __FILE__  ),     
				'icon_big'        => plugins_url( 'options/images/about-large.png' , __FILE__  ),      
				'add_tab'         => true,
				);
			$this->add_admin_menu( $admin_menu2 );		
			
		}
		
		function about_jsonpress() { 
			?>	
				<p>
				Plugin name: <strong><?php echo $this->plugin_name;?></strong><br>
				Version: <strong><?php echo $this->plugin_version;?></strong><br>
				Author: <strong>takien</strong><br>
				Author URL: <a target="_blank" href="http://takien.com">http://takien.com</a>
				</p>
				<p>
					Need help?
				</p>
			<?php
		}		
		
	}
}

$jsonpress = new JSONPress;
$jsonpress->start();


function jsonpress_settings( $key='',$default='' ) {
	$jsonpress = new JSONPress;
	$options = $jsonpress->option( $key,'jsonpress-settings',$default );
	return $options;
}

function jsonpress_exclude_columns() {
	$exclude_columns = jsonpress_settings( 'exclude_columns' );
	$exclude_columns = explode( "\n", $exclude_columns );
	
	if(isset($_GET['exclude_columns'])) {
		$user_exclude_columns = explode(',',$_GET['exclude_columns']);
		$exclude_columns      = array_merge($exclude_columns,$user_exclude_columns);
	}
	return $exclude_columns;
}

function jsonpress_exclude_query() {
	$exclude_query = explode("\n",jsonpress_settings( 'exclude_query' ));
	return $exclude_query;
}

function jsonpress_include_post_meta() {
	$post_meta = Array(
		// put default included post meta here, otherwise you must add it in query var, ?include_post_meta=metakey1,metakey2 etc
	);
	if(isset($_GET['include_post_meta'])) {
		$user_post_meta = explode(',',$_GET['include_post_meta']);
		$post_meta = array_merge($post_meta,$user_post_meta);
	}
	return $post_meta;
}

function jsonpress_json_output($array) {
	header('Access-Control-Allow-Origin: *');
	header('Content-type: application/json; charset=utf-8');
	if(isset($_GET['callback'])) {
		echo $_GET['callback'].'('.json_encode($array).');';
	}
	else if(isset($_GET['debug'])) {
		echo json_prettyPrint(json_encode($array));
	}
	else {
		echo json_encode($array);
	}
	exit;
}

function jsonpress_excerpt( $text = '') {

	$text = strip_shortcodes( $text );

	$text = apply_filters('the_content', $text);
	$text = str_replace(']]>', ']]&gt;', $text);
	$excerpt_length = apply_filters('excerpt_length', 55);
	$excerpt_more   = apply_filters('excerpt_more', ' ' . '[&hellip;]');
	$text = wp_trim_words( $text, $excerpt_length, $excerpt_more );
	
	return $text;
}


function json_prettyPrint( $json ) {
    $result = '';
    $level = 0;
    $prev_char = '';
    $in_quotes = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if( $char === '"' && $prev_char != '\\' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
        $prev_char = $char;
    }

    return $result;
}