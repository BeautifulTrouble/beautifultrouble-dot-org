<?php
/*
JLFunctions IO Class
Copyright (c)2009-2010 John Lamansky
*/

class suio {

	function is_file($filename, $path, $ext=false) {
		$is_ext = strlen($ext) ? sustr::endswith($filename, '.'.ltrim($ext, '*.')) : true;		
		return is_file(suio::tslash($path).$filename) && $is_ext;
	}
	
	function is_dir($name, $path) {
		return $name != '.' && $name != '..' && is_dir(suio::tslash($path).$name);
	}
	
	function tslash($path) {
		return suio::untslash($path).'/';
	}
	
	function untslash($path) {
		return rtrim($path, '/');
	}
	
	function import_csv($path) {
		if (!is_readable($path)) return false;
		
		$result = array();
		
		//Open the CSV file
		$handle = @fopen($path, 'r');
		if ($handle === false) return false;
		
		//Get the columns
		$headers = fgetcsv($handle, 99999, ',');
		if ($headers === false) {
			fclose($handle);
			return false;
		}
		
		//Get the rows
		while (($row = fgetcsv($handle, 99999, ',')) !== false) {
			
			if (count($row) > count($headers))
				//Too long
				$row = array_slice($row, 0, count($headers));
			elseif (count($row) < count($headers))
				//Too short
				$row = array_pad($row, count($headers), '');
			
			$new = array_combine($headers, $row);
			if ($new !== false) $result[] = $new;
		}
		
		//Close the CSV file
		fclose($handle);
		
		//Return
		return $result;
	}
	
	function export_csv($csv) {
		header("Content-Type: text/csv");
		$result = suio::print_csv($csv);
		if ($result) die(); else return false;
	}
	
	function print_csv($csv) {
		if (!is_array($csv) || !count($csv) || !is_array($csv[0])) return false;
		
		$headers = array_keys($csv[0]);
		array_unshift($csv, array_combine($headers, $headers));
		
		foreach ($csv as $row) {
			$csv_row = array();
			foreach ($headers as $header) {
				$csv_row[$header] = $row[$header];
				if (sustr::has($csv_row[$header], ',')) $csv_row[$header] = '"'.$csv_row[$header].'"';
			}
			
			echo implode(',', $csv_row)."\r\n";
		}
		
		return true;
	}
}

?>