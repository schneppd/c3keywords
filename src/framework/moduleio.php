<?php

namespace NsC3Framework;

class ModuleIO {
	
	public static function existDirectory($dirPath) {
		return file_exists($dirPath);
	}
	public static function existFile($filePath) {
		return file_exists($filePath);
	}
	
	public static function createDirectory($dirPath) {
		return mkdir($dirPath, 0755, false);
	}
	
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
	
	public static function deleteFile($filePath) {
		unlink($filePath);
	}
	
	public static function safeDeleteFile($filePath) {
		if(static::existFile($filePath)) {
			static::deleteFile($filePath);
		}
	}
	
	public static function getFileContentToString($filePath) {
		$res = file_get_contents($filePath);
		return $res;
	}
	
	public static function writeStringToFile($str, $filePath) {
		$file = fopen($filePath, "w") or die("Unable to open cache!");
		fwrite($file, $str);
		fclose($file);
	}
}