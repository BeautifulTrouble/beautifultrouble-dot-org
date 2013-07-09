<!-- template="taxonomy-table" -->
        <tr valign="top">
		<td colspan="2" style="text-align:left;">
          <table class="taxonomytable">
		  <thead>
		  <tr>
		    <th scope="col" style="text-align:center">
			Support
			</th>
		    <th scope="col" style="text-align:center">
			Inline Edit
			</th>
		    <th scope="col" style="text-align:center">
			List Filter
			</th>
		    <th scope="col" style="text-align:left">
			Taxonomy
			</th>
			</tr>
			</thead>
			<tbody>
[+taxonomy_rows+]
			</tbody>
          </table>
          <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
        </td></tr>
		
<!-- template="taxonomy-row" -->
        <tr valign="top">
		<td style="text-align:center;">
            <input type="checkbox" name="tax_support[[+key+]]" id="tax_support_[+key+]" [+support_checked+] value="checked" />
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="tax_quick_edit[[+key+]]" id="tax_quick_edit_[+key+]" [+quick_edit_checked+] value="checked" />
        </td>
		<td style="text-align:center;">
            <input type="radio" name="tax_filter" id="tax_filter_[+key+]" [+filter_checked+] value="[+key+]" />
        </td>
		<td>
            &nbsp;[+name+]
        </td>
		</tr>

<!-- template="custom-field-table" -->
        <tr valign="top">
		<td colspan="2" style="text-align:left;">
          <table class="custom-field-table">
		  <thead>
		  <tr style="text-align:center">
		    <th scope="col">
			Field Title
			</th>
		    <th scope="col">
			Data Source
			</th>
		    <th scope="col">
			Existing Text
			</th>
		    <th scope="col">
			Format
			</th>
		    <th scope="col">
			MLA Column
			</th>
		    <th scope="col">
			Quick Edit
			</th>
		    <th scope="col">
			Bulk Edit
			</th>
			</tr>
			</thead>
			<tbody>
[+table_rows+]
			</tbody>
          </table>
          <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
        </td></tr>

<!-- template="custom-field-select-option" -->
                <option [+selected+] value="[+value+]">[+text+]</option>

<!-- template="custom-field-empty-row" -->
        <tr>
		<td colspan="[+column_count+]" style="font-weight:bold; height: 4em; text-align:center; vertical-align:middle">
		No Custom Field Mapping Rules Defined
        </td>
		</tr>
<!-- template="custom-field-rule-row" -->
        <tr valign="top">
		<td style="text-align:left; vertical-align:middle">
            [+name+]&nbsp;
			<input type="hidden" name="custom_field_mapping[[+key+]][name]" id="custom_field_name_[+key+]" value="[+name+]" />
        </td>
		<td style="text-align:left;">
            <select name="custom_field_mapping[[+key+]][data_source]" id="custom_field_data_source_[+key+]">
[+data_source_options+]
            </select>
        </td>
		<td style="text-align:left;">
            <select name="custom_field_mapping[[+key+]][keep_existing]" id="custom_field_keep_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="custom_field_mapping[[+key+]][format]" id="custom_field_format_[+key+]">
                <option [+native_format+] value="native">Native</option>
                <option [+commas_format+] value="commas">Commas</option>
            </select>
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="custom_field_mapping[[+key+]][mla_column]" id="custom_field_mla_column_[+key+]" [+mla_column_checked+] value="checked" />
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="custom_field_mapping[[+key+]][quick_edit]" id="custom_field_quick_edit_[+key+]" [+quick_edit_checked+] value="checked" />
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="custom_field_mapping[[+key+]][bulk_edit]" id="custom_field_bulk_edit_[+key+]" [+bulk_edit_checked+] value="checked" />
        </td>
		</tr>
        <tr valign="top">
		<td>&nbsp;
			
        </td>
		<td style="text-align:left;">
            <input name="custom_field_mapping[[+key+]][meta_name]" id="custom_field_meta_name_[+key+]" type="text" size="[+meta_name_size+]" value="[+meta_name+]" />
        </td>
		<td colspan="[+column_count_meta+]" style="text-align:left; vertical-align:middle;">
			<strong>Option:</strong>&nbsp;
            <select name="custom_field_mapping[[+key+]][option]" id="custom_field_option_[+key+]">
                <option [+text_option+] value="text">Text</option>
                <option [+single_option+] value="single">Single</option>
                <option [+export_option+] value="export">Export</option>
                <option [+array_option+] value="array">Array</option>
                <option [+multi_option+] value="multi">Multi</option>
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="custom_field_mapping[[+key+]][no_null]" id="custom_field_no_null_[+key+]" [+no_null_checked+] value="1" /> <strong>Delete NULL values</strong>
        </td>
		</tr>
        <tr valign="top">
		<td colspan="[+column_count+]" style="padding-bottom: 10px">
	        <input type="submit" name="custom_field_mapping[[+key+]][action][delete_rule]" class="button-primary" style="height: 18px; line-height: 16px" value="Delete Rule" />
	        <input type="submit" name="custom_field_mapping[[+key+]][action][delete_field]" class="button-primary" style="height: 18px; line-height: 16px" value="Delete Rule AND Field" />
	        <input type="submit" name="custom_field_mapping[[+key+]][action][update_rule]" class="button-primary" style="height: 18px; line-height: 16px" value="Update Rule" />
	        <input type="submit" name="custom_field_mapping[[+key+]][action][map_now]" class="button-secondary" style="height: 18px; line-height: 16px" value="Map All Attachments" />
        </td>
		</tr>

<!-- template="custom-field-new-rule-row" -->
        <tr>
		<td colspan="[+column_count+]" style="font-weight:bold; height: 3em; vertical-align:bottom">
		Add a new Mapping Rule
        </td>
		</tr>
        <tr valign="top">
		<td style="text-align:left;">
            <select name="custom_field_mapping[[+key+]][name]" id="custom_field_name_[+key+]">
[+field_name_options+]
            </select>
        </td>
		<td style="text-align:left;">
            <select name="custom_field_mapping[[+key+]][data_source]" id="custom_field_data_source_[+key+]">
[+data_source_options+]
            </select>
        </td>
		<td style="text-align:left;">
            <select name="custom_field_mapping[[+key+]][keep_existing]" id="custom_field_keep_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="custom_field_mapping[[+key+]][format]" id="custom_field_format_[+key+]">
                <option [+native_format+] value="native">Native</option>
                <option [+commas_format+] value="commas">Commas</option>
            </select>
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="custom_field_mapping[[+key+]][mla_column]" id="custom_field_mla_column_[+key+]" [+mla_column_checked+] value="checked" />
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="custom_field_mapping[[+key+]][quick_edit]" id="custom_field_quick_edit_[+key+]" [+quick_edit_checked+] value="checked" />
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="custom_field_mapping[[+key+]][bulk_edit]" id="custom_field_bulk_edit_[+key+]" [+bulk_edit_checked+] value="checked" />
        </td>
		</tr>
        <tr valign="top">
		<td>&nbsp;
			
        </td>
		<td style="text-align:left;">
            <input name="custom_field_mapping[[+key+]][meta_name]" id="custom_field_meta_name_[+key+]" type="text" size="[+meta_name_size+]" value="[+meta_name+]" />
        </td>
		<td colspan="[+column_count_meta+]" style="text-align:left; vertical-align:middle;">
			<strong>Option:</strong>&nbsp;
            <select name="custom_field_mapping[[+key+]][option]" id="custom_field_option_[+key+]">
                <option [+text_option+] value="text">Text</option>
                <option [+single_option+] value="single">Single</option>
                <option [+export_option+] value="export">Export</option>
                <option [+array_option+] value="array">Array</option>
                <option [+multi_option+] value="multi">Multi</option>
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="custom_field_mapping[[+key+]][no_null]" id="custom_field_no_null_[+key+]" [+no_null_checked+] value="1" /> <strong>Delete NULL values</strong>
        </td>
		</tr>
        <tr valign="top">
		<td colspan="[+column_count+]">
	        <input type="submit" name="custom_field_mapping[[+key+]][action][add_rule]" type="submit" class="button-primary" value="Add Rule" />
	        <input type="submit" name="custom_field_mapping[[+key+]][action][add_rule_map]" type="submit" class="button-secondary" value="Add Rule and Map All Attachments" />
        </td>
		</tr>

<!-- template="custom-field-new-field-row" -->
        <tr>
		<td colspan="[+column_count+]" style="font-weight:bold; height: 3em; vertical-align:bottom">
		Add a new Custom Field and Mapping Rule
        </td>
		</tr>
        <tr valign="top">
		<td style="text-align:left;">
            <input name="custom_field_mapping[[+key+]][name]" id="custom_field_name_[+key+]" type="text" size="[+field_name_size+]" value="" />
        </td>
		<td style="text-align:left;">
            <select name="custom_field_mapping[[+key+]][data_source]" id="custom_field_data_source_[+key+]">
[+data_source_options+]
            </select>
        </td>
		<td style="text-align:left;">
            <select name="custom_field_mapping[[+key+]][keep_existing]" id="custom_field_keep_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="custom_field_mapping[[+key+]][format]" id="custom_field_format_[+key+]">
                <option [+native_format+] value="native">Native</option>
                <option [+commas_format+] value="commas">Commas</option>
            </select>
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="custom_field_mapping[[+key+]][mla_column]" id="custom_field_mla_column_[+key+]" [+mla_column_checked+] value="checked" />
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="custom_field_mapping[[+key+]][quick_edit]" id="custom_field_quick_edit_[+key+]" [+quick_edit_checked+] value="checked" />
        </td>
		<td style="text-align:center;">
            <input type="checkbox" name="custom_field_mapping[[+key+]][bulk_edit]" id="custom_field_bulk_edit_[+key+]" [+bulk_edit_checked+] value="checked" />
        </td>
		</tr>
        <tr valign="top">
		<td>&nbsp;
			
        </td>
		<td style="text-align:left;">
            <input name="custom_field_mapping[[+key+]][meta_name]" id="custom_field_meta_name_[+key+]" type="text" size="[+meta_name_size+]" value="[+meta_name+]" />
        </td>
		<td colspan="[+column_count_meta+]" style="text-align:left; vertical-align:middle;">
			<strong>Option:</strong>&nbsp;
            <select name="custom_field_mapping[[+key+]][option]" id="custom_field_option_[+key+]">
                <option [+text_option+] value="text">Text</option>
                <option [+single_option+] value="single">Single</option>
                <option [+export_option+] value="export">Export</option>
                <option [+array_option+] value="array">Array</option>
                <option [+multi_option+] value="multi">Multi</option>
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="checkbox" name="custom_field_mapping[[+key+]][no_null]" id="custom_field_no_null_[+key+]" [+no_null_checked+] value="1" /> <strong>Delete NULL values</strong>
        </td>
		</tr>
        <tr valign="top">
		<td colspan="[+column_count+]">
	        <input type="submit" name="custom_field_mapping[[+key+]][action][add_field]" type="submit" class="button-primary" value="Add Field" />
	        <input type="submit" name="custom_field_mapping[[+key+]][action][add_field_map]" type="submit" class="button-secondary" value="Add Field and Map All Attachments" />
        </td>
		</tr>

<!-- template="iptc-exif-standard-table" -->
        <tr valign="top">
		<td colspan="2" style="text-align:left;">
          <table class="iptc-exif-standard-table">
		  <thead>
		  <tr>
		    <th scope="col" style="text-align:center">
			Field Title
			</th>
		    <th scope="col" style="text-align:center">
			IPTC Value
			</th>
		    <th scope="col" style="text-align:center">
			EXIF Value
			</th>
		    <th scope="col" style="text-align:left">
			Priority
			</th>
		    <th scope="col" style="text-align:left">
			Existing Text
			</th>
			</tr>
			</thead>
			<tbody>
[+table_rows+]
			</tbody>
          </table>
          <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
        </td></tr>

<!-- template="iptc-exif-taxonomy-table" -->
        <tr valign="top">
		<td colspan="2" style="text-align:left;">
          <table class="iptc-exif-taxonomy-table">
		  <thead>
		  <tr>
		    <th scope="col" style="text-align:center">
			Field Title
			</th>
		    <th scope="col" style="text-align:center">
			IPTC Value
			</th>
		    <th scope="col" style="text-align:center">
			EXIF Value
			</th>
		    <th scope="col" style="text-align:center">
			Priority
			</th>
		    <th scope="col" style="text-align:center">
			Existing Text
			</th>
		    <th scope="col" style="text-align:center">
			Parent
			</th>
			</tr>
			</thead>
			<tbody>
[+table_rows+]
			</tbody>
          </table>
          <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
        </td></tr>

<!-- template="iptc-exif-custom-table" -->
        <tr valign="top">
		<td colspan="2" style="text-align:left;">
          <table class="iptc-exif-custom-table">
		  <thead>
		  <tr>
		    <th scope="col" style="text-align:center">
			Field Title
			</th>
		    <th scope="col" style="text-align:center">
			IPTC Value
			</th>
		    <th scope="col" style="text-align:center">
			EXIF Value
			</th>
		    <th scope="col" style="text-align:left">
			Priority
			</th>
		    <th scope="col" style="text-align:left">
			Existing Text
			</th>
			</tr>
			</thead>
			<tbody>
[+table_rows+]
			</tbody>
          </table>
          <div style="font-size:8pt;padding-bottom:10px;">[+help+]</div>
        </td></tr>

<!-- template="iptc-exif-select-option" -->
                <option [+selected+] value="[+value+]">[+text+]</option>

<!-- template="iptc-exif-select" -->
            <select name="iptc_exif_mapping[[+array+]][[+key+]][[+element+]]" id="iptc_exif_taxonomy_parent_[+key+]">
[+options+]
            </select>

<!-- template="iptc-exif-standard-row" -->
        <tr valign="top">
		<td>
            [+name+]&nbsp;
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[standard][[+key+]][iptc_value]" id="iptc_exif_standard_iptc_field_[+key+]">
[+iptc_field_options+]
            </select>
        </td>
		<td style="text-align:center;">
            <input name="iptc_exif_mapping[standard][[+key+]][exif_value]" id="iptc_exif_standard_exif_field_[+key+]" type="text" size="[+exif_size+]" value="[+exif_text+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[standard][[+key+]][iptc_first]" id="iptc_exif_standard_priority_[+key+]">
                <option [+iptc_selected+] value="1">IPTC</option>
                <option [+exif_selected+] value="">EXIF</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[standard][[+key+]][keep_existing]" id="iptc_exif_standard_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		</tr>

<!-- template="iptc-exif-taxonomy-row" -->
        <tr valign="top">
		<td>
            [+name+]&nbsp;
			<input type="hidden" id="iptc_exif_taxonomy_name_field_[+key+]" name="iptc_exif_mapping[taxonomy][[+key+]][name]" value="[+name+]" />
			<input type="hidden" id="iptc_exif_taxonomy_hierarchical_field_[+key+]" name="iptc_exif_mapping[taxonomy][[+key+]][hierarchical]" value="[+hierarchical+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[taxonomy][[+key+]][iptc_value]" id="iptc_exif_taxonomy_iptc_field_[+key+]">
[+iptc_field_options+]
            </select>
        </td>
		<td style="text-align:center;">
            <input name="iptc_exif_mapping[taxonomy][[+key+]][exif_value]" id="iptc_exif_taxonomy_exif_field_[+key+]" type="text" size="[+exif_size+]" value="[+exif_text+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[taxonomy][[+key+]][iptc_first]" id="iptc_exif_taxonomy_priority_[+key+]">
                <option [+iptc_selected+] value="1">IPTC</option>
                <option [+exif_selected+] value="">EXIF</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[taxonomy][[+key+]][keep_existing]" id="iptc_exif_taxonomy_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		<td style="text-align:left;">
[+parent_select+]
        </td>
		</tr>

<!-- template="iptc-exif-custom-empty-row" -->
        <tr>
		<td colspan="[+column_count+]" style="font-weight:bold; height: 4em; text-align:center; vertical-align:middle">
		No Custom Field Mapping Rules Defined
        </td>
		</tr>

<!-- template="iptc-exif-custom-rule-row" -->
        <tr valign="top">
		<td style="text-align:left; vertical-align:middle">
            [+name+]&nbsp;
			<input type="hidden" name="iptc_exif_mapping[custom][[+key+]][name]" id="iptc_exif_custom_name_[+key+]" value="[+name+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][iptc_value]" id="iptc_exif_custom_iptc_field_[+key+]">
[+iptc_field_options+]
            </select>
        </td>
		<td style="text-align:center;">
            <input name="iptc_exif_mapping[custom][[+key+]][exif_value]" id="iptc_exif_custom_exif_field_[+key+]" type="text" size="[+exif_size+]" value="[+exif_text+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][iptc_first]" id="iptc_exif_custom_priority_[+key+]">
                <option [+iptc_selected+] value="1">IPTC</option>
                <option [+exif_selected+] value="">EXIF</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][keep_existing]" id="iptc_exif_custom_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		</tr>
        <tr valign="top">
		<td colspan="[+column_count+]" style="padding-bottom: 10px">
	        <input type="submit" name="iptc_exif_mapping[custom][[+key+]][action][delete_rule]" class="button-primary" style="height: 18px; line-height: 16px" value="Delete Rule" />
	        <input type="submit" name="iptc_exif_mapping[custom][[+key+]][action][delete_field]" class="button-primary" style="height: 18px; line-height: 16px" value="Delete Rule AND Field" />
	        <input type="submit" name="iptc_exif_mapping[custom][[+key+]][action][update_rule]" class="button-primary" style="height: 18px; line-height: 16px" value="Update Rule" />
	        <input type="submit" name="iptc_exif_mapping[custom][[+key+]][action][map_now]" class="button-secondary" style="height: 18px; line-height: 16px" value="Map All Attachments" />
        </td>
		</tr>

<!-- template="iptc-exif-custom-new-rule-row" -->
        <tr>
		<td colspan="[+column_count+]" style="font-weight:bold; height: 3em; vertical-align:bottom">
		Add a new Mapping Rule
        </td>
		</tr>
        <tr valign="top">
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][name]" id="iptc_exif_custom_name_[+key+]">
[+field_name_options+]
            </select>
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][iptc_value]" id="iptc_exif_custom_iptc_field_[+key+]">
[+iptc_field_options+]
            </select>
        </td>
		<td style="text-align:center;">
            <input name="iptc_exif_mapping[custom][[+key+]][exif_value]" id="iptc_exif_custom_exif_field_[+key+]" type="text" size="[+exif_size+]" value="[+exif_text+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][iptc_first]" id="iptc_exif_custom_priority_[+key+]">
                <option [+iptc_selected+] value="1">IPTC</option>
                <option [+exif_selected+] value="">EXIF</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][keep_existing]" id="iptc_exif_custom_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		</tr>
        <tr valign="top">
		<td colspan="[+column_count+]">
	        <input type="submit" name="iptc_exif_mapping[custom][[+key+]][action][add_rule]" type="submit" class="button-primary" value="Add Rule" />
	        <input type="submit" name="iptc_exif_mapping[custom][[+key+]][action][add_rule_map]" type="submit" class="button-secondary" value="Add Rule and Map All Attachments" />
        </td>
		</tr>

<!-- template="iptc-exif-custom-new-field-row" -->
        <tr>
		<td colspan="[+column_count+]" style="font-weight:bold; height: 3em; vertical-align:bottom">
		Add a new Custom Field and Mapping Rule
        </td>
		</tr>
        <tr valign="top">
		<td style="text-align:left;">
            <input name="iptc_exif_mapping[custom][[+key+]][name]" id="iptc_exif_custom_name_[+key+]" type="text" size="[+field_name_size+]" value="" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][iptc_value]" id="iptc_exif_custom_iptc_field_[+key+]">
[+iptc_field_options+]
            </select>
        </td>
		<td style="text-align:center;">
            <input name="iptc_exif_mapping[custom][[+key+]][exif_value]" id="iptc_exif_custom_exif_field_[+key+]" type="text" size="[+exif_size+]" value="[+exif_text+]" />
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][iptc_first]" id="iptc_exif_custom_priority_[+key+]">
                <option [+iptc_selected+] value="1">IPTC</option>
                <option [+exif_selected+] value="">EXIF</option>
            </select>
        </td>
		<td style="text-align:left;">
            <select name="iptc_exif_mapping[custom][[+key+]][keep_existing]" id="iptc_exif_custom_existing_[+key+]">
                <option [+keep_selected+] value="1">Keep</option>
                <option [+replace_selected+] value="">Replace</option>
            </select>
        </td>
		</tr>
        <tr valign="top">
		<td colspan="[+column_count+]">
	        <input type="submit" name="iptc_exif_mapping[custom][[+key+]][action][add_field]" type="submit" class="button-primary" value="Add Field" />
	        <input type="submit" name="iptc_exif_mapping[custom][[+key+]][action][add_field_map]" type="submit" class="button-secondary" value="Add Field and Map All Attachments" />
        </td>
		</tr>

<!-- template="default-style" -->
<style type='text/css'>
	#[+selector+] {
		margin: auto;
		width: 100%;
	}
	#[+selector+] .gallery-item {
		float: [+float+];
		margin: [+margin+]%;
		text-align: center;
		width: [+itemwidth+]%;
	}
	#[+selector+] .gallery-item .gallery-icon img {
		border: 2px solid #cfcfcf;
	}
	#[+selector+] .gallery-caption {
		margin-left: 0;
		vertical-align: top;
	}
</style>
<!-- see mla_gallery_shortcode() in media-library-assistant/includes/class-mla-shortcodes.php -->

<!-- template="default-open-markup" -->
<div id='[+selector+]' class='gallery galleryid-[+id+] gallery-columns-[+columns+] gallery-size-[+size_class+]'>

<!-- template="default-row-open-markup" -->
<!-- row-open -->

<!-- template="default-item-markup" -->
<[+itemtag+] class='gallery-item'>
	<[+icontag+] class='gallery-icon'>
		[+link+]
	</[+icontag+]>
	<[+captiontag+] class='wp-caption-text gallery-caption'>
		[+caption+]
	</[+captiontag+]>
</[+itemtag+]>

<!-- template="default-row-close-markup" -->
<br style="clear: both" />

<!-- template="default-close-markup" -->
</div>
