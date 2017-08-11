(function () {
	/* Register the buttons */
	tinymce.create('tma.plugins.ShortCodes', {
		init: function (ed, url) {
			/**
			 * Inserts shortcode content
			 */
			ed.addButton('button_tma_content', {
				title: 'Insert tma_content shortcode',
				image: TMA_CONFIG.plugin_url + '/images/tma_content.png',
				onclick: function () {
					// Open window
					ed.windowManager.open({
						title: 'TMA Content',
						body: [
							{type: 'textbox', name: 'segments', label: 'Segments', tooltip: 'Comma separated list of segments.'},
							{type: 'textbox', name: 'group', label: 'Group', tooltip: "The group the content belongs to."},
							{type: 'listbox',
								label: 'Default :',
								name: 'default',
								tooltip: 'Is this the default content for the group?',
								values: [
									{text: 'True', value: 'true'},
									{text: 'False', value: 'false'}
								]
							},
							{type: 'listbox',
								label: 'Mode :',
								name: 'mode',
								tooltip: 'The matching mode for the segments!',
								values: [
									{text: 'Single', value: 'single'},
									{text: 'All', value: 'all'}
								]
							}
						],
						onsubmit: function (e) {
							var return_text = "[tma_content segments='" + e.data.segments + "' ";
							if (e.data.group !== "") {
								return_text += "group='" + e.data.group + "' ";
							}

							return_text += "default='" + e.data.default + "' ";
							return_text += "mode='" + e.data.mode + "' ";
							return_text += "]";
							return_text += ed.selection.getContent();
							return_text += "[/tma_content]";
//							ed.selection.setContent(return_text);
							ed.execCommand('mceInsertContent', 0, return_text);
						}
					});
				}
			});
			/*
			window.wp.mce.views.register('tma_content', {
				initialize: function () {
					console.log("tma_content view");
					var self = this;

					wp.ajax.post('query-attachments', {
						query: {
							post_mime_type: 'image'
						}
					}).done(function (response) {
						self.render(_.map(response, function (data) {
							return '<img src=' + data.sizes.thumbnail.url + ' alt=' + data.alt + '>';
						}).join(''));
					});
				},
			});*/
		},
		createControl: function (n, cm) {
			return null;
		},
	});
	/* Start the buttons */
	tinymce.PluginManager.add('tma_shortcodes_plugin', tma.plugins.ShortCodes);
})();