<?php
/**
*
* @package phpBB Extension - Post Bookmarks
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace sheer\postbookmark\migrations;

class postbookmark_1_0_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['postbookmark_version']) && version_compare($this->config['postbookmark_version'], '1.0.1', '>=');
	}

	static public function depends_on()
	{
		return array('\sheer\postbookmark\migrations\postbookmark_1_0_0');
	}

	public function update_schema()
	{
		return array(
		);
	}

	public function revert_schema()
	{
		return array(
		);
	}

	public function update_data()
	{
		return array(
			// Update configs
			array('config.update', array('postbookmark_version', '1.0.1')),
		);
	}
}
