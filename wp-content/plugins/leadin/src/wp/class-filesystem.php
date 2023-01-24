<?php

namespace Leadin\wp;

/**
 * Static class containing wrapper functions to access the file system.
 */
class FileSystem {
	/**
	 * Transform the a path relative to the plugin directory into an absolute path.
	 *
	 * @param String $file_path Relative path starting from the leadin folder.
	 * @return String Absolute path to the given file.
	 */
	private static function get_absolute_path( $file_path ) {
		return plugin_dir_path( LEADIN_BASE_PATH ) . $file_path;
	}

	/**
	 * Return if the given file exists.
	 *
	 * @param String $file_path Relative path starting from the leadin folder.
	 * @return Boolean true if the given file exists.
	 */
	public static function file_exists( $file_path ) {
		return file_exists( self::get_absolute_path( $file_path ) );
	}
}
