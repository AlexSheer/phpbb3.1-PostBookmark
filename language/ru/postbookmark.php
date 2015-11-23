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
	'POST_BOOKMARK_REMOVED'		=> 'Сообщение успешно удалено из закладок.',
	'POST_BOOKMARK_ADDED'		=> 'Сообщение успешно добавлено в закладки.',
	'POST_BOOKMARK_ERR'			=> 'Попытка поместить сообщение в закладки не удалась. Попробуйте ещё раз.',
	'BOOKMARK_EXPLAIN'			=> 'Здесь вы можете добавить комментарий к закладке. Максимальная длина - 40 символов.',
	'DELETED_POST_TOPIC'		=> 'Тема или сообщение удалены',
	'UCP_POSTBOOKMARK'			=> 'Закладки-сообщения',
	'UCP_POSTBOOKMARK_MANAGE'	=> 'Управление закладками',
	'ADDED'						=> 'Добавлено',
	'COMMENT'					=> 'Примечание',
	'POST_BOOKMARK_ADD'			=> 'Добавить закладку',
	'VIEW_TOPIC_POST'			=> '1 сообщение',
	'VIEW_TOPIC_POSTS'			=> 'Сообщений: %d',
	'BOOKMARKS'					=> 'Закладки',
	'TOTAL_BOOKMARKS'			=> 'Всего: <strong>%d</strong>',
	'NO_POST_BOOKMARKS'			=> 'У вас нет закладок на сообщения.',
));
