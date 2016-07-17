/**
 * $Id: editor_template_src.js 114 2006-10-17 09:29:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2006, Moxiecode Systems AB, All rights reserved.
 */

var TinyMCE_SimpleTheme = {
	// List of button ids in tile map
	_buttonMap : 'bold,bullist,cleanup,italic,numlist,redo,strikethrough,underline,undo',

	getEditorTemplate : function() {
		var html = '';

		html += '<table class="mceEditor" border="0" cellpadding="0" cellspacing="0" width="{$width}" height="{$height}">';
		html += '<tr><td align="center">';
		html += '<span id="{$editor_id}">IFRAME</span>';
		html += '</td></tr>';
		html += '<tr><td class="mceToolbar" align="center" height="1">';
		html += '</td></tr></table>';

		return {
			delta_width : 0,
			delta_height : 20,
			html : html
		};
	},

	handleNodeChange : function(editor_id, node) {
		// Reset old states
		tinyMCE.switchClass(editor_id + '_bold', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_italic', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_underline', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_strikethrough', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_bullist', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_numlist', 'mceButtonNormal');

		// Handle elements
		do {
			switch (node.nodeName.toLowerCase()) {
				case "b":
				case "strong":
					tinyMCE.switchClass(editor_id + '_bold', 'mceButtonSelected');
				break;

				case "i":
				case "em":
					tinyMCE.switchClass(editor_id + '_italic', 'mceButtonSelected');
				break;

				case "u":
					tinyMCE.switchClass(editor_id + '_underline', 'mceButtonSelected');
				break;

				case "strike":
					tinyMCE.switchClass(editor_id + '_strikethrough', 'mceButtonSelected');
				break;
				
				case "ul":
					tinyMCE.switchClass(editor_id + '_bullist', 'mceButtonSelected');
				break;

				case "ol":
					tinyMCE.switchClass(editor_id + '_numlist', 'mceButtonSelected');
				break;
			}
		} while ((node = node.parentNode) != null);
	}
};

tinyMCE.addTheme("simple", TinyMCE_SimpleTheme);
tinyMCE.addButtonMap(TinyMCE_SimpleTheme._buttonMap);
