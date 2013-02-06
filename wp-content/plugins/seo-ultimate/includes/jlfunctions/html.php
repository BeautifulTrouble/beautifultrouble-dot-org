<?php
/*
JLFunctions HTML Class
Copyright (c)2010-2012 John Lamansky
*/

class suhtml {
	
	/**
	 * Returns <option> tags.
	 * 
	 * @param array $options An array of (value => label)
	 * @param string $current The value of the option that should be initially selected on the dropdown
	 * 
	 * @return string
	 */
	function option_tags($options, $current) {
		$html = '';
		foreach ($options as $value => $label) {
			if (is_array($label)) {
				$html .= "<optgroup label='$value'>\n".suhtml::option_tags($label, $current)."</optgroup>\n";
			} else {
				$html .= "\t<option value='$value'";
				if ((string)$value == (string)$current) $html .= " selected='selected'";
				$html .= ">$label</option>\n";
			}
		}
		return $html;
	}
}

?>