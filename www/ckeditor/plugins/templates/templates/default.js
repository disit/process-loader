/**
 * @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

// Register a templates definition set named "default".
CKEDITOR.addTemplates( 'default', {
	// The name of sub folder which hold the shortcut preview images of the
	// templates.
	imagesPath: CKEDITOR.getUrl( CKEDITOR.plugins.getPath( 'templates' ) + 'templates/images/' ),

	// The templates definitions.
	templates: [ 
	/*{
		title: 'Image and Title',
		image: 'template1.gif',
		description: 'One main image with a title and text that surround the image.',
		html: '<h3>' +
			// Use src=" " so image is not filtered out by the editor as incorrect (src is required).
			'<img src=" " alt="" style="margin-right: 10px" height="100" width="100" align="left" />' +
			'Type the title here' +
			'</h3>' +
			'<p>' +
			'Type the text here' +
			'</p>'
	},*/
//	

/*
'<style>'+
				'.message-properties>dt {'+
				'padding: 0px 3px 2px 3px;'+
				'font-family: monospace;'+
				'font-weight: normal;'+
				'margin: 5px 3px 1px;'+
				'color: #AD1625;'+
			   ' white-space: nowrap;}'+
						'.palette-header {'+
					   ' position: relative;'+
					   ' background: #f3f3f3;'+
						'cursor: pointer;'+
						'text-align: left;'+
					   ' padding: 9px;'+
					   ' font-weight: bold;'+
					   ' overflow: hidden;'+
					   ' white-space: nowrap;'+
					   ' text-overflow: ellipsis;'+
					'}'+
					'dl.message-properties>dt .property-type {'+
						'font-family: Helvetica Neue, Arial, Helvetica, sans-serif;'+
						'color: #666;'+
						'font-style: italic;'+
					   ' font-size: 11px;'+
						'float: right;'+
					'}'+
					'dl.message-properties {'+
					   ' border: 1px solid #ddd;'+
					  '  border-radius: 2px;'+
					  '  margin: 5px auto 10px;'+
					'}'+
					'</style>' +
*/
	{
		title: 'MicroService Template',
		image: 'template1.gif',
		description: 'MicroService Template Standard',
		html: 		'<p>Description of microservice</p>'+
					'<h3>Inputs</h3>Microservice input description:'+
					'<dl class="message-properties">'+
					'<dt>Parameter Name<span class="property-type">string</span></dt>'+
					'<dd>Insert text here</dd>'+
					'<dt>Parameter Name<span class="property-type">string</span></dt>'+
					'<dd>Insert text here</dd>'+
					'<dt>Parameter Name<span class="property-type">string</span></dt>'+
					'<dd>Insert text here</dd>'+
					'</dl>' +
					'<h3>Outputs</h3>'+
					'<dl class="message-properties">'+
					'<dd>Insert text here</dd>'+
					'</dl>'+
					'<h3>Details</h3>'+
					'<p>Insert text here</p>'
	}
/*
	{
		title: 'MicroService Template',
		image: 'template1.gif',
		description: 'MicroService Template Standard',
		html: '<h3 class="palette-header">Description</h3>'+
				'<p>Information about a Microservice.</p>'+
				'<h3 class="palette-header">Inputs</h3>'+
				'<p>MicroService Input Parameter Descritption</p>'+
				'<h3 class="palette-header">Outputs</h3>'+
				'<dl class="message-properties" style="border: none; margin: 0px;">'+
					'<p>MicroService Output Description</p>'+
				'<h3 class="palette-header">Configuration</h3>'+
				'<p>[Insert Screenshot]</p>'+
				'<h3 class="palette-header">Details</h3>'+
				'<p>Details about Microservice usage.</p>'
					}
*/
	]
} );
