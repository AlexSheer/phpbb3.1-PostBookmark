<?php
/**
*
* @package phpBB Extension - Post Bookmarks
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sheer\postbookmark\ucp;

class postbookmark_info
{
	function module()
	{
		return array(
			'filename'	=> '\sheer\postbookmark\ucp\postbookmark_module',
			'title'		=> 'UCP_POSTBOOKMARK',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'manage'		=> array('title' => 'UCP_POSTBOOKMARK_MANAGE',
				'auth' => 'ext_sheer/delnotifications',
				'cat' => array('UCP_MAIN')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}
