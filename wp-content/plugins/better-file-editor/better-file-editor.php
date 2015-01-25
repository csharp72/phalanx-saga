<?php
/*
Plugin Name: Better File Editor
Plugin URI: http://wordpress.org/extend/plugins/better-file-editor/
Description: Adds line numbers, syntax highlighting, code folding, and lots more to the theme and plugin editors in the admin panel.
Version: 2.2.0
Author: Bryan Petty <bryan@ibaku.net>
Author URI: http://profiles.wordpress.org/bpetty/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

class BetterFileEditorPlugin {

	function BetterFileEditorPlugin() {
		add_action('admin_footer-theme-editor.php', array($this, 'admin_footer'));
		add_action('admin_footer-plugin-editor.php', array($this, 'admin_footer'));
	}

	function admin_footer() {
		?>
		<script src="<?php echo plugins_url( 'js/require.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'js/ace/ace.js' , __FILE__ ); ?>"></script>
		<script src="<?php echo plugins_url( 'js/ace/ext-modelist.js' , __FILE__ ); ?>"></script>
		<script type="text/javascript" charset="utf-8">
			jQuery(document).ready(function() {
				/**
				 * Detecting the HTML5 Canvas API (usually) gives us IE9+ and
				 * of course all modern browsers. This should be adequate for
				 * minimum requirements instead of browser sniffing.
				 */
				if(!!document.createElement('canvas').getContext)
				{
					var wpacejs = document.createElement('script');
					wpacejs.type = 'text/javascript'; wpacejs.charset = 'utf-8';
					wpacejs.src = '<?php echo plugins_url( "js/wp-ace.js" , __FILE__ ); ?>';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(wpacejs, s);
				}
			});
		</script>
		<style type="text/css">
			#template div {
				/* Need to reset margin here from core styles since it destroys
				   every single div contained in the editor... */
				margin-right: 0px;
			}
			#template #editor, #template > div {
				/* ... then redefine it in a much more scoped manner. */
				margin-right: 210px;
			}
			#template div #newcontent {
				width: 100%;
			}
			#wp-ace-editor {
				position: relative;
				height: 560px;
				font-size: 12px;
				border: 1px solid #BBB;
				border-radius: 3px;
			}
			.ace_editor {
				font-family: Consolas, Menlo, "Liberation Mono", Courier, monospace !important;
			}
			#wp-ace-editor-controls table td {
				vertical-align: center;
				padding: 5px;
			}
		</style>
		<?php
	}

}

$bfe_plugin = new BetterFileEditorPlugin();
