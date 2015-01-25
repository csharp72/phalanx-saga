<?php
/*
WP Code Prettify Page
*/

$wpcp_status = "normal";

if(isset($_POST['wpcp_update_options'])) {
	if($_POST['wpcp_update_options'] == 'Y') {
		update_option("wp_code_prettify", maybe_serialize($_POST));
		$wpcp_status = 'update_success';
	}
}

if(!class_exists('WPCodePrettifyPage')) {
class WPCodePrettifyPage {
function WPCodePrettify_Options_Page() {
	?>

	<div class="wrap">
	<div id="wpcp-options">
	<div id="wpcp-title"><h2>WP Code Prettify</h2></div>
	<?php
	global $wpcp_status;
	if($wpcp_status == 'update_success')
		$message =__('Configuration updated', 'wp-code-prettify') . "<br />";
	else if($wpcp_status == 'update_failed')
		$message =__('Error while saving options', 'wp-code-prettify') . "<br />";
	else
		$message = '';

	if($message != "") {
	?>
		<div class="updated"><strong><p><?php
		echo $message;
		?></p></strong></div><?php
	} ?>
	<div id="wpcp-desc">
	<p><?php _e('This plugin enable syntax highlighting of code snippets in your post using Google Code Prettify.', 'wp-code-prettify'); ?></p>
	<h3><?php _e('Usage', 'wp-code-prettify'); ?></h3>
	<p><?php _e('Put code snippets in<tt>&lt;pre class="prettyprint"&gt;...&lt;/pre&gt;</tt> or <tt>&lt;code class="prettyprint"&gt;...&lt;/code&gt;</tt> and it will automatically be pretty printed.', 'wp-code-prettify'); ?></p>
	<p><?php _e('See <a href="http://google-code-prettify.googlecode.com/svn/trunk/README.html" target="_blank">Google Code Prettify Readme File</a> for more info.(<a href="http://google-code-prettify.googlecode.com/svn/trunk/README-zh-Hans.html" target="_blank">Chinese Version</a>)', 'wp-code-prettify'); ?></p>
	<p><?php _e('<b>Note</b>: The browser must support javascript.', 'wp-code-prettify'); ?></p>
	</div>

	<!--right-->
	<div class="postbox-container" style="float:right;width:300px;">
	<div class="metabox-holder">
	<div class="meta-box-sortables">

	<!--about-->
	<div id="wpcp-about" class="postbox">
	<h3 class="hndle"><?php _e('About this plugin', 'wp-code-prettify'); ?></h3>
	<div class="inside"><ul>
	<li><a href="http://wordpress.org/extend/plugins/wp-code-prettify/"><?php _e('Plugin URI', 'wp-code-prettify'); ?></a></li>
	<li><a href="http://www.cbug.org" target="_blank"><?php _e('Author URI', 'wp-code-prettify'); ?></a></li>
	</ul></div>
	</div>
	<!--about end-->

	<!-- donate -->
	<div id="wpps-donate" class="postbox">
	<h3 class="hndle"><?php _e('Donate', 'wp-post-signature'); ?></h3>
	<div class="inside">
	<center><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RQCN6KKJ2YEAN">
		<img src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_LG.gif" />
	</a></center>
	</div>
	</div>
	<!-- donate end -->

	<!--others-->
	<!--others end-->

	</div></div></div>
	<!--right end-->

	<!--left-->
	<div class="postbox-container" style="float:none;margin-right:320px;">
	<div class="metabox-holder">
	<div class="meta-box-sortabless">

	<!--setting-->
	<div id="wpcp-setting" class="postbox" style="padding: 10px;">
	<h3 class="hndle"><?php _e('Settings', 'wp-code-prettify'); ?></h3>
	<?php $wp_code_prettify = maybe_unserialize(get_option('wp_code_prettify')); ?>

	<form method="post" action="<?php echo get_bloginfo("wpurl"); ?>/wp-admin/options-general.php?page=wp-code-prettify">
	<input type="hidden" name="wpcp_update_options" value="Y">

	<script type="text/javascript">
		prettifyOnLoadHead = function() {
			allways_load_yes.checked = true;
			allways_load_no.disabled = true;
		}

		prettifyOnLoadFooter = function() {
			allways_load_no.disabled = false;
		}
	</script>

	<table class="form-table" style="clear:none;">
		<tr>
		<th scope="row"><?php _e('Where the js/css files should be loaded?', 'wp-code-prettify'); ?></th>
		<td>
		<input type="radio" name="load_pos" value="head" onclick="prettifyOnLoadHead();" <?php if($wp_code_prettify['load_pos'] == 'head') { echo 'checked'; } ?> /><?php _e('Head', 'wp-code-prettify'); ?><br />
		<input type="radio" name="load_pos" value="footer" onclick="prettifyOnLoadFooter();" <?php if($wp_code_prettify['load_pos'] == 'footer') { echo 'checked'; } ?> /><?php _e('Footer', 'wp-code-prettify'); ?>
		</td>
		<td><?php _e('Head: Load the js/css files in the &lt;head&gt; section. If this is checked, the next option will be disabled.<br />Footer: Load the js/css files in the &lt;div id="footer"&gt; section.', 'wp-code-prettify'); ?></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Allways load the js/css files?', 'wp-code-prettify'); ?></th>
		<td>
		<input type="radio" name="allways_load" id="allways_load_yes" value="yes" <?php if($wp_code_prettify['allways_load'] == 'yes') { echo 'checked'; } ?> /><?php _e('Yes', 'wp-code-prettify'); ?>
		<input type="radio" name="allways_load" id="allways_load_no" value="no" <?php if($wp_code_prettify['allways_load'] == 'no') { echo 'checked'; } ?> /><?php _e('No', 'wp-code-prettify'); ?>
		</td>
		<td><?php _e('Yes: Allway load the js/css files even if there is no code in the content.<br />No: Load the js/css files only if there is some code.', 'wp-code-prettify'); ?></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('Which style do you like?', 'wp-code-prettify'); ?></th>
		<td>
		<select name="style_file">
		<?php
		foreach(glob(dirname(__FILE__).'/css/*.css') as $filename) {
			$filename = basename($filename);
			if($filename == $wp_code_prettify['style_file']) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}

			echo '<option value="'. $filename . '"' . $selected . '>' . basename($filename, '.css') . '</option>';
		}
		?>
		</select>
		</td>
		<td></td>
		</tr>

		<tr>
		<th scope="row"><?php _e('You can add custom css here.', 'wp-code-prettify'); ?></th>
		<td colspan="2"><textarea cols="75" rows="5" name="style_custom"><?php echo stripslashes($wp_code_prettify['style_custom']); ?></textarea></td>
		</tr>
	</table>

	<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Save Changes', 'wp-code-prettify'); ?>" />
	</p>
	</form>
	</div>
	<!--setting end-->

	<!--others-->
	<!--others end-->

	</div></div></div>
	<!--left end-->

	</div>
	</div>
	<?php
}

function WPCodePrettify_Menu() {
	add_options_page(__('WP Code Prettify'), __('WP Code Prettify'), 'manage_options', 'wp-code-prettify', array($this,'WPCodePrettify_Options_Page'));
}

} // end of class WPCodePrettifyPage
} // end of if(!class_exists('WPCodePrettifyPage'))

