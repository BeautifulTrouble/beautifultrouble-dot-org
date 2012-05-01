<?php

class acf_Flexible_content extends acf_Field
{

	/*--------------------------------------------------------------------------------------
	*
	*	Constructor
	*	- $parent is passed buy reference so you can play with the acf functions
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function __construct($parent)
	{
    	parent::__construct($parent);
    	
    	$this->name = 'flexible_content';
		$this->title = __("Flexible Content",'acf');

   	}


	/*--------------------------------------------------------------------------------------
	*
	*	create_field
	*	- called in lots of places to create the html version of the field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_field($field)
	{
		$button_label = ( isset($field['button_label']) && $field['button_label'] != "" ) ? $field['button_label'] : __("+ Add Row",'acf');
		$layouts = array();
		foreach($field['layouts'] as $l)
		{
			$layouts[$l['name']] = $l;
		}
		
		?>
		<div class="acf_flexible_content">
			
			<div class="no_value_message" <?php if($field['value']){echo 'style="display:none;"';} ?>>
				<?php _e("Click the \"$button_label\" button below to start creating your layout",'acf'); ?>
			</div>
			
			<div class="clones">
			<?php $i = -1; ?>
			<?php foreach($layouts as $layout): $i++; ?>
			<table class="widefat" data-layout="<?php echo $layout['name'] ?>">
			<?php if($layout['display'] == 'table'): ?>
			<thead>
				<tr>
					<th class="order"><!-- order --></th>
					<?php foreach($layout['sub_fields'] as $sub_field_i => $sub_field):?>
					<th class="<?php echo $sub_field['name']; ?>" <?php if($sub_field_i != 0): ?>style="width:<?php echo 95/count($layout['sub_fields']); ?>%;"<?php endif; ?>><span><?php echo $sub_field['label']; ?></span></th>
					<?php endforeach; ?>
					<th class="remove"><!-- remove --></th>
				</tr>
			</thead>
			<?php endif; ?>
			<tbody>
				<tr>
					<td class="order"><?php echo $i+1; ?></td>
					<?php if($layout['display'] == 'row'): ?><td><?php endif; ?>
					<?php foreach($layout['sub_fields'] as $sub_field):?>
						<?php if($layout['display'] == 'table'): ?><td><?php else: ?><label class="field_label"><?php echo $sub_field['label']; ?></label><?php endif; ?>	
						<input type="hidden" name="<?php echo $field['name'] ?>[999][acf_fc_layout]" value="<?php echo $layout['name']; ?>" />
						<?php 
						// add value
						$sub_field['value'] = isset($sub_field['default_value']) ? $sub_field['default_value'] : '';
						
						// add name
						$sub_field['name'] = $field['name'] . '[999][' . $sub_field['key'] . ']';
						
						// create field
						$this->parent->create_field($sub_field);
						?>
						<?php if($layout['display'] == 'table'): ?></td><?php endif; ?>	
					<?php endforeach; ?>
					<?php if($layout['display'] == 'row'): ?></td><?php endif; ?>
					<td class="remove"><a class="remove_row" id="fc_remove_row" href="javascript:;"></a></td>
				</tr>
			</tbody>
			</table>
			<?php endforeach; ?>
			</div>
			<div class="values">
				<?php if($field['value']): ?>
					<?php foreach($field['value'] as $i => $value):?>
						
						
						<?php if(!isset($layouts[$value['acf_fc_layout']])) continue; ?>
						<?php $layout = $layouts[$value['acf_fc_layout']]; ?>

						
						<table class="widefat" data-layout="<?php echo $layout['name'] ?>">
						<?php if($layout['display'] == 'table'): ?>
						<thead>
							<tr>
								<th class="order"><!-- order --></th>
								<?php $l = 0; foreach($layout['sub_fields'] as $sub_field): $l++; ?>
								<th class="<?php echo $sub_field['name']; ?>" <?php if($l != count($layout['sub_fields'])): ?>style="width:<?php echo 100/count($layout['sub_fields']) - 5; ?>%;"<?php endif; ?>><span><?php echo $sub_field['label']; ?></span></th>
								<?php endforeach; ?>
								<th class="remove"><!-- remove --></th>
							</tr>
						</thead>
						<?php endif; ?>
						<tbody>
							<tr>
								<td class="order"><?php echo $i+1; ?></td>
								<?php if($layout['display'] == 'row'): ?><td><?php endif; ?>
								<?php foreach($layout['sub_fields'] as $sub_field):?>
									<?php if($layout['display'] == 'table'): ?><td><?php else: ?><label class="field_label"><?php echo $sub_field['label']; ?></label><?php endif; ?>	
									<input type="hidden" name="<?php echo $field['name'] ?>[<?php echo $i ?>][acf_fc_layout]" value="<?php echo $layout['name']; ?>" />
									<?php 
									// add value
									$sub_field['value'] = isset($value[$sub_field['name']]) ? $value[$sub_field['name']] : '';
									
									// add name
									$sub_field['name'] = $field['name'] . '[' . $i . '][' . $sub_field['key'] . ']';
									
									// create field
									$this->parent->create_field($sub_field);
									?>
									<?php if($layout['display'] == 'table'): ?></td><?php endif; ?>
								<?php endforeach; ?>
								<?php if($layout['display'] == 'row'): ?></td><?php endif; ?>
								<td class="remove"><a class="remove_row" id="fc_remove_row" href="javascript:;"></a></td>
							</tr>
						</tbody>
						</table>
						

					<?php endforeach; ?>
				<?php endif; ?>
				<?php // values here ?>
			</div>
			<div class="table_footer">
				<div class="order_message"></div>
				<div class="acf_popup">
					<ul>
						<?php foreach($field['layouts'] as $layout): $i++; ?>
						<li><a href="javascript:;" data-layout="<?php echo $layout['name']; ?>"><?php echo $layout['label']; ?></a></li>
						<?php endforeach; ?>
					</ul>
					<div class="bit"></div>
				</div>
				<ul class="hl clearfix">
					<li class="right">
						<a href="javascript:;" id="fc_add_row" class="add_row acf-button"><?php echo $button_label; ?></a>
					</li>
				</ul>
			</div>	

		</div>
		<?php
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	create_options
	*	- called from core/field_meta_box.php to create special options
	*
	*	@params : 	$key (int) - neccessary to group field data together for saving
	*				$field (array) - the field data from the database
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_options($key, $field)
	{
		// vars
		$fields_names = array();
		$field['layouts'] = isset($field['layouts']) ? $field['layouts'] : array();
		$field['button_label'] = (isset($field['button_label']) && $field['button_label'] != "") ? $field['button_label'] : __("+ Add Row",'acf');
		
		// load default layout
		if(empty($field['layouts']))
		{
			$field['layouts'][] = array(
				'name' => '',
				'label' => '',
				'display' => 'table',
				'sub_fields' => array(),
			);
		}
		
		// get name of all fields for use in field type
		foreach($this->parent->fields as $f)
		{
			$fields_names[$f->name] = $f->title;
		}
		//unset($fields_names['repeater']);
		unset($fields_names['flexible_content']);
		
		// loop through layouts and create the options for them
		if($field['layouts']):
		foreach($field['layouts'] as $layout_key => $layout):
		
			// add clone field
			$layout['sub_fields'][999] = array(
					'label'		=>	__("New Field",'acf'),
					'name'		=>	'new_field',
					'type'		=>	'text',
					'order_no'	=>	'1',
					'instructions'	=>	'',
			);
		?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Layout",'acf'); ?></label>
		<p class="desription">
			<span><a class="acf_fc_reorder" title="<?php _e("Reorder Layout",'acf'); ?>" href="javascript:;"><?php _e("Reorder",'acf'); ?></a> | </span>
			<span><a class="acf_fc_add" title="<?php _e("Add New Layout",'acf'); ?>" href="javascript:;"><?php _e("Add New",'acf'); ?></a> | </span>
			<span><a class="acf_fc_delete" title="<?php _e("Delete Layout",'acf'); ?>" href="javascript:;"><?php _e("Delete",'acf'); ?></a>
		</p>
	</td>
	<td>
	<div class="repeater">
		
		<table class="acf_cf_meta">
			<body>
				<tr>
					<td class="acf_fc_label" style="padding-left:0;">
						<label><?php _e('Label','acf'); ?></label>
						<?php 
						$this->parent->create_field(array(
							'type'	=>	'text',
							'name'	=>	'fields['.$key.'][layouts][' . $layout_key . '][label]',
							'value'	=>	$layout['label'],
						));
						?>
					</td>
					<td class="acf_fc_name">
						<label><?php _e('Name','acf'); ?></label>
						<?php 
						$this->parent->create_field(array(
							'type'	=>	'text',
							'name'	=>	'fields['.$key.'][layouts][' . $layout_key . '][name]',
							'value'	=>	$layout['name'],
						));
						?>
					</td>
					<td style="padding-right:0;">
						<label><?php _e('Display','acf'); ?></label>
						<?php 
						$this->parent->create_field(array(
							'type'	=>	'select',
							'name'	=>	'fields['.$key.'][layouts][' . $layout_key . '][display]',
							'value'	=>	$layout['display'],
							'choices'	=>	array(
								'table' => __("Table",'acf'), 
								'row' => __("Row",'acf')
							)
						));
						?>
					</td>
				</tr>
			</body>
		</table>
					
		<div class="fields_header">
			<table class="acf widefat">
				<thead>
					<tr>
						<th class="field_order"><?php _e('Field Order','acf'); ?></th>
						<th class="field_label"><?php _e('Field Label','acf'); ?></th>
						<th class="field_name"><?php _e('Field Name','acf'); ?></th>
						<th class="field_type"><?php _e('Field Type','acf'); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<div class="fields">

			<div class="no_fields_message" <?php if(count($layout['sub_fields']) > 1){ echo 'style="display:none;"'; } ?>>
				<?php _e("No fields. Click the \"+ Add Sub Field button\" to create your first field.",'acf'); ?>
			</div>
	
			<?php foreach($layout['sub_fields'] as $key2 => $sub_field): ?>
				<div class="<?php if($key2 == 999){echo "field_clone";}else{echo "field";} ?> sub_field">
					
					<?php if(isset($sub_field['key'])): ?>
						<input type="hidden" name="fields[<?php echo $key; ?>][sub_fields][<?php echo $key2; ?>][key]" value="<?php echo $sub_field['key']; ?>" />
					<?php endif; ?>
					
					<div class="field_meta">
					<table class="acf widefat">
						<tr>
							<td class="field_order"><span class="circle"><?php echo ($key2+1); ?></span></td>
							<td class="field_label">
								<strong>
									<a class="acf_edit_field" title="<?php _e("Edit this Field",'acf'); ?>" href="javascript:;"><?php echo $sub_field['label']; ?></a>
								</strong>
								<div class="row_options">
									<span><a class="acf_edit_field" title="<?php _e("Edit this Field",'acf'); ?>" href="javascript:;"><?php _e("Edit",'acf'); ?></a> | </span>
									<span><a class="acf_delete_field" title="<?php _e("Delete this Field",'acf'); ?>" href="javascript:;"><?php _e("Delete",'acf'); ?></a>
								</div>
							</td>
							<td class="field_name"><?php echo $sub_field['name']; ?></td>
							<td class="field_type"><?php echo $sub_field['type']; ?></td>
						</tr>
					</table>
					</div>
					
					<div class="field_form_mask">
					<div class="field_form">
						<table class="acf_input widefat">
							<tbody>
								<tr class="field_label">
									<td class="label">
										<label><span class="required">*</span><?php _e("Field Label",'acf'); ?></label>
										<p class="description"><?php _e("This is the name which will appear on the EDIT page",'acf'); ?></p>
									</td>
									<td>
										<?php 
										$this->parent->create_field(array(
											'type'	=>	'text',
											'name'	=>	'fields['.$key.'][layouts][' . $layout_key . '][sub_fields]['.$key2.'][label]',
											'value'	=>	$sub_field['label'],
											'class'	=>	'label',
										));
										?>
									</td>
								</tr>
								<tr class="field_name">
									<td class="label">
										<label><span class="required">*</span><?php _e("Field Name",'acf'); ?></label>
										<p class="description"><?php _e("Single word, no spaces. Underscores and dashes allowed",'acf'); ?></p>
									</td>
									<td>
										<?php 
										$this->parent->create_field(array(
											'type'	=>	'text',
											'name'	=>	'fields['.$key.'][layouts][' . $layout_key . '][sub_fields]['.$key2.'][name]',
											'value'	=>	$sub_field['name'],
											'class'	=>	'name',
										));
										?>
									</td>
								</tr>
								<tr class="field_type">
									<td class="label"><label><span class="required">*</span><?php _e("Field Type",'acf'); ?></label></td>
									<td>
										<?php 
										$this->parent->create_field(array(
											'type'	=>	'select',
											'name'	=>	'fields['.$key.'][layouts][' . $layout_key . '][sub_fields]['.$key2.'][type]',
											'value'	=>	$sub_field['type'],
											'class'	=>	'type',
											'choices'	=>	$fields_names
										));
										?>
									</td>
								</tr>
								<?php 
								
								$this->parent->fields[$sub_field['type']]->create_options($key.'][layouts][' . $layout_key . '][sub_fields]['.$key2, $sub_field);
								
								?>
								<tr class="field_save">
									<td class="label">
										<!-- <label><?php _e("Save Field",'acf'); ?></label> -->
									</td>
									<td>
										<ul class="hl clearfix">
											<li>
												<a class="acf_edit_field acf-button grey" title="<?php _e("Close Field",'acf'); ?>" href="javascript:;"><?php _e("Close Sub Field",'acf'); ?></a>
											</li>
										</ul>
									</td>
								</tr>								
							</tbody>
						</table>
					</div><!-- End Form -->
					</div><!-- End Form Mask -->
				
				</div>
			<?php endforeach; ?>
		</div>
		<div class="table_footer">
			<div class="order_message"></div>
			<a href="javascript:;" id="add_field" class="acf-button"><?php _e('+ Add Sub Field','acf'); ?></a>
		</div>
	</div>
	</td>
</tr><?php endforeach; endif; ?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Button Label",'acf'); ?></label>
	</td>
	<td>
		<?php 
		$this->parent->create_field(array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][button_label]',
			'value'	=>	$field['button_label'],
		));
		?>
	</td>
</tr><?php
  	}
	
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	admin_print_scripts / admin_print_styles
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_print_scripts()
	{
		wp_enqueue_script(array(

			'jquery-ui-sortable',
			
		));
	}
	
	function admin_print_styles()
	{
  
	}


	/*--------------------------------------------------------------------------------------
	*
	*	update_value
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function update_value($post_id, $field, $value)
	{
		$sub_fields = array();
		
		foreach($field['layouts'] as $layout)
		{
			foreach($layout['sub_fields'] as $sub_field)
			{
				$sub_fields[$sub_field['key']] = $sub_field;
			}
		}

		$total = array();
		
		if($value)
		{
			// remove dummy field
			unset($value[999]);
			
			$i = -1;
			
			// loop through rows
			foreach($value as $row)
			{	
				$i++;
				
				// increase total
				$total[] = $row['acf_fc_layout'];
				unset($row['acf_fc_layout']);
					
				// loop through sub fields
				foreach($row as $field_key => $value)
				{
					$sub_field = $sub_fields[$field_key];

					// update full name
					$sub_field['name'] = $field['name'] . '_' . $i . '_' . $sub_field['name'];
					
					// save sub field value
					$this->parent->update_value($post_id, $sub_field, $value);
				}
			}
		}
		
		parent::update_value($post_id, $field, $total);
		
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	pre_save_field
	*	- called just before saving the field to the database.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function pre_save_field($field)
	{

		// format sub_fields
		if($field['layouts'])
		{

			$layouts = array();
			
			// loop through and save fields
			foreach($field['layouts'] as $l)
			{				
				// remove dummy field
				unset($l['sub_fields'][999]);
				
				// loop through and save fields
				$i = -1;
				
				$sub_fields = array();
				
				if($l['sub_fields'])
				{
				foreach($l['sub_fields'] as $f)
				{
					$i++;
					
					// each field has a unique id!
					if(!isset($f['key'])) $f['key'] = 'field_' . uniqid();
	
					// order
					$f['order_no'] = $i;
					
					// format
					$f = $this->parent->pre_save_field($f);
					
					$sub_fields[] = $f;
				}
				}
				$l['sub_fields'] = $sub_fields;
				
				$layouts[] = $l;
				
			}
			
			$field['layouts'] = $layouts;
			
		}
		
		// return updated repeater field
		return $field;

	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value
	*	- called from the input edit page to get the value.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value($post_id, $field)
	{
		$layouts = array();
		foreach($field['layouts'] as $l)
		{
			$layouts[$l['name']] = $l;
		}

		// vars
		$values = array();
		$layout_order = false;
		
		
		// get total rows
		if( is_numeric($post_id) )
		{
			$layout_order = get_post_meta($post_id, $field['name'], true);
		}
		else
		{
			$layout_order = get_option( $post_id . '_' . $field['name'] );
		}
		

		if($layout_order)
		{
			$i = -1;
			// loop through rows
			foreach($layout_order as $layout)
			{
				$i++;
				$values[$i]['acf_fc_layout'] = $layout;
				
				// check if layout still exists
				if(isset($layouts[$layout]))
				{
					// loop through sub fields
					foreach($layouts[$layout]['sub_fields'] as $sub_field)
					{
						// store name
						$field_name = $sub_field['name'];
						
						// update full name
						$sub_field['name'] = $field['name'] . '_' . $i . '_' . $field_name;
						
						$values[$i][$field_name] = $this->parent->get_value($post_id, $sub_field);
					}
				}
			}
		}
		
		return $values;	
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value_for_api
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value_for_api($post_id, $field)
	{
		$layouts = array();
		foreach($field['layouts'] as $l)
		{
			$layouts[$l['name']] = $l;
		}

		// vars
		$values = array();
		$layout_order = false;
		
		
		// get total rows
		if( is_numeric($post_id) )
		{
			$layout_order = get_post_meta($post_id, $field['name'], true);
		}
		else
		{
			$layout_order = get_option( $post_id . '_' . $field['name'] );
		}
		

		if($layout_order)
		{
			$i = -1;
			// loop through rows
			foreach($layout_order as $layout)
			{
				$i++;
				$values[$i]['acf_fc_layout'] = $layout;
				
				// loop through sub fields
				foreach($layouts[$layout]['sub_fields'] as $sub_field)
				{
					// store name
					$field_name = $sub_field['name'];
					
					// update full name
					$sub_field['name'] = $field['name'] . '_' . $i . '_' . $field_name;
					
					$values[$i][$field_name] = $this->parent->get_value_for_api($post_id, $sub_field);
				}
			}
			
			return $values;
		}
		
		return array();	
	}
	
}

?>