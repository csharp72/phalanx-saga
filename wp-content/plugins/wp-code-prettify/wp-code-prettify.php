<?php
/*
Plugin Name: WP Code Prettify
Plugin URI: http://wordpress.org/extend/plugins/wp-code-prettify/
Description: This plugin enable syntax highlighting of code snippets in your post using Google Code Prettify.
Version: 0.2.4
Author: Soli
Author URI: http://www.cbug.org
Text Domain: wp-code-prettify
Domain Path: /lang


Copyright (c) 2011-2014
Released under the GPL license
http://www.gnu.org/licenses/gpl.txt
*/

if(!class_exists('WPCodePrettify')) {
class WPCodePrettify {

// Is there some code?
var $there_is_some_code;

function is_str_and_not_empty($var) {
	if (!is_string($var))
		return false;

	if (empty($var))
		return false;

	if ($var=='')
		return false;

	return true;
}

function WPCP_ParseCode($matches) {
	$this->there_is_some_code = true;

	if($this->is_str_and_not_empty($matches[1])) {
		$pre = $matches[2];
		$plaincode = $matches[3];
		$post = $matches[4];
	} else if($this->is_str_and_not_empty($matches[5])) {
		$pre = $matches[6];
		$plaincode = $matches[7];
		$post = $matches[8];
	} else {
		return;
	}
//	echo "PRE:" . $pre . ":PRE";
//	echo "POST:" . $post . ":POST";
//	echo "CODE:" . $plaincode . ":CODE";
//	$html_entities_match = array( "|\<br \/\>|", "#<#", "#>#", "|&#39;|", '#&quot;#', '#&nbsp;#' );
//	$html_entities_replace = array( "\n", '&lt;', '&gt;', "'", '"', ' ' );

	$html_entities_match = array( "|\<br \/\>|", "#\<#", "#\>#", "|/|", '#\[#', '#\]#', '#"#', "#'#" );
	$html_entities_replace = array( "\n", '&lt;', '&gt;', '&#47;', '&#91;', '&#93;', "&#34;", '&#39;');

	$plaincode = preg_replace( $html_entities_match, $html_entities_replace, $plaincode );

//	$plaincode = str_replace('&lt;', '<', $plaincode);
//	$plaincode = str_replace('&gt;', '>', $plaincode);

 	return $pre . $plaincode . $post;
}

function WPCP_Content($content) {

//	return preg_replace_callback("/<pre\s+.*class\s*=\"prettyprint\">(.*)<\/pre>/siU",
	return preg_replace_callback('@((<pre\s+.*?class\s*?=\s*?"\s*prettyprint\b.*?\".*?>)(.*?)(</pre>))|((<code\s+.*?class\s*?=\s*?"\s*prettyprint\b.*?\".*?>)(.*?)(</code>))@si',
								 array($this,'WPCP_ParseCode'),
								 $content);
}

function WPCP_Head($content) {
	// init flag
	$this->there_is_some_code = false;

	$wp_code_prettify = maybe_unserialize(get_option('wp_code_prettify'));

	if($wp_code_prettify['load_pos'] == 'head') {

		$plugin_path = site_url('/wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ));
		?>

		<!--wp code prettify-->
		<link id="prettify_css" href="<?php echo $plugin_path . '/css/' . $wp_code_prettify['style_file']; ?>" type="text/css" rel="stylesheet" />
		<?php if($this->is_str_and_not_empty($wp_code_prettify['style_custom'])) { ?>
		<style type="text/css" id="prettify_custom"><?php echo stripslashes($wp_code_prettify['style_custom']); ?></style>
		<?php } ?>

		<script type="text/javascript" src="<?php echo $plugin_path . '/js/prettify.js'; ?>"></script>
		<script type="text/javascript">
			function wpCodePrettifyOnLoad(func){
				var wpCodePrettifyOldOnLoad = window.onload;
				if (typeof window.onload != 'function') {
					window.onload = func
				} else {
					window.onload = function () {
						wpCodePrettifyOldOnLoad();
						func()
					}
				}
			}

			wpCodePrettifyOnLoad(function(){prettyPrint();});
		</script>
		<!--//wp code prettify-->

		<?php
	}
}

function WPCP_Footer($content) {

	$wp_code_prettify = maybe_unserialize(get_option('wp_code_prettify'));

	if($wp_code_prettify['load_pos'] == 'head') {
		return;
	}

	if( $this->there_is_some_code == false && $wp_code_prettify['allways_load'] == 'no') {
		return;
	}

	$plugin_path = site_url('/wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ));
	?>

	<!--wp code prettify-->
	<script type="text/javascript">
	function $(id) {return !id ? null : document.getElementById(id);}

	loadPrettifyCss = function () {
		if(!$('prettify_css')) {
			css = document.createElement('link');
			css.id = 'prettify_css';
			css.type = 'text/css';
			css.rel = 'stylesheet';
			css.href = '<?php echo  $plugin_path . '/css/' . $wp_code_prettify['style_file']; ?>';
			var headNode = document.getElementsByTagName("head")[0];
			headNode.appendChild(css);
		} else {
			$('prettify_css').href = '<?php echo  $plugin_path . '/css/' . $wp_code_prettify['style_file']; ?>';
		}

		if(!$('prettify_custom')) {
			css = document.createElement('style');
			css.id = 'prettify_custom';
			css.type = 'text/css';
			css.rel = 'stylesheet';
			css.innerHTML = '<?php echo stripslashes($wp_code_prettify['style_custom']); ?>';
			var headNode = document.getElementsByTagName("head")[0];
			headNode.appendChild(css);
		} else {
			$('prettify_css').innerHTML = '<?php echo stripslashes($wp_code_prettify['style_custom']); ?>';
		}
	}
	</script>
	<script type="text/javascript">
		loadPrettifyCss();
	</script>

	<script type="text/javascript" src="<?php echo $plugin_path . '/js/prettify.js'; ?>"></script>
	<script type="text/javascript">
		function wpCodePrettifyOnLoad(func){
			var wpCodePrettifyOldOnLoad = window.onload;
			if (typeof window.onload != 'function') {
				window.onload = func
			} else {
				window.onload = function () {
					wpCodePrettifyOldOnLoad();
					func()
				}
			}
		}

		wpCodePrettifyOnLoad(function(){prettyPrint();});
	</script>
	<!--//wp code prettify-->
	<?php
}

/**
 * Registers additional links for the plugin on the WP plugin configuration page
 *
 * Registers the links if the $file param equals to the plugin
 * @param $links Array An array with the existing links
 * @param $file string The file to compare to
 */
function RegisterPluginLinks($links, $file) {
	load_plugin_textdomain( 'wp-code-prettify', false, dirname( plugin_basename( __FILE__ ) ) . "/lang" );
	$base = plugin_basename(__FILE__);
	if ($file ==$base) {
		$links[] = '<a href="options-general.php?page=wp-code-prettify">' . __('Settings','wp-code-prettify') . '</a>';
		$links[] = '<a href="http://www.cbug.org/tag/wp-code-prettify/">' . __('FAQ','wp-code-prettify') . '</a>';
	}
	return $links;
}

/**
 * Handled the plugin activation on installation
 */
function ActivatePlugin() {
	$optfile = trailingslashit(dirname(__FILE__)) . "options.txt";
	$options = file_get_contents($optfile);
	add_option("wp_code_prettify", $options, null, 'no');
}

/**
 * Handled the plugin deactivation
 */
function DeactivatePlugin() {
	$optfile = trailingslashit(dirname(__FILE__)) . "options.txt";
	file_put_contents($optfile, get_option("wp_code_prettify"));
	delete_option("wp_code_prettify");
}

} // end of class WPCodePrettify
} // end of if(!class_exists('WPCodePrettify'))

load_plugin_textdomain( 'wp-code-prettify', false, dirname( plugin_basename( __FILE__ ) ) . "/lang" );

if(class_exists('WPCodePrettify')) {

	$wpcodeprettify = new WPCodePrettify();

	if(isset($wpcodeprettify)) {
		register_activation_hook(__FILE__, array(&$wpcodeprettify, 'ActivatePlugin'));
		register_deactivation_hook(__FILE__, array(&$wpcodeprettify, 'DeactivatePlugin'));

		//Additional links on the plugin page
		add_filter('plugin_row_meta', array(&$wpcodeprettify, 'RegisterPluginLinks'),10,2);

		//Add the actions
		add_action('wp_head', array(&$wpcodeprettify, 'WPCP_Head'));
		add_action('get_footer', array(&$wpcodeprettify, 'WPCP_Footer'));

		//Add the filters
		add_filter('the_content', array(&$wpcodeprettify, 'WPCP_Content'));
		add_filter('comment_text', array(&$wpcodeprettify, 'WPCP_Content'));
	}
}

/* Options Page */
require_once(trailingslashit(dirname(__FILE__)) . "wp-code-prettify-page.php");

if(class_exists('WPCodePrettifyPage')) {
	$wpcodeprettify_page = new WPCodePrettifyPage();

	if(isset($wpcodeprettify_page)) {
		add_action('admin_menu', array(&$wpcodeprettify_page, 'WPCodePrettify_Menu'), 1);
	}
}

