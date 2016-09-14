<?php

namespace NsC3Framework;

class ModuleIO {
	
	public static function existDirectory($dirPath) {
		if (file_exists($dirPath))
			return true;
		return false;
	}
	public static function existFile($filePath) {
		if (file_exists($filePath))
			return true;
		return false;
	}
	
	public static function createDirectory($dirPath) {
		if (!mkdir($dirPath, 0755, false))
			return false;
		return true; //success
	}
	
	public static function emptyAndDeleteDirectory($dirPath) {
		if (static::existDirectory($dirPath)) {
			//empty dir of content
			$files = scandir($dirPath);
			foreach ($files as $file) {
				if (filetype($dirPath . "/" . $file) == "file") {
					$filePath = $dirPath . "/" . $file;
					static::deleteFile($filePath);
				}
			}
			//delete dir
			rmdir($dirPath);
		}

	}
	
	public static function deleteFile($filePath) {
		unlink($filePath);
	}
	
	public static function getFileContentToString($filePath) {
		$res = file_get_contents($filePath);
		return $res;
	}
	
}