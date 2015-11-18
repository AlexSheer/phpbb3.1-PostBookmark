<?php
/**
*
* @package phpBB Extension - Post Bookmarks
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sheer\postbookmark\controller;

use Symfony\Component\HttpFoundation\Response;

class postbookmark
{	/** @var \phpbb	emplate	emplate */
	protected $template;

	/** @var \phpbb\db\driver\driver_interface $db */
	protected $db;

	/** @var \phpbb\user */
	protected $user;

	//** @var string phpbb_root_path */
	protected $phpbb_root_path;

	//** @var string php_ext */
	protected $php_ext;

	public function __construct(
		\phpbb\template\template $template,
		\phpbb\request\request_interface $request,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\user $user,
		$phpbb_root_path,
		$php_ext,
		$post_bookmark,
		$helper
	)
	{
		$this->template = $template;
		$this->request = $request;
		$this->db = $db;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->postbookmark_table = $post_bookmark;
		$this->helper = $helper;
	}

	public function main()
	{		$topic_id = $this->request->variable('t', 0);
		$post_id = $this->request->variable('p', 0);
		$forum_id = $this->request->variable('f', 0);
		$mode = $this->request->variable('mode', '');

		$book_submit = request_var('book', false);
		$book_cancel = request_var('reset', false);

		$viewtopic_url = append_sid("{$this->phpbb_root_path}viewtopic." . $this->php_ext . "", "f=$forum_id&amp;t=$topic_id");
		$body = 'add_bookmark';

		if($mode == 'delete')
		{			$sql = 'DELETE FROM ' . $this->postbookmark_table . "
				WHERE user_id = {$this->user->data['user_id']}
					AND post_id = $post_id";
			$this->db->sql_query($sql);
			$message = $this->user->lang['POST_BOOKMARK_REMOVED'] . '<br /><br />' . sprintf($this->user->lang['RETURN_TOPIC'], '<a href="' . $viewtopic_url . '">', '</a>');
			meta_refresh(3, $viewtopic_url);
			trigger_error($message);
		}
		else if($mode == 'find')
		{			$body = 'find_bookmark';
			$this->helper->get_bookmarks();
		}
		else
		{			$bookmark_desc = $this->request->variable('bookmark_desc', '', true);
			if($book_cancel)
			{
				redirect($viewtopic_url);
			}
			if ($book_submit)
			{				$sql = 'INSERT INTO ' . $this->postbookmark_table . ' ' . $this->db->sql_build_array('INSERT', array(
					'user_id'		=> $this->user->data['user_id'],
					'post_id'		=> $post_id,
					'topic_id'		=> $topic_id,
					'bookmark_time'	=> time(),
					'bookmark_desc'	=> $bookmark_desc,
				));
				$this->db->sql_query($sql);				$message = $this->user->lang['POST_BOOKMARK_ADDED'] . '<br /><br />' . sprintf($this->user->lang['RETURN_TOPIC'], '<a href="' . $viewtopic_url . '">', '</a>');
				meta_refresh(3, $viewtopic_url);
				trigger_error($message);
			}
		}

		$this->template->assign_vars(array(
			'U_POST_ACTION'			=> append_sid("{$this->phpbb_root_path}postbookmark", "f=$forum_id&amp;t=$topic_id&amp;p=$post_id&amp;mode=$mode"),
			)
		);

		page_header($this->user->lang['POST_BOOKMARK_ADD']);
		$this->template->set_filenames(array(
			'body' => $body . '.html'));

		page_footer();
		return new Response('', 200);
	}
}