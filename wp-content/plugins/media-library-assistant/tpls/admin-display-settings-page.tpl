<!-- template="page" -->
<a name="backtotop"></a>
&nbsp;
<div class="wrap">
<div id="icon-options-general" class="icon32"><br/></div>
<div id="donate-button-div" style="text-align: center; width: 100px; height: 40px; float: right; clear:right; margin-top: 5px">
<a href="http://fairtradejudaica.org/make-a-difference/donate/" title="Support our work" target="_blank" style="border: none;"><img border="0" src="[+donateURL+]" width="100" height="40" alt="Donate"></a></div>
<h2>Media Library Assistant [+version+] Settings</h2>
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
            <a href="#backtotop">Go to Top</a>
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
<p>Shortcodes made available by this plugin:</p>
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
<h3>General Processing Options</h3>
<p>In this tab you can find a number of options for controlling the plugin's operation. Scroll down to find options for <strong>Where-used Reporting</strong>, <strong>Taxonomy Support</strong>, <strong>Media/Assistant Table Defaults</strong> and <strong>Media Manager Enhancements</strong>. Be sure to click "Save Changes" at the bottom of the tab to save any changes you make.</p>
[+shortcode_list+]
<form action="[+form_url+]" method="post" class="mla-display-settings-page" id="mla-display-settings-general-tab">
    <table class="optiontable">
[+options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="mla-general-options-save" type="submit" class="button-primary" value="Save Changes" />
        <input name="mla-general-options-reset" type="submit" class="button-primary" value="Delete General options and restore default settings"  style="float:right;"/>
    </p>
[+_wpnonce+]
[+_wp_http_referer+]
</form>
<p>
<a href="#backtotop">Go to Top</a>
</p>
<h3>Support Our Work</h3>
<table width="700" border="0" cellpadding="10">
	<tr>
		<td><a href="http://fairtradejudaica.org/make-a-difference/donate/" title="Donate to FTJ" target="_blank" style="border: none;"><img border="0" src="[+donateURL+]" width="100" height="40" alt="Donate"></a></td>
		<td>This plugin was inspired by my work on the WordPress web site for our nonprofit, Fair Trade Judaica. If you find the Media Library Assistant plugin useful and would like to support a great cause, consider a <a href="http://fairtradejudaica.org/make-a-difference/donate/" title="Donate to FTJ" target="_blank" style="font-weight:bold">tax-deductible donation</a> to our work. Thank you!</td>
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
            Name:
        </th><td width="1%" style="text-align:left;">
            <input name="[+name_name+]" id="[+name_id+]" type="text" size="15" [+readonly+] value="[+name_text+]" />
        </td>
		[+control_cells+]
		</tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Styles:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+value_name+]" id="[+value_id+]" rows="11" cols="100" [+readonly+]>[+value_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+value_help+]</div>
        </td></tr>
</table>
<!-- template="mla-gallery-markup" -->
<table width="700">
        <tr valign="top"><th width="1%" scope="row" style="text-align:right;">
            Name:
        </th><td width="1%" style="text-align:left;">
            <input name="[+name_name+]" id="[+name_id+]" type="text" size="15" [+readonly+] value="[+name_text+]" />
        </td>
		[+control_cells+]
		</tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Open:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+open_name+]" id="[+open_id+]" rows="3" cols="100" [+readonly+]>[+open_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+open_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Row&nbsp;Open:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+row_open_name+]" id="[+row_open_id+]" rows="3" cols="100" [+readonly+]>[+row_open_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+row_open_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Item:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+item_name+]" id="[+item_id+]" rows="6" cols="100" [+readonly+]>[+item_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+item_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Row&nbsp;Close:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+row_close_name+]" id="[+row_close_id+]" rows="3" cols="100" [+readonly+]>[+row_close_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+row_close_help+]</div>
        </td></tr>
        <tr valign="top"><th scope="row" style="text-align:right;">
            Close:
        </th><td colspan="3" style="text-align:left;">
            <textarea name="[+close_name+]" id="[+close_id+]" rows="3" cols="100" [+readonly+]>[+close_text+]</textarea>
            <div style="font-size:8pt;padding-bottom:5px;">&nbsp;&nbsp;[+close_help+]</div>
        </td></tr>
</table>
<hr width="650" align="left" />

<!-- template="mla-gallery-tab" -->
<h3>MLA Gallery Options</h3>
<p><a href="#markup">Go to Markup Templates</a></p>
<p>In this tab you can view the default style and markup templates. You can also define additional templates and use the <code>mla_style</code> and <code>mla_markup</code> parameters to apply them in your <code>[mla_gallery]</code> shortcodes.<strong>NOTE:</strong> template additions and changes will not be made permanent until you click "Save Changes" at the bottom of this page.</p>
<form action="[+form_url+]" method="post" class="mla-display-settings-page" id="mla-display-settings-mla-gallery-tab">
[+options_list+]
<h4>Style Templates</h4>
    <table class="optiontable">
[+style_options_list+]
	</table>
<a name="markup">&nbsp;<br /></a><h4>Markup Templates</h4>
    <table class="optiontable">
[+markup_options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="mla-gallery-options-save" type="submit" class="button-primary" value="Save Changes" />
    </p>
[+_wpnonce+]
[+_wp_http_referer+]
</form>

<!-- template="custom-field-tab" -->
<h3>Custom Field Processing Options</h3>
<p>
In this tab you can define the rules for mapping several types of image metadata to WordPress custom fields. <strong>NOTE:</strong> changes to a single rule will be made permanent if you click the "Update Rule" button for that rule. The rule will be applied to existing attachments if you click the "Map All Attachments" button for that rule.
</p>
<p>
Custom field mapping can be applied automatically when an attachment is added to the Media Library. You can refresh the mapping for <strong><em>ALL</em></strong> attachments using the command buttons on this screen. You can selectively apply the mapping in the bulk edit area of the Media/Assistant screen and/or on the Media/Edit Media screen for a single attachment.
</p>
<p>
If you click any of the "Map All Attachments" buttons, the rule currently displayed for that field will be immediately applied to <strong><em>ALL</em></strong> of the attachments in your site. Rule changes are <strong><em>NOT</em></strong> saved when you click any of these buttons, and <strong><em>THERE IS NO UNDO FOR THESE ACTIONS!</em></strong>
</p>
<p>
If you just want to add a custom field to the Media/Assistant submenu, the quick edit area and/or the bulk edit area you can bypass the mapping logic by leaving the Data Source value as "-- None (select a value) --".
</p>
<p>
You can find more information about all of these topics on the <a href="http:/wp-admin/options-general.php?page=mla-settings-menu-documentation&amp;mla_tab=documentation#mla_custom_field_mapping" title="Custom Field Processing Options documentation">Documentation tab</a>.
</p>
<p>
<strong>NOTE:</strong> Check the box in the "MLA Column" column to make the field a sortable, searchable column in the Media/Assistant submenu table. The "file_size", "pixels", "width" and "height" data sources will sort more sensibly if you use the "Commas" format. Check the "Quick Edit" or "Bulk Edit" box to add the field to the quick edit or bulk edit areas of the Media/Assistant submenu.
</p>
<form action="[+form_url+]" method="post" class="mla-display-settings-page" id="mla-display-settings-custom-field-tab">
&nbsp;<br />
[+options_list+]
<h4>Custom field mapping</h4>
    <table class="optiontable">
[+custom_options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="custom-field-options-save" type="submit" class="button-primary" value="Save Changes" />
        <input name="custom-field-options-map" type="submit" class="button-secondary" style="margin-left: 20px" value="Map All Rules, All Attachments Now" />
    </p>
	<p>
	Click <strong>Save Changes</strong> to update the "Enable custom field mapping..." checkbox and/or all rule changes and additions at once. <strong>No rule mapping will be performed.</strong><br />
	Click Map All Rules, All Attachments Now to apply all the rules at once (rule changes will be applied but not saved).
    </p>
[+_wpnonce+]
[+_wp_http_referer+]
</form>

<!-- template="iptc-exif-tab" -->
<h3>IPTC &amp; EXIF Processing Options</h3>
<p>In this tab you can define the rules for mapping IPTC (International Press Telecommunications Council) and EXIF (EXchangeable Image File) metadata to WordPress standard attachment fields, taxonomy terms and custom fields. <strong>NOTE:</strong> settings changes will not be made permanent until you click "Save Changes" at the bottom of this page.</p>
<p>If you click any of the three "Map All Attachments, ... Now" buttons, the rules currently displayed in that category will be immediately applied to <strong>all</strong> of the attachments in your site. Rule changes are <strong>not</strong> saved when you click any of these buttons.<strong>THERE IS NO UNDO FOR THESE ACTIONS!</strong></p>
<p>
If you just want to add a custom field to the Media/Assistant submenu, the quick edit area and/or the bulk edit area go to the "Custom Fields" tab and follow the instructions there.
</p>
<p>
You can find more information about all of these topics on the <a href="http:/wp-admin/options-general.php?page=mla-settings-menu-documentation&amp;mla_tab=documentation#mla_iptc_exif_mapping" title="IPTC &amp; EXIF Processing Options documentation">Documentation tab</a>.
</p>
<form action="[+form_url+]" method="post" class="mla-display-settings-page" id="mla-display-settings-iptc-exif-tab">
&nbsp;<br />
[+options_list+]
<h4>Standard field mapping <input name="iptc-exif-options-process-standard" type="submit" class="button-secondary" style="margin-left: 20px" value="Map All Attachments, Standard Fields Now" /></h4>
    <table class="optiontable">
[+standard_options_list+]
	</table>
<h4>Taxonomy term mapping <input name="iptc-exif-options-process-taxonomy" type="submit" class="button-secondary" style="margin-left: 10px" value="Map All Attachments, Taxonomy Terms Now" /></h4>
    <table class="optiontable">
[+taxonomy_options_list+]
	</table>
<h4>Custom field mapping <input name="iptc-exif-options-process-custom" type="submit" class="button-secondary" style="margin-left: 25px" value="Map All Attachments, Custom Fields Now" /></h4>
    <table class="optiontable">
[+custom_options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="iptc-exif-options-save" type="submit" class="button-primary" value="Save Changes" />
    </p>
	<p>
	Click <strong>Save Changes</strong> to update the "Enable IPTC/EXIF mapping..." checkbox and/or all rule changes and additions at once. <strong>No rule mapping will be performed.</strong><br />
    </p>
[+_wpnonce+]
[+_wp_http_referer+]
</form>
