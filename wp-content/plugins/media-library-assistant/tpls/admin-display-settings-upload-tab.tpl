<!-- template="icon-type-select-option" -->
                <option [+selected+] value="[+value+]">[+text+]</option>

<!-- template="icon-type-select" -->
            <select name="[+name+]" class="ptitle" id="mla-upload-icon-type">
[+options+]
            </select>

<!-- template="single-item-edit" -->
<h3>Edit Upload MIME Type</h3>
<form action="[+form_url+]" method="post" class="validate" id="mla-edit-upload">
	<input type="hidden" name="page" value="mla-settings-menu-upload" />
	<input type="hidden" name="mla_tab" value="upload" />
	<input type="hidden" name="mla_admin_action" value="[+action+]" />
	<input type="hidden" name="mla_upload_item[original_slug]" value="[+original_slug+]" />
	[+_wpnonce+]
	<table class="form-table">
	<tr class="form-field form-required">
	<th scope="row" valign="top"><label for="mla-upload-slug">Extension</label></th>
	<td>
	<input name="mla_upload_item[slug]" id="mla-upload-slug" type="text" value="[+slug+]" size="40" aria-required="true" />
	<p class="description">The &#8220;extension&#8221; is the file extension for this type, and a unique key for the item. It must be all lowercase and contain only letters and numbers.</p>
	</td>
	</tr>
	<tr class="form-field form-required">
	<th scope="row" valign="top"><label for="mla-upload-mime-type">MIME Type</label></th>
	<td>
						<input name="mla_upload_item[mime_type]" id="mla-upload-mime-type" type="text" value="[+mime_type+]" size="40" />
						<p class="description">The MIME Type must be all lowercase and contain only letters, numbers, periods (.), slashes (/) and hyphens (-). It <strong>must be a valid MIME</strong> type, e.g., &#8220;image&#8221; or &#8220;image/jpeg&#8221;.</p>
	</td>
	</tr>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="mla-upload-icon-type">Icon Type</label></th>
	<td>
[+icon_types+]
						<p class="description">The Icon Type selects a thumbnail image displayed for non-image file types, such as PDF documents.</p>
	</td>
	</tr>
	<tr>
	<th scope="row" valign="top"><label for="mla-upload-disabled">Inactive</label></th>
	<td>
						<input type="checkbox" name="mla_upload_item[disabled]" id="mla-upload-post-disabled" [+disabled+] value="1" />
						<span class="description">&nbsp;Check this box if you want to remove this entry from the list of Upload MIME Types returned by get_allowed_mime_types().</span>
	</td>
	</tr>
	<tr class="form-field">
	<th scope="row" valign="top"><label for="mla-upload-description">Description</label></th>
	<td>
						<textarea name="mla_upload_item[description]" id="mla-upload-description" rows="5" cols="40">[+description+]</textarea>
						<p class="description">The description can contain any documentation or notes you need to understand or use the item.</p>
	</td>
	</tr>
</table>
<p class="submit">
<input name="update" type="submit" class="button-primary" value="Update" />&nbsp;
<input name="cancel" type="submit" class="button-primary" value="Cancel" />&nbsp;
</p>
</form>
<!-- template="upload-disabled" -->
<h3>Upload MIME Type Support is disabled</h3>
<form action="[+form_url+]" method="post" class="mla-display-settings-page" id="mla-display-settings-upload-tab">
    <table class="optiontable">
[+options_list+]
	</table>
    <p class="submit" style="padding-bottom: 0;">
        <input name="mla-upload-options-save" type="submit" class="button-primary" value="Save Changes" />
    </p>
	<input type="hidden" name="page" value="mla-settings-menu-upload" />
	<input type="hidden" name="mla_tab" value="upload" />
	[+_wpnonce+]
</form>

<!-- template="before-optional-uploads-table" -->
<form action="[+form_url+]" method="get" id="mla-search-optional-uploads-form">
	<input type="hidden" name="page" value="mla-settings-menu-upload" />
	<input type="hidden" name="mla_tab" value="upload" />
	[+_wpnonce+]
	<H3 style="width: 600px; float: left">Known File Extension/MIME Type Associations[+results+]</H3>
	<p class="search-box" style="margin-top: 1em">
		<label class="screen-reader-text" for="mla-search-optional-uploads-input">Search Known MIME Types:</label>
		<input type="search" id="mla-search-optional-uploads-input" name="s" value="[+s+]" />
		<input type="submit" name="mla-optional-uploads-search" id="mla-search-optional-uploads-submit" class="button" value="Search Types" />
		<span class="description"><br />To search by extension, use ".", e.g., ".doc"</span>
	</p>
</form>
<br class="clear" />
<div id="col-container">
	<form action="[+form_url+]" method="post" id="mla-search-optional-uploads-filter">
		<input type="hidden" name="page" value="mla-settings-menu-upload" />
		<input type="hidden" name="mla_tab" value="upload" />
		[+_wpnonce+]
		
<!-- template="after-optional-uploads-table" -->
		<p class="submit">
		<input name="mla-optional-uploads-cancel" type="submit" class="button-primary" value="Cancel" />&nbsp;
		</p>
	</form><!-- /id=mla-search-uploads-filter --> 
</div><!-- /col-container -->

<!-- template="before-table" -->
<h3>File Extension and MIME Type Processing</h3>
<p>On this tab you can manage the list of file extension/MIME Type associations, which are used by WordPress to decide what kind of files can be uploaded to the Media Library and to fill in the <strong><em>post_mime_type</em></strong> value. To upload a file, the file extension must be in this list and be active. You can find more information in the <a href="/wp-admin/options-general.php?page=mla-settings-menu-documentation&amp;mla_tab=documentation#mla_uploads">Documentation tab</a> or by clicking the <strong>"Help"</strong> tab in the upper-right corner of this screen.</p>
<div id="ajax-response"></div>
<form action="[+form_url+]" method="get" id="mla-search-uploads-form">
	<input type="hidden" name="page" value="mla-settings-menu-upload" />
	<input type="hidden" name="mla_tab" value="upload" />
	[+_wpnonce+]
	[+results+]
	<p class="search-box" style="margin-top: 1em">
		<label class="screen-reader-text" for="mla-search-uploads-input">Search Upload MIME Types:</label>
		<input type="search" id="mla-search-uploads-input" name="s" value="[+s+]" />
		<input type="submit" name="" id="mla-search-uploads-submit" class="button" value="Search Uploads" />
		<span class="description"><br />To search by extension, use ".", e.g., ".doc"</span>
	</p>
</form>
<br class="clear" />
<div id="col-container">
	<div id="col-right">
		<div class="col-wrap">
			<form action="[+form_url+]" method="post" id="mla-search-uploads-filter">
				<input type="hidden" name="page" value="mla-settings-menu-upload" />
				<input type="hidden" name="mla_tab" value="upload" />
				[+_wpnonce+]
				
<!-- template="after-table" -->
			</form><!-- /id=mla-search-uploads-filter --> 
		</div><!-- /col-wrap --> 
		<div style="margin-left: 10px; margin-top: 10px;">
		<form action="[+form_url+]" method="post" class="mla-display-settings-page" id="mla-display-settings-upload-tab">
			<table class="optiontable">
		[+options_list+]
			</table>
			<span class="submit" style="padding-bottom: 0;">
				<input name="mla-upload-options-save" type="submit" class="button-primary" value="Save Changes" />
			</span>
		[+_wpnonce+]
		</form>
		</div>
	</div><!-- /col-right -->
	<div id="col-left">
		<div class="col-wrap">
			<div class="form-wrap">
				<h3>Add New Upload MIME Type</h3>
						<p class="description">To search the database of over 1,500 known extension/type associations, click "Search Known Types" below the form.</p>
				<form action="[+form_url+]" method="post" class="validate" id="mla-add-upload">
					<input type="hidden" name="page" value="mla-settings-menu-upload" />
					<input type="hidden" name="mla_tab" value="upload" />
					[+_wpnonce+]
					<div class="form-field form-required">
						<label for="mla-upload-slug">Extension</label>
						<input name="mla_upload_item[slug]" id="mla-upload-slug" type="text" value="[+slug+]" size="40" />
						<p class="description">The &#8220;extension&#8221; is the file extension for this type, and unique key for the item. It must be all lowercase and contain only letters and numbers.</p>
					</div>
					<div class="form-field form-required">
						<label for="mla-upload-mime-type">MIME Type</label>
						<input name="mla_upload_item[mime_type]" id="mla-upload-mime-type" type="text" value="[+mime_type+]" size="40" />
						<p class="description">The MIME Type must be all lowercase and contain only letters, numbers, periods (.), slashes (/) and hyphens (-). It <strong>must be a valid MIME</strong> type, e.g., &#8220;image&#8221; or &#8220;image/jpeg&#8221;.</p>
					</div>
					<div class="form-field">
						<label for="mla-upload-mime-type">Icon Type</label>
[+icon_types+]
						<p class="description">The Icon Type selects a thumbnail image displayed for non-image file types, such as PDF documents.</p>
					</div>
					<div>
						<input type="checkbox" name="mla_upload_item[disabled]" id="mla-upload-disabled" [+disabled+] value="1" />
						Inactive
						<p class="description">&nbsp;Check this box if you want to remove this entry from the list of Upload MIME Types returned by get_allowed_mime_types().</p>
					</div>
					<div class="form-field">
						<label for="mla-upload-description">Description</label>
						<textarea name="mla_upload_item[description]" id="mla-upload-description" rows="5" cols="40">[+description+]</textarea>
						<p class="description">The description can contain any documentation or notes you need to understand or use the item.</p>
					</div>
					<p class="submit">
						<input type="submit" name="mla-add-upload-submit" id="mla-add-upload-submit" class="button button-primary" value="Add Upload MIME Type" /> <a href="[+search_url+]" class="button">Search Known Types</a>
					</p>
				</form><!-- /id=mla-add-upload --> 
			</div><!-- /form-wrap --> 
		</div><!-- /col-wrap -->
	</div><!-- /col-left --> 
</div><!-- /col-container -->
<script type="text/javascript">
try{document.forms.addtag['mla-upload-slug'].focus();}catch(e){}
</script> 
<form>
	<table width="99%" style="display: none">
		<tbody id="inlineedit">
			<tr id="inline-edit" class="inline-edit-row inline-edit-row-upload inline-edit-upload quick-edit-row quick-edit-row-upload quick-edit-upload" style="display: none">
				<td colspan="[+colspan+]" class="colspanchange">
					<fieldset class="inline-edit-col">
						<div class="inline-edit-col">
							<h4>Quick Edit</h4>
							<label> <span class="title">Extension</span> <span class="input-text-wrap">
								<input type="text" name="slug" class="ptitle" value="" />
								</span> </label>
							<label> <span class="title">MIME Type</span> <span class="input-text-wrap">
								<input type="text" name="mime_type" class="ptitle" value="" />
								</span> </label>
							<br clear="all">
							<label> <span class="title">Icon Type</span> <span class="input-text-wrap">
[+inline_icon_types+]
								</span> </label>
							<span class="input-text-wrap">
								<input type="checkbox" name="disabled" class="ptitle" checked="checked" value="1" />
								</span> <span class="title">Inactive</span> 
						</div>
					</fieldset>
					<p class="inline-edit-save submit"> <a accesskey="c" href="#inline-edit" title="Cancel" class="cancel button-secondary alignleft">Cancel</a> <a accesskey="s" href="#inline-edit" title="Update Upload MIME Type" class="save button-primary alignright">Update Upload MIME Type</a>
						<input type="hidden" name="original_slug" value="" />
						<input type="hidden" name="page" value="mla-settings-menu-upload" />
						<input type="hidden" name="mla_tab" value="upload" />
						<input type="hidden" name="screen" value="settings_page_mla-settings-menu-upload" />
						<span class="spinner"></span>
						<span class="error" style="display: none;"></span>
						<br class="clear" />
					</p>
				</td>
			</tr>
			<tr id="bulk-edit" class="inline-edit-row inline-edit-row-upload inline-edit-upload bulk-edit-row bulk-edit-row-upload bulk-edit-upload" style="display: none">
				<td colspan="[+colspan+]" class="colspanchange">
					<h4>Bulk Edit</h4>
					<fieldset class="inline-edit-col-left">
						<div class="inline-edit-col">
							<div id="bulk-title-div">
								<div id="bulk-titles"></div>
							</div>
						</div>
					</fieldset>
					<fieldset class="inline-edit-col-right">
						<div class="inline-edit-col">
							<label class="inline-edit-disabled"> <span class="title">Status</span> <span class="input-text-wrap">
								<select name="disabled">
									<option selected="selected" value="-1">&mdash; No Change &mdash;</option>
									<option value="0">Active</option>
									<option value="1">Inactive</option>
								</select>
								</span> </label>
							<label class="title"> <span class="title">Icon Type</span> <span class="input-text-wrap">
[+bulk_icon_types+]
								</span> </label>
						</div>
					</fieldset>
					<p class="submit inline-edit-save"> <a accesskey="c" href="#inline-edit" title="Cancel" class="button-secondary cancel alignleft">Cancel</a>
						<input accesskey="s" type="submit" name="bulk_edit" id="bulk_edit" class="button-primary alignright" value="Update"  />
						<input type="hidden" name="page" value="mla-settings-menu-upload" />
						<input type="hidden" name="mla_tab" value="upload" />
						<input type="hidden" name="screen" value="settings_page_mla-settings-menu-upload" />
						<span class="error" style="display:none"></span> <br class="clear" />
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</form>
