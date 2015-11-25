<?php
/**
 *
 * @package		phpBB Extension - Post Bookmarks
 * @copyright	(c) 2015 Sheer
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace sheer\postbookmark\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 * @static
	 * @access public
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'								=> 'load_language_on_setup',
			'core.viewtopic_assign_template_vars_before'	=> 'get_topic_data',
			'core.viewtopic_modify_post_row'				=> 'modify_post_row',
		);
	}

	/** @var \phpbb\template\template */
	protected $template;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/** @var string php_ext */
	protected $php_ext;

	/** @var \phpbb\db\driver\driver_interface $db */
	protected $db;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/** @var string bookmarks_table */
	protected $postbookmark_table;

	/**
	 * Constructor
	 */
	public function __construct(
		$phpbb_root_path,
		$php_ext,
		\phpbb\template\template $template,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\user $user,
		\phpbb\config\config $config,
		$phpbb_extension_manager,
		$post_bookmark
	)
	{
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->template = $template;
		$this->db = $db;
		$this->user = $user;
		$this->config = $config;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->postbookmark_table = $post_bookmark;
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'sheer/postbookmark',
			'lang_set' => 'postbookmark',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function get_topic_data($event)
	{
		if ($this->user->data['is_registered'] && $this->config['allow_bookmarks'])
		{
			$topic_data = $event['topic_data'];
			$posts_bookmark = array();
			$sql = 'SELECT post_id
				FROM ' . $this->postbookmark_table . '
				WHERE user_id = ' . $this->user->data['user_id'] . '
					AND topic_id = ' . $topic_data['topic_id'];
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$posts_bookmark[] = $row['post_id'];
			}
			$this->db->sql_freeresult($result);
			if (!empty($posts_bookmark))
			{
				$topic_data = array_merge($event['topic_data'], array('posts_bookmarks' => $posts_bookmark));
				$event['topic_data'] = $topic_data;
			}
			$this->template->assign_vars(array(
				'S_QUICK_REPLY_EXT'	=> $this->phpbb_extension_manager->is_enabled('boardtools/quickreply') || $this->phpbb_extension_manager->is_enabled('tatiana5/quickreply'),
			));
		}
	}

	public function modify_post_row($event)
	{
		if ($this->user->data['is_registered'] && $this->config['allow_bookmarks'])
		{
			$row = $event['row'];
			$post_row = $event['post_row'];
			$topic_data = $event['topic_data'];
			$posts_bookmark = (isset($topic_data['posts_bookmarks'])) ? $topic_data['posts_bookmarks'] : array();
			$post_id = $row['post_id'];
			$topic_id = $topic_data['topic_id'];
			$forum_id = $topic_data['forum_id'];

			$mode = (in_array($row['post_id'], $posts_bookmark)) ? 'delete' : 'insert';
			$post_row['L_BOOKMARK_POST'] = (in_array($row['post_id'], $posts_bookmark)) ? $this->user->lang['BOOKMARK_TOPIC_REMOVE'] : $this->user->lang['BOOKMARK_TOPIC'];
			$post_row['BOOKMARK_DELETE'] = (in_array($row['post_id'], $posts_bookmark)) ? '-delete' : '';
			$post_row['U_BOOKMARK_POST'] = append_sid("{$this->phpbb_root_path}postbookmark", "f=$forum_id&amp;t=$topic_id&amp;p=$post_id&amp;mode=$mode");
			$post_row['U_BOOKMARK_LINK'] = append_sid("{$this->phpbb_root_path}postbookmark", "f=$forum_id&amp;t=$topic_id&amp;p=$post_id");

			$event['post_row'] = $post_row;
		}
	}
}
