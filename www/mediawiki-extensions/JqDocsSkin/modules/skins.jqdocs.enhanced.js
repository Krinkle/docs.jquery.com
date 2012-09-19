/**
 * Scripts to run on jqdocs wiki pages.
 */
jQuery(document).ready(function ($) {
	$('#examples').each(function(){
		$(this).siblings('div:first').find('div.desc').after(
			$(this).find('div.example:has(#demo):first').remove().clone()
		);

		if (!$(this).find('div.example').length) {
			$(this).remove();
		}
	});

	$('div.entry')
		.children('p').remove().end()
		.find('ul:first li').each(function(){
			if ( !$(this).parent().siblings( $(this).find('a').attr('href') ).length )
				$(this).remove();
		}).end()
		.not('.ui')
		.tabs();

	$('div.args > br').remove();

	$('div.example')
		.children('p').remove().end()
		.tabs();

	var keywords = 'String,Number,Object,Options,Array,Function,Callback,Selector,Event,Element,Integer,Float,Boolean'.split(',');

	$('td.default span, td.type').each(function () {
		var html = this.innerHTML;
		jQuery.each(keywords, function (i, key) {
			html = html.replace(new RegExp(key, 'ig'), '<a href="/Types#' + key + '">' + key + '</a>');
		});
		this.innerHTML = html;
	});

	$('#source').each(function () {
		var source = $(this).find('pre').html()
			.replace(/<\/?a.*?>/ig, '')
			.replace(/<\/?strong.*?>/ig, '')
			.replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&');


		var iframe = document.createElement('iframe');
		iframe.src = '/index-blank.html';
		iframe.width = '100%';
		iframe.height = $(this).prev().attr('rel') || '125';
		iframe.style.border = 'none';
		$(this).prev().append(iframe);


		$(window).load(function () {
			var doc = iframe.contentDocument || iframe.contentWindow.document;
			  source = source
			          .replace(/<script>([^<])/g,'<script>window.onload = (function () {\ntry {$1')
			          .replace(/([^>])<\/sc/g,  '$1\n}catch (e) {} });</sc');

			    source = source
			            .replace('</head>', '<style>html, body { border: 0; margin: 0; padding: 0; }</style></head>');

			doc.open();
			doc.write( source );
			doc.close();
		});

	});

});
