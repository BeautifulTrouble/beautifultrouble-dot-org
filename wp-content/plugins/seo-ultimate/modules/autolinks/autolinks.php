<?php
/**
 * Deeplink Juggernaut Module
 * 
 * @since 1.8
 */

if (class_exists('SU_Module')) {

class SU_Autolinks extends SU_Module {
	static function get_module_title() { return __('Deeplink Juggernaut', 'seo-ultimate'); }
	static function get_menu_title() { return __('Deeplink Juggernaut', 'seo-ultimate'); }
	
	function add_help_tabs($screen) {
		
		$screen->add_help_tab(array(
			  'id' => 'su-autolinks-overview'
			, 'title' => __('Overview', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><strong>What it does:</strong> Deeplink Juggernaut lets you automatically generate hyperlinks in your site&#8217;s content and footer.</li>
	<li><strong>Why it helps:</strong> Search engines use the anchor text of hyperlinks to determine the topicality of the webpage to which the link points. Deeplink Juggernaut lets you automatically generate hyperlinks to various pages on your site, which can help increase the linked page&#8217;s ranking for the term used in the anchor text.</li>
	<li><strong>How to use it:</strong> The Content Links section lets you automatically link words or phrases in your site&#8217;s content to a target page of your choosing. The Footer Links section lets you add links in the footer of your entire site or just a particular set of webpages.</li>
</ul>
", 'seo-ultimate')));

		$screen->add_help_tab(array(
			  'id' => 'su-autolinks-content-links'
			, 'title' => __('Content Links Tab', 'seo-ultimate')
			, 'content' => __("
<p>To add an autolink, fill in the fields and then click &#8220;Save Changes.&#8221; Once you do so, you can edit your new autolink or add another one.</p>

<ul>
	<li><strong>Anchor Text</strong> &mdash; Deeplink Juggernaut will scan your site&#8217;s content for the word or phrase you put in this box, and then hyperlink instances of that word or phrase to the webpage or item you specify in the Destination box. The Anchor Text should be a keyword that you want the Destination page to rank for.</li>
	<li><p><strong>Destination</strong> &mdash; This is the box where you specify the webpage where you want the auto-generated hyperlinks to point.</p>
		<ul>
			<li>To link to a post, page, attachment, category, tag, term, or author on your site, just type in its name and then select it from the dropdown.</li>
			<li>To link to your blog homepage, just type in &#8220;home&#8221; and select &#8220;Blog Homepage&#8221; from the dropdown.</li>
			<li>To link to one of the aliased URLs you created with the Link Mask Generator, just type in part of the original URL or alias URL and then select the link mask from the dropdown.</li>
			<li>To link to some other webpage, just type or paste in its URL in the box.</li>
		</ul>
	</li>
	<li><strong>Title Attribute</strong> &mdash; The link&#8217;s title attribute is the text that will appear when the visitor&#8217;s mouse pointer hovers over the link. Totally optional.</li>
	<li><p><strong>Dampener:</strong> If the anchor text you specify occurs many times throughout your site&#8217;s content, you may wish to reduce the overall frequency with which the anchor text is hyperlinked. You can reduce the autolinking frequency by a percentage with the Dampener field. For example:</p>
		<ul>
			<li>0% dampening will have no effect.</li>
			<li>50% dampening means the anchor text will be autolinked approximately half as often as it otherwise would be.</li>
			<li>90% dampening means the anchor text will be autolinked only 10% as often as it otherwise would be.</li>
			<li>100% dampening means the anchor text won&#8217;t be linked at all.</li>
		</ul>
		<p>The &#8220;Dampener&#8221; column will only appear if you&#8217;ve enabled it under the &#8220;Content Link Settings&#8221; tab.</p>
	</li>
	<li><strong>Nofollow</strong> &mdash; Checking this will add the <code>rel=&quot;nofollow&quot;</code> attribute to all autolinks generated for that anchor text. You should enable this only if you&#8217;re creating an automatic affiliate link.</li>
	<li><strong>New window</strong> &mdash; Checking this will make the link destination open up in a new window when the autolink is clicked.</li>
	<li><strong>Delete</strong> &mdash; To delete an autolink, tick its &#8220;Delete&#8221; checkbox and then click &#8220;Save Changes.&#8221;</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-autolinks-content-link-settings'
			, 'title' => __('Content Link Settings Tab', 'seo-ultimate')
			, 'content' => __("
<p>The following options are available on the Content Link Settings tab:</p>

<ul>
	<li><strong>Add Autolinks to...</strong> &mdash; You can stop Deeplink Juggernaut from adding hyperlinks to the content of items of a given post type by unchecking the post type&#8217;s checkbox.</li>
	<li>
		<p><strong>Self-Linking</strong></p>
		<ul>
			<li><strong>Allow posts to link to themselves</strong> &mdash; This permits Deeplink Juggernaut to add a link to the content of a given post/page even if the link is pointing to the URL of that post/page.</li>
			<li><strong>Allow posts to link to the URL by which the visitor is accessing the post</strong> &mdash; There are lots of URLs by which you can access any given post/page on your site. You can access posts/pages via the homepage URL, your site&#8217;s many archive URLs, or the URLs of the posts/pages themselves. If you have an autolink that points to the homepage, and if Deeplink Juggernaut were to add that link to the content of posts when those posts are accessed from the homepage, then the homepage would be linking to itself. By default, Deeplink Juggernaut won&#8217;t let this happen. But if you&#8217;re okay with that sort of behavior, you can enable it by checking this box.</li>
		</ul>
	</li>
	<li>
		<p><strong>Quantity Restrictions</strong></p>
		<ul>
			<li><strong>Don&#8217;t add any more than ___ autolinks per post/page/etc.</strong> &mdash; Use this option to cap the total number of autolinks (for all anchor texts combined) that can be added to the content of any one item.</li>
			<li><strong>Don&#8217;t link the same anchor text any more than ___ times per post/page/etc.</strong> &mdash; Use this option to cap the number of times that each anchor text can be autolinked in the content of any one item.</li>
			<li><strong>Don&#8217;t link to the same destination any more than ___ times per post/page/etc.</strong> &mdash; Use this option to cap the number of autolinks that any one URL can get within the content of any one item. (This is different from the previous option because you can have multiple anchor texts pointing to the same place.)</li>
		</ul>
	</li>
	<li>
		<p><strong>Additional Dampening Effect</strong></p>
		<ul>
			<li><strong>Globally decrease autolinking frequency by ___%</strong> &mdash; If you have massive amounts of content on your site (e.g. thousands of posts), the &#8220;Quantity Restrictions&#8221; settings may not be sufficient to reign in the number of autolinks being generated. If that is the case, you can use this option to reduce overall autolinking frequency by a given percentage. For example, if you were to set global dampening to 10%, then autolinks would be added only 90% as often as before.</li>
			<li><strong>Add a &#8220;Dampener&#8221; column to the Content Links editor</strong> &mdash; Check this box and click &#8220;Save Changes&#8221; to add a new column to the &#8220;Content Links&#8221; editor table that will let you apply the dampening effect to individual autolinks. If you&#8217;ve also enabled the global dampening option, this will let you override the global value for individual links. (For example, you can disable dampening for just one of your links by setting the Dampener field to 0%.)</li>
		</ul>
	</li>
	<li><strong>Tag Restrictions</strong> &mdash; By default, Deeplink Juggernaut will not autolink a particular anchor text if that anchor text is found in a header or in a code block. You can further customize these exceptions by adding HTML tags to the list.</li>
	<li><strong>Siloing</strong> &mdash; If you enable the siloing feature for a given post type (such as posts or pages), then items of that post type will only be able to autolink to a webpage on your site if it falls within a category, tag, or term shared by that item. For example, you can set it up so that posts in Category A can&#8217;t autolink to anything on your site with the exception of other posts in Category A and the Category A archive. Autolinks to external sites will not be affected, since the siloing setting will not affect autolinks that have a URL in the Destination box.</li>
	<li><strong>CSS Class for Autolinks</strong> &mdash; If you want to apply CSS styling to Content Links generated by Deeplink Juggernaut, type in a class name here (e.g. &#8220;autolink&#8221;).</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-autolinks-footer-links'
			, 'title' => __('Footer Links Tab', 'seo-ultimate')
			, 'content' => __("
<p>To add a footer link, fill in the fields and then click &#8220;Save Changes.&#8221; Once you do so, you can edit your new footer link or add another one.</p>

<ul>
	<li><p><strong>Link Location</strong> &mdash; If you want to add a footer link across your entire site, leave this box blank. Otherwise, type in the location on your site where you want the footer link to appear.</p>
		<p>If you only want the footer link to appear on&hellip;</p>
		<ul>
			<li>&hellip;A particular post, page, attachment, or category/tag/term/author archive, just type in the item&#8217;s name and then select it from the dropdown.</li>
			<li>&hellip;Your blog homepage, just type in &#8220;home&#8221; and select &#8220;Blog Homepage&#8221; from the dropdown.</li>
			<li>&hellip;A particular URL on your site, just type or paste it into the box.</li>
		</ul>
	</li>
	<li><p><strong>Match child content</strong> &mdash; What this does depends on what type of Link Location you specified.</p>
		<ul>
			<li>If the Link Location is a category/tag/term archive, then the footer link will also be added to posts within that term.</li>
			<li>If the Link Location is an author archive, then the footer link will also be added to posts written by that author.</li>
			<li>If the Link Location is a URL, then the footer link will also be added to URLs that begin with whatever URL you entered.</li>
		</ul>
	</li>
	<li><strong>Negative match</strong> &mdash; This will cause the footer link to be inserted on webpages <em>other than</em> the Link Location and (if the appropriate box is checked) its child content.</li>
	<li><strong>Anchor Text</strong> &mdash; Deeplink Juggernaut insert this text into your site&#8217;s footer and will link that text to the webpage or item you specify in the Destination box. The Anchor Text should be a keyword that you want the Destination page to rank for.</li>
	<li><p><strong>Destination</strong> &mdash; This is the box where you specify the webpage where you want the auto-generated hyperlinks to point.</p>
		<ul>
			<li>To link to a post, page, attachment, category, tag, term, or author on your site, just type in its name and then select it from the dropdown.</li>
			<li>To link to your blog homepage, just type in &#8220;home&#8221; and select &#8220;Blog Homepage&#8221; from the dropdown.</li>
			<li>To link to one of the aliased URLs you created with the Link Mask Generator, just type in part of the original URL or alias URL and then select the link mask from the dropdown.</li>
			<li>To link to some other webpage, just type or paste in its URL in the box.</li>
		</ul>
	</li>
	<li><strong>Title Attribute</strong> &mdash; The link&#8217;s title attribute is the text that will appear when the visitor&#8217;s mouse pointer hovers over the link. Totally optional.</li>
	<li><strong>Nofollow</strong> &mdash; Checking this will add the <code>rel=&quot;nofollow&quot;</code> attribute to all instances of this footer link. You should enable this only if you&#8217;re creating an automatic affiliate link.</li>
	<li><strong>New window</strong> &mdash; Checking this will make the link destination open up in a new window when the footer link is clicked.</li>
	<li><strong>Delete</strong> &mdash; To delete an autolink, tick its &#8220;Delete&#8221; checkbox and then click &#8220;Save Changes.&#8221;</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-autolinks-footer-link-settings'
			, 'title' => __('Footer Link Settings Tab', 'seo-ultimate')
			, 'content' => __("
<p>The following options are available on the Footer Link Settings tab:</p>

<ul>
	<li><strong>Link Section Format</strong> &mdash; Lets you customize the text/HTML that will surround the list of links outputted in your site&#8217;s footer (represented by the <code>{links}</code> variable).</li>
	<li><strong>Link Format</strong> &mdash; Lets you specify text or HTML that will surround each individual link (represented by the <code>{link}</code> variable).</li>
	<li><strong>Link Separator</strong> &mdash; Lets you specify text or HTML that will separate each individual link.</li>
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-autolinks-faq'
			, 'title' => __('FAQ', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><strong>What happens if I autolink to a post and then delete the post later?</strong><br />Deeplink Juggernaut will disable all autolinks that point to the deleted post. Deeplink Juggernaut will keep the autolink in the list though, so that you can point it somewhere else.</li>
	<li><strong>What happens if I autolink to a draft post?</strong><br />Don&#8217;t worry: Deeplink Juggernaut won&#8217;t actually autolink to it until the post is published.</li>
	<li><strong>Does Deeplink Juggernaut edit my posts&#8217; content as it is stored in the database?</strong><br />No. Autolinks are added dynamically. This means all the autolinks will go away if you disable Deeplink Juggernaut or deactivate SEO Ultimate.</li>
	<li><strong>How does the Dampener work?</strong><br />When the Dampener is in effect, Deeplink Juggernaut creates a hash for each autolink and creates a hash for each post/page/etc. on your site. In order for the autolink to be added to the content of a post/page, the two hashes have to be compatible with each other. If the Dampener is set to 70%, then the hashes will match and the autolink will be applied approximately 30% of the time. This hash system results in a pseudo-random dampening effect that will always have a consistent outcome for any given anchor/post combination.</li>
	<li><strong>Can I still use the Footer Links feature if my theme has a widgetized footer?</strong><br />Yes. Make sure the &#8220;SEO Ultimate Widgets&#8221; module is enabled in the SEO Ultimate <a href='admin.php?page=seo' target='_blank'>Module Manager</a>, then go to your <a href='widgets.php' target='_blank'>Widgets</a> page and add the &#8220;Footer Links&#8221; widget.
</ul>
", 'seo-ultimate')));
		
		$screen->add_help_tab(array(
			  'id' => 'su-autolinks-troubleshooting'
			, 'title' => __('Troubleshooting', 'seo-ultimate')
			, 'content' => __("
<ul>
	<li><strong>I configured a Content Link, but the anchor text isn&#8217;t being linked on my site.</strong><br />You likely enabled a setting on the &#8220;Content Link Settings&#8221; tab that is preventing the autolink from being applied.</li>
	<li><strong>I have Content Links configured for &#8220;widgets&#8221; and &#8220;blue widgets,&#8221; but when the phrase &#8220;blue widgets&#8221; appears on my site, only the word &#8220;widgets&#8221; is being linked. Why is that?</strong><br />Deeplink Juggernaut always links longer anchor texts first, so if this is happening, then the &#8220;blue widgets&#8221; autolink must have been disabled in that particular instance due to a Quantity Restriction or the Dampener effect being applied.</li>
	<li><strong>Why aren&#8217;t my footer links appearing?</strong><br />Check to make sure your theme is <a href='http://johnlamansky.com/wordpress/theme-plugin-hooks/' target='_blank'>plugin-friendly</a>. Also, check the &#8220;Footer Link Settings&#8221; tab and make sure that the &#8220;Link Section Format&#8221; field includes the <code>{links}</code> variable and that the &#8220;Link Format&#8221; field includes the <code>{link}</code> variable.</li>
</ul>
", 'seo-ultimate')));
		
	}
}

}
?>