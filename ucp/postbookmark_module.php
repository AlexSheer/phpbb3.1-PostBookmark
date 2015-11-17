<?php
/**
*
* @package phpBB Extension - Post Bookmarks
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sheer\postbookmark\ucp;

class postbookmark_module
{
	var $p_master;
	var $u_action;

	function __construct(&$p_master)
	{
		$this->p_master = &$p_master;
	}

	function main($id, $mode)
	{
		global $db, $user, $template, $auth, $phpbb_container;

		$forbidden_forums = $auth->acl_getf('!f_read', true);
		$forbidden_forums = array_unique(array_keys($forbidden_forums));

		$controller = $phpbb_container->get('sheer.postbookmark.helper');
		$controller->get_bookmarks($forbidden_forums);

		if (isset($_POST['unbookmark']))
		{
			$s_hidden_fields = array('unbookmark' => 1);
			$topics = (isset($_POST['t'])) ? array_keys(request_var('t', array(0 => 0))) : array();
			$url = $this->u_action;

			if (!sizeof($topics))
			{
				trigger_error('NO_BOOKMARKS_SELECTED');
			}

			foreach ($topics as $topic_id)
			{
				$s_hidden_fields['t'][$topic_id] = 1;
			}

			if (confirm_box(true))
			{
				$sql = 'DELETE FROM ' . POSTS_BOOKMARKS_TABLE . '
					WHERE user_id = ' . $user->data['user_id'] . '
						AND ' . $db->sql_in_set('post_id', $topics);
				$db->sql_query($sql);

				meta_refresh(3, $url);
				$message = $user->lang['BOOKMARKS_REMOVED'] . '<br /><br />' . sprintf($user->lang['RETURN_UCP'], '<a href="' . $url . '">', '</a>');
				trigger_error($message);
			}
			else
			{
				confirm_box(false, 'REMOVE_SELECTED_BOOKMARKS', build_hidden_fields($s_hidden_fields));
			}
		}

		$template->assign_vars(array(
			'S_HIDDEN_FIELDS'		=> (isset($s_hidden_fields)) ? $s_hidden_fields : '',
			'S_UCP_ACTION'			=> $this->u_action,
		));

		$this->tpl_name = 'ucp_postbookmark_body';
	}
}
