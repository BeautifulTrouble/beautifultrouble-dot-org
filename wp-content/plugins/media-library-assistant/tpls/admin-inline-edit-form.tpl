<!-- template="category_fieldset" -->
          <fieldset class="inline-edit-col-center inline-edit-categories"><div class="inline-edit-col">
[+category_blocks+]          </div></fieldset>
<!-- template="category_block" -->
            <span class="title inline-edit-categories-label">[+tax_html+]
              <span class="catshow">[more]</span>
              <span class="cathide" style="display:none;">[less]</span>
            </span>
            <input type="hidden" name="tax_input[[+tax_attr+]][]" value="0" />
            <ul class="cat-checklist [+tax_attr+]-checklist">
[+tax_checklist+]
            </ul>

<!-- template="tag_fieldset" -->
          <fieldset class="inline-edit-col-right"><div class="inline-edit-col">
[+tag_blocks+]          </div></fieldset>
<!-- template="tag_block" -->
            <label class="inline-edit-tags">
              <span class="title">[+tax_html+]</span>
              <textarea cols="22" rows="1" name="tax_input[[+tax_attr+]]" class="tax_input_[+tax_attr+] mla_tags"></textarea>
            </label>

<!-- template="taxonomy_options" -->
			<div class="mla_bulk_taxonomy_options">
            <input type="radio" name="tax_action[[+tax_attr+]]" id="tax_add_[+tax_attr+]" checked="checked" value="add" /> Add&nbsp;
            <input type="radio" name="tax_action[[+tax_attr+]]" id="tax_remove_[+tax_attr+]" value="remove" /> Remove&nbsp;
            <input type="radio" name="tax_action[[+tax_attr+]]" id="tax_reset_[+tax_attr+]" value="replace" /> Replace&nbsp;
            </div>
<!-- template="custom_field" -->
              <label class="inline-edit-[+slug+]"> <span class="title">[+label+]</span> <span class="input-text-wrap">
                <input type="text" name="[+slug+]" value="" />
                </span> </label>
<!-- template="page" -->
<form>
  <table width="99%" style="display: none">
    <tbody id="inlineedit">
      <tr id="inline-edit" class="inline-edit-row inline-edit-row-attachment inline-edit-attachment quick-edit-row quick-edit-row-attachment quick-edit-attachment" style="display: none">
        <td colspan="[+colspan+]" class="colspanchange">
          <fieldset class="inline-edit-col-left">
            <div class="inline-edit-col">
              <h4>Quick Edit</h4>
              <label> <span class="title">Title</span> <span class="input-text-wrap">
                <input type="text" name="post_title" class="ptitle" value="" />
                </span> </label>
              <label> <span class="title">Name/Slug</span> <span class="input-text-wrap">
                <input type="text" name="post_name" value="" />
                </span> </label>
              <label> <span class="title">Caption</span> <span class="input-text-wrap">
                <input type="text" name="post_excerpt" value="" />
                </span> </label>
              <label class="inline-edit-image-alt"> <span class="title">Alt Text</span> <span class="input-text-wrap">
                <input type="text" name="image_alt" value="" />
                </span> </label>
              <label class="inline-edit-post-parent"> <span class="title">Parent ID</span> <span class="input-text-wrap">
                <input type="text" name="post_parent" value="" />
                </span> </label>
              <label class="inline-edit-menu-order"> <span class="title">Menu Order</span> <span class="input-text-wrap">
                <input type="text" name="menu_order" value="" />
                </span> </label>
[+authors+]
[+custom_fields+]
            </div>
          </fieldset>
[+quick_middle_column+]
[+quick_right_column+]
          <p class="submit inline-edit-save">
		  	<a accesskey="c" href="#inline-edit" title="Cancel" class="button-secondary cancel alignleft">Cancel</a>
		  	<a accesskey="s" href="#inline-edit" title="Update" class="button-primary save alignright">Update</a>
            <input type="hidden" name="page" value="mla-menu" />
            <input type="hidden" name="screen" value="media_page_mla-menu" />
			<br class="clear" />
            <span class="error" style="display:none"></span>
          </p>
        </td>
      </tr>
      <tr id="bulk-edit" class="inline-edit-row inline-edit-row-attachment inline-edit-attachment bulk-edit-row bulk-edit-row-attachment bulk-edit-attachment" style="display: none">
        <td colspan="[+colspan+]" class="colspanchange">
          <fieldset class="inline-edit-col-left">
            <div class="inline-edit-col">
              <h4>Bulk Edit</h4>
              <div id="bulk-title-div">
                <div id="bulk-titles"></div>
              </div>
            </div>
          </fieldset>
[+bulk_middle_column+]
[+bulk_right_column+]
          <fieldset class="inline-edit-col-right">
            <div class="inline-edit-col">
              <label class="inline-edit-post-parent"> <span class="title">Parent ID</span> <span class="input-text-wrap">
                <input type="text" name="post_parent" value="" />
                </span> </label>
[+bulk_authors+]
[+bulk_custom_fields+]
            </div>
          </fieldset>
          <p class="submit inline-edit-save">
		  	<a accesskey="c" href="#inline-edit" title="Cancel" class="button-secondary cancel alignleft">Cancel</a>
            <input accesskey="s" type="submit" name="bulk_edit" id="bulk_edit" class="button-primary alignright" value="Update"  />
            <input accesskey="i" type="submit" name="bulk_map" id="bulk_map" class="button-secondary alignright" value="Map IPTC/EXIF metadata" style="margin-right: 1em"  />
            <input accesskey="m" type="submit" name="bulk_custom_field_map" id="bulk_custom_field_map" class="button-secondary alignright" value="Map Custom Field metadata" style="margin-right: 1em" />
            <input type="hidden" name="page" value="mla-menu" />
            <input type="hidden" name="screen" value="media_page_mla-menu" />
            <span class="error" style="display:none"></span> <br class="clear" />
          </p>
        </td>
      </tr>
    </tbody>
  </table>
</form>

