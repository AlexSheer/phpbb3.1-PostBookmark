<?php
/**
*
* @package phpBB Extension - Post Bookmarks
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'POST_BOOKMARK_REMOVED'		=> 'Post successfully removed from bookmarks.',
	'POST_BOOKMARK_ADDED'		=> 'Post has been successfully added to your bookmarks.',
	'POST_BOOKMARK_ERR'			=> 'Trying to put a message in the tab failed. Try again.',
	'BOOKMARK_EXPLAIN'			=> 'Here you can add a comment to bookmark. Maximum length - 40 characters.',
	'DELETED_POST_TOPIC'		=> 'Topic or post removed',
	'UCP_POSTBOOKMARK'			=> 'Post bookmarks',
	'UCP_POSTBOOKMARK_MANAGE'	=> 'Managing bookmarks',
	'ADDED'						=> 'Added',
	'COMMENT'					=> 'Remark',
	'POST_BOOKMARK_ADD'			=> 'Add bookmark',
	'VIEW_TOPIC_POST'			=> '1 message',
	'VIEW_TOPIC_POSTS'			=> 'Messages: %d',
	'BOOKMARKS'					=> 'Bookmarks',
	'TOTAL_BOOKMARKS'			=> 'Total: <strong>%d</strong>',
	'NO_POST_BOOKMARKS'			=> 'You have no bookmarks to posts.',
));
