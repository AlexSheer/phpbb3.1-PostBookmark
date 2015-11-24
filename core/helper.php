<?php
/**
*
* @package phpBB Extension - Post Bookmarks
* @copyright (c) 2015 Sheer
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace sheer\postbookmark\core;

class helper
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\cache\driver\driver_interface */
	protected $cache;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\notification\manager */
	protected $notification_manager;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/** @var string phpEx */
	protected $php_ext;

	/** @var string table_prefix */
	protected $table_prefix;

	/** @var \phpbb\pagination */
	protected $pagination;

	/**
	* Constructor
	*/
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\template\template $template,
		\phpbb\user $user,
		\phpbb\request\request_interface $request,
		\phpbb\pagination $pagination,
		$phpbb_root_path,
		$php_ext,
		$table_prefix
	)
	{
		$this->config = $config;
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->pagination = $pagination;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->table_prefix = $table_prefix;
	}

	public function get_bookmarks()
	{
		define ('POSTS_BOOKMARKS_TABLE', $this->table_prefix.'posts_bookmarks');
		$start = $this->request->variable('start', 0);
		$sql = 'SELECT COUNT(post_id) as posts_count
			FROM ' . POSTS_BOOKMARKS_TABLE . '
			WHERE user_id = ' . $this->user->data['user_id'];

		$result = $this->db->sql_query($sql);
		$posts_count = (int) $this->db->sql_fetchfield('posts_count');
		$this->db->sql_freeresult($result);

		$pagination_url = append_sid("{$this->phpbb_root_path}postbookmark", "mode=find");
		$this->pagination->generate_template_pagination($pagination_url, 'pagination', 'start', $posts_count, $this->config['topics_per_page'], $start);

		$sql = 'SELECT b.post_id AS b_post_id, b.user_id, b.bookmark_time, b.bookmark_desc, p.post_id, p.forum_id, p.topic_id, p.poster_id, p.post_subject, p.post_time, u.user_id, u.username, u.user_colour
			FROM ' . POSTS_BOOKMARKS_TABLE . ' b
			LEFT JOIN ' . POSTS_TABLE . ' p ON( b.post_id = p.post_id)
			LEFT JOIN ' . USERS_TABLE . ' u ON (p.poster_id = u.user_id)
			WHERE b.user_id = ' . $this->user->data['user_id'] . '
			ORDER BY b.bookmark_time ASC';
		$result = $this->db->sql_query_limit($sql, $this->config['topics_per_page'], $start);

		while ($row = $this->db->sql_fetchrow($result))
		{
			// Send vars to template
			$this->template->assign_block_vars('postrow', array(
				'POST_ID'			=> $row['b_post_id'],
				'POST_TIME'			=> $this->user->format_date($row['post_time']),
				'BOOKMARK_TIME'		=> $this->user->format_date($row['bookmark_time']),
				'BOOKMARK_DESC'		=> $row['bookmark_desc'],
				'LAST_POST_SUBJECT'	=> $row['post_subject'],
				'TOPIC_AUTHOR'		=> get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
				'POST_TITLE'		=> $row['post_subject'],
				'U_VIEW_POST'		=> append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", "p=" . $row['post_id'] . "#p" . $row['post_id'] . ""),
				'S_DELETED_TOPIC'	=> (!$row['topic_id']) ? true : false,
				'S_DELETED_POST'	=> (!$row['post_id']) ? true : false,
				'U_POST_BOOKMARK'	=> '[url='. generate_board_url() .'/viewtopic.'. $this->php_ext .'?p=' . $row['post_id'] . '#p' . $row['post_id'] . ']' . $row['post_subject'] . '[/url]',
			));
		}
		$this->db->sql_freeresult($result);

		$this->template->assign_vars(array(
			'TOTAL_BOOKMARKS'	=> $this->user->lang('TOTAL_BOOKMARKS', (int) $posts_count),
			'PAGE_NUMBER'		=> $this->pagination->on_page($posts_count, $this->config['topics_per_page'], $start),
		));
	}

	/**
	 * Outputs correct response: standard/JSON
	 *
	 * @param string $message     Status message
	 * @param string $return_link Standard only
	 * @param string $url         Standard only
	 */
	public function output_response($message, $return_link, $url)
	{
		if ($this->request->is_ajax())
		{
			$json_response = new \phpbb\json_response();
			$json_response->send(array(
				'title'		=> $this->user->lang("INFORMATION"),
				'message'	=> $message,
			));
		}
		else
		{
			meta_refresh(3, $url);
			trigger_error($message . $return_link);
		}
	}
}
