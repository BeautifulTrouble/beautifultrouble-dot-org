<!-- template="page" -->
<a name="backtotop"></a>
&nbsp;
<div class="wrap">
<div id="icon-options-general" class="icon32"><br/></div>
<div id="donate-button-div" style="text-align: center; width: 100px; height: 40px; float: right; clear:right; margin-top: 5px">
<a href="http://fairtradejudaica.org/make-a-difference/donate/" title="Support our work" target="_blank" style="border: none;"><img border="0" src="[+donateURL+]" width="100" height="40" alt="Donate"></a></div>
<h2>[+Media Library Assistant+] [+version+] [+Settings+]</h2>
[+messages+]
[+tablist+]
[+tab_content+]
</div><!-- wrap -->

<!-- template="checkbox" -->
        <tr valign="top"><td style="text-align:right;">
            <input type="checkbox" name="[+key+]" id="[+key+]" [+checked+] value="[+value+]" />
        </td><td>
		    &nbsp;<strong>[+value+]</strong>
            <div style="font-size:8pt;padding-bottom:10px;">&nbsp;&nbsp;[+help+]</div>
		</td></tr>
<!-- template="header" -->
        <tr><td colspan="2">
            <a href="#backtotop">[+Go to Top+]</a>
        </td></tr>
        <tr><td colspan="2">
            <h3 id="[+key+]">[+value+]</h3>
        </td></tr>
<!-- template="subheader" -->
        <tr><td colspan="2">
            <h4 id="[+key+]">[+value+]</h4>
        </td></tr>
<!-- template="radio" -->
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+value+]
        </th><td style="text-align:left;">
            <span style="font-size:8pt;padding-bottom:10px;">&nbsp;&nbsp;[+help+]</span>
        </td></tr>
[+options+]
        <tr valign="top"><td colspan="2" style="padding-bottom:10px;">
        </td></tr>
<!-- template="radio-option" -->
        <tr valign="top"><td style="text-align:right;">
            <input type="radio" name="[+key+]" [+checked+] value="[+option+]" />
        </td><td>
            &nbsp;[+value+]
        </td></tr>
<!-- template="select" -->
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+value+]
        </th><td style="text-align:left;">
            <select name="[+key+]" id="[+key+]">
[+options+]
            </select><div style="font-size:8pt;padding-bottom:10px;">&nbsp;&nbsp;[+help+]</div>
        </td></tr>
<!-- template="select-only" -->
            <select name="[+key+]" id="[+key+]">
[+options+]
            </select>
<!-- template="select-option" -->
                <option [+selected+] value="[+value+]">[+text+]</option>
<!-- template="text" -->
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+value+]
        </th><td style="text-align:left;">
            <input name="[+key+]" id="[+key+]" type="text" size="[+size+]" value="[+text+]" />
            <div style="font-size:8pt;padding-bottom:10px;">&nbsp;&nbsp;[+help+]</div>
        </td></tr>
<!-- template="textarea" -->
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+value+]
        </th><td style="text-align:left;">
            <textarea name="[+key+]" id="[+key+]" rows="[+rows+]" cols="[+cols+]">
            [+text+]
            </textarea>
            <div style="font-size:8pt;padding-bottom:10px;">&nbsp;&nbsp;[+help+]</div>
        </td></tr>
<!-- template="messages" -->
<div class="[+mla_messages_class+]">
<p>
[+messages+]
</p></div>
<!-- template="shortcode-list" -->
<div id="mla-shortcode-list" style="width: 90%; padding-left: 5%; ">
<p>[+Shortcodes made available+]:</p>
<ol>
[+shortcode_list+]
</ol>
</div>
<!-- template="shortcode-item" -->
<li><code>[[+name+]]</code> - [+description+]</li>
<!-- template="tablist" -->
<h2 class="nav-tab-wrapper">
[+tablist+]
</h2>
<!-- template="tablist-item" -->
<a data-tab-id="[+data-tab-id+]" class="nav-tab [+nav-tab-active+]" href="?page=[+settings-page+]&amp;mla_tab=[+data-tab-id+]">[+title+]</a>
<!-- template="general-tab" -->
<h3>[+General Processing Options+]</h3>
<p>[+In this tab+]</p>
[+shortcode_list+]
<form action="[+form_url+]" method="post" class="mla-display-settings-page" id="mla-display-settings-general-tab">
    <table class="optiontable">
[+options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="mla-general-options-save" type="submit" class="button-primary" value="[+Save Changes+]" />&nbsp;&nbsp;
        <input name="mla-general-options-export" type="submit" class="button-primary" value="[+Export ALL Settings+]" />
        [+import_settings+]
        <input name="mla-general-options-reset" type="submit" class="button-primary" value="[+Delete General options+]"  style="float:right;"/>
    </p>
[+_wpnonce+]
[+_wp_http_referer+]
</form>
<p>
<a href="#backtotop">[+Go to Top+]</a>
</p>
<h3>[+Support Our Work+]</h3>
<table width="700" border="0" cellpadding="10">
	<tr>
		<td><a href="http://fairtradejudaica.org/make-a-difference/donate/" title="[+Donate to FTJ+]" target="_blank" style="border: none;"><img border="0" src="[+donateURL+]" width="100" height="40" alt="[+Donate+]"></a></td>
		<td>[+This plugin was+]</td>
	</tr>
</table>
<!-- template="mla-gallery-default" -->
		<td colspan="2" width="500">
            <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
		</td>
<!-- template="mla-gallery-delete" -->
		<td width="1%" style="text-align:right;">
            <input type="checkbox" name="[+name+]" id="[+id+]" value="[+value+]" />
        </td><td width="500">
		    &nbsp;<strong>[+value+]</strong>
            <div style="font-size:8pt;padding-bottom:10px;">&nbsp;[+help+]</div>
		</td>
<!-- template="mla-gallery-style" -->
<table width="700">
        <tr valign="top"><th width="1%" scope="row" style="text-align:right;">
            [+Name+]:
        </th><td width="1%" style="text-align:left;">
            <input name="[+name_name+]" id="[+name_id+]" type="text" size="15" [+readonly+] value="[+name_text+]" />
        </td>
		[+control_cells+]
		</tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+Styles+]:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+value_name+]" id="[+value_id+]" rows="11" cols="100" [+readonly+]>[+value_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+value_help+]</div>
        </td></tr>
</table>
<!-- template="mla-gallery-markup" -->
<table width="700">
        <tr valign="top"><th width="1%" scope="row" style="text-align:right;">
            [+Name+]:
        </th><td width="1%" style="text-align:left;">
            <input name="[+name_name+]" id="[+name_id+]" type="text" size="15" [+readonly+] value="[+name_text+]" />
        </td>
		[+control_cells+]
		</tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+Open+]:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+open_name+]" id="[+open_id+]" rows="3" cols="100" [+readonly+]>[+open_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+open_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+Row+]&nbsp;[+Open+]:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+row_open_name+]" id="[+row_open_id+]" rows="3" cols="100" [+readonly+]>[+row_open_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+row_open_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+Item+]:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+item_name+]" id="[+item_id+]" rows="6" cols="100" [+readonly+]>[+item_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+item_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+Row+]&nbsp;[+Close+]:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+row_close_name+]" id="[+row_close_id+]" rows="3" cols="100" [+readonly+]>[+row_close_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+row_close_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            [+Close+]:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+close_name+]" id="[+close_id+]" rows="3" cols="100" [+readonly+]>[+close_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+close_help+]</div>
        </td></tr>
</table>
<hr width="650" align="left" />

<!-- template="mla-gallery-tab" -->
<h3>[+MLA Gallery Options+]</h3>
<p><a href="#markup">[+Go to Markup Templates+]</a></p>
<p>[+In this tab+]</p>
<form action="[+form_url+]" method="post" class="mla-display-settings-page" id="mla-display-settings-mla-gallery-tab">
[+options_list+]
<h4>[+Style Templates+]</h4>
    <table class="optiontable">
[+style_options_list+]
	</table>
<a name="markup">&nbsp;<br /></a><h4>[+Markup Templates+]</h4>
    <table class="optiontable">
[+markup_options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="mla-gallery-options-save" type="submit" class="button-primary" value="[+Save Changes+]" />
    </p>
[+_wpnonce+]
[+_wp_http_referer+]
</form>

<!-- template="mla-progress-div" -->
<div class="wrap" id="mla-progress-div" style="display:none; border-bottom:1px solid #cccccc">
	<h3>[+Mapping Progress+]</h3>
	<p style="font-weight:bold">[+DO NOT+]:</p>
	<ol>
		<li>[+DO NOT Close+]</li>
		<li>[+DO NOT Reload+]</li>
		<li>[+DO NOT Click+]</li>
	</ol>
	<p style="font-weight:bold">[+Progress+]:</p>
	<div id="mla-progress-meter-div" style="padding: 3px; border: 1px solid rgb(101, 159, 255); border-image: none; width: 80%; height: 11px;">
		<div id="mla-progress-meter" style="width: 100%; height: 11px; text-align: center; color: rgb(255, 255, 255); line-height: 11px; font-size: 6pt; background-color: rgb(101, 159, 255);">100%
		</div>
	</div>
	<div id="mla-progress-message">&nbsp;</div>
	<p class="submit inline-edit-save">
		<a title="[+Cancel+]" class="button-secondary alignleft" id="mla-progress-cancel" accesskey="c" href="#mla-progress">[+Cancel+]</a>
		<a title="[+Resume+]" class="button-secondary alignleft" id="mla-progress-resume" accesskey="r" href="#mla-progress">[+Resume+]</a>
		<input name="mla_resume_offset" id="mla-progress-offset" type="text" size="3" />
		<a title="[+Close+]" class="button-primary alignright" id="mla-progress-close" accesskey="x" href="#mla-progress">[+Close+]</a>
		<a title="[+Refresh+]" class="button-primary alignright" id="mla-progress-refresh" accesskey="f" href="[+refresh_href+]">[+Refresh+]</a>
		<span class="spinner"></span>
		<span id="mla-progress-error" style="display:inline"></span><br class="clear" />
	</p>
</div>

<!-- template="custom-field-tab" -->
[+mla-progress-div+]
<h3>[+Custom Field Options+]</h3>
<p>
[+In this tab+]
</p>
<p>
[+You can find+]
</p>
<form action="[+form_url+]" method="post" class="mla-display-settings-page" id="mla-display-settings-custom-field-tab">
&nbsp;<br />
[+options_list+]
<h4>[+Custom field mapping+]</h4>
    <table class="optiontable">
[+custom_options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="custom-field-options-save" class="button-primary" id="custom-field-options-save" type="submit" value="[+Save Changes+]" />
        <input name="custom-field-options-map" class="button-secondary mla-mapping" id="custom-field-options-map" style="margin-left: 20px" type="submit" value="[+Map All Rules+]" />
	<div style="font-size:8pt;padding-bottom:5px;">[+Click Save Changes+]</div>
	<div style="font-size:8pt;padding-bottom:5px;">[+Click Map All+]</div>
    </p>
[+_wpnonce+]
[+_wp_http_referer+]
</form>

<!-- template="iptc-exif-tab" -->
[+mla-progress-div+]
<h3>[+IPTX/EXIF Options+]</h3>
<p>
[+In this tab+]
</p>
<p>
[+You can find+]
</p>
<form action="[+form_url+]" method="post" class="mla-display-settings-page" id="mla-display-settings-iptc-exif-tab">
&nbsp;<br />
[+options_list+]
<h4>[+Standard field mapping+] <input name="iptc-exif-options-process-standard" class="button-secondary mla-mapping" id="iptc-exif-options-process-standard" style="margin-left: 20px" type="submit" value="[+Map Standard Fields+]" /></h4>
    <table class="optiontable">
[+standard_options_list+]
	</table>
<h4>[+Taxonomy term mapping+] <input name="iptc-exif-options-process-taxonomy" class="button-secondary mla-mapping" id="iptc-exif-options-process-taxonomy" style="margin-left: 10px" type="submit" value="[+Map Taxonomy Terms+]" /></h4>
    <table class="optiontable">
[+taxonomy_options_list+]
	</table>
<h4>[+Custom field mapping+] <input name="iptc-exif-options-process-custom" class="button-secondary mla-mapping" id="iptc-exif-options-process-custom" style="margin-left: 25px" type="submit" value="[+Map Custom Fields+]" /></h4>
    <table class="optiontable">
[+custom_options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="iptc-exif-options-save" class="button-primary" id="iptc-exif-options-save" type="submit" value="[+Save Changes+]" />
	<div style="font-size:8pt;padding-bottom:5px;">[+Click Save Changes+]</div>
    </p>
[+_wpnonce+]
[+_wp_http_referer+]
</form>
