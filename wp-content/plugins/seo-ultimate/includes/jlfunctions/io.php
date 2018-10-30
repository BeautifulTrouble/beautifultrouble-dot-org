<?php
/*
JLFunctions IO Class
Copyright (c)2009-2012 John Lamansky
*/

class suio {
	
	/**
	 * Checks whether a given $filename in $path is actually a file, and optionally whether $filename ends in extension .$ext
	 * 
	 * @param string $filename The name of the purported file (e.g. "index.php")
	 * @param string $path The path in which this file is purportedly located
	 * @param string $ext The extension that the file should have (e.g. "php") (optional)
	 * 
	 * @return bool
	 */
	static function is_file($filename, $path, $ext=false) {
		$is_ext = strlen($ext) ? sustr::endswith($filename, '.'.ltrim($ext, '*.')) : true;		
		return is_file(suio::tslash($path).$filename) && $is_ext;
	}
	
	/**
	 * Checks whether a given path name points to a real directory.
	 * Ensures that the path name is not "." or ".."
	 * 
	 * @param string $name The name of the purported directory
	 * @param string $path The path in which this directory is purportedly located
	 * 
	 * @return bool
	 */
	static function is_dir($name, $path) {
		return $name != '.' && $name != '..' && is_dir(suio::tslash($path).$name);
	}
	
	/**
	 * Makes sure a directory path or URL ends in a trailing slash.
	 * 
	 * @param string $path
	 * 
	 * @return string
	 */
	static function tslash($path) {
		return suio::untslash($path).'/';
	}
	
	/**
	 * Removes any trailing slash at the end of a directory path or URL.
	 * 
	 * @param string $path
	 * 
	 * @return string
	 */
	static function untslash($path) {
		return rtrim($path, '/');
	}
	
	/**
	 * Converts the contents of a CSV file into an array.
	 * The first row of the CSV file must contain the column headers.
	 * 
	 * @param $path The location of the CSV file on the server.
	 * 
	 * @return array An array of arrays which represent rows of the file.
	 */
	static function import_csv($path) {
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
	
	/**
	 * Converts an array into a CSV file, outputs it with the appropriate HTTP header, and terminates program execution.
	 * 
	 * @param array $csv The array to convert and output
	 * 
	 * @return false Returns a value only if conversion failed
	 */
	static function export_csv($csv) {
		header("Content-Type: text/csv");
		$result = suio::print_csv($csv);
		if ($result) die(); else return false;
	}
	
	/**
	 * Converts an array into a CSV file and outputs it.
	 * 
	 * @param array $csv The array to convert and output
	 * 
	 * @return bool Whether or not the conversion was successful
	 */
	static function print_csv($csv) {
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