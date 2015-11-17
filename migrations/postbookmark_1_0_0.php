<?php
/**
*
* @package phpBB Extension - Post Bookmarks
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace sheer\postbookmark\migrations;

class postbookmark_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return;
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'posts_bookmarks'	=> array(
					'COLUMNS'	=> array(
						'post_id'		=> array('UINT', '0'),
						'bookmark_time'	=> array('TIMESTAMP', '0'),
						'bookmark_desc'	=> array('VCHAR:64', ''),
						'topic_id'		=> array('UINT', '0'),
						'user_id'		=> array('UINT', '0'),
					),
					'PRIMARY_KEY'	=> array('post_id', 'user_id'),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'posts_bookmarks',
			),
		);
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.add', array('postbookmark_version', '1.0.0')),
			// UCP
			array('module.add', array('ucp', 'UCP_MAIN', 'UCP_POSTBOOKMARK')),
			array('module.add', array('ucp', 'UCP_POSTBOOKMARK', array(
				'module_basename'	=> '\sheer\postbookmark\ucp\postbookmark_module',
				'module_langname'	=> 'UCP_POSTBOOKMARK_MANAGE',
				'module_mode'		=> 'manage',
				'module_auth'		=> 'ext_sheer/postbookmark',
				'module_enabled'	=> true,
			))),
		);
	}
}