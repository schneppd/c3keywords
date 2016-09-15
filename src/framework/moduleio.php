<?php
/*
 * Provides all functionalities for the module's IO operations
 * 
 * @author Schnepp David <david.schnepp@schneppd.com>
 * @since 2016/09/13
 */

namespace NsC3Framework;

class ModuleIO {
	
	/*
	 * Checks if given directory exists
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param string $dirPath absolute path to directory
	 * @return boolean does the directory exists
	 */
	public static function existDirectory($dirPath) {
		return file_exists($dirPath);
	}
	
	/*
	 * Checks if given file exists
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param string $filePath absolute path to file
	 * @return boolean does the file exists
	 */
	public static function existFile($filePath) {
		return file_exists($filePath);
	}
	
	/*
	 * Create given directory and apply correct rights
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param string $dirPath absolute path to directory
	 * @return boolean if the directory creation succeeded
	 */
	public static function createDirectory($dirPath) {
		return mkdir($dirPath, 0755, false);
	}
	
	/*
	 * Empty given directory and then deletes it
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param string $dirPath absolute path to directory
	 */
	public static function emptyAndDeleteDirectory($dirPath) {
		if (static::existDirectory($dirPath)) {
			//empty the dir of its content
			$files = scandir($dirPath);
			foreach ($files as $file) {
				if (filetype($dirPath . "/" . $file) == "file") {
					$filePath = $dirPath . "/" . $file;
					static::deleteFile($filePath);
				}
			}
			//delete empty dir
			rmdir($dirPath);
		}

	}
	
	/*
	 * Delete given file
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param string $filePath absolute path to the file
	 */
	public static function deleteFile($filePath) {
		unlink($filePath);
	}
	
	/*
	 * Only delete the file if exists
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param string $filePath absolute path to the file
	 */
	public static function safeDeleteFile($filePath) {
		if(static::existFile($filePath)) {
			static::deleteFile($filePath);
		}
	}
	
	/*
	 * Read file content to memory
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param string $filePath absolute path to the file
	 * @param string the file content
	 */
	public static function getFileContentToString($filePath) {
		$res = file_get_contents($filePath);
		return $res;
	}
	
	/*
	 * Write given string to given file (override if exists)
	 * 
	 * @author Schnepp David
	 * @since 2016/09/13
	 * @param string $str the content to write to the file
	 * @param string $filePath absolute path to the file
	 */
	public static function writeStringToFile($str, $filePath) {
		$file = fopen($filePath, "w") or die("Unable to open cache!");
		fwrite($file, $str);
		fclose($file);
	}
}