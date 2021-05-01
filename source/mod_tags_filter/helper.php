<?php
/**
 * @package     mod_tags_filter
 * @version     1.0.4
 * @copyright   Copyright (C) 2017 Rene Kreijveld Webdevelopment, Inc. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *              Parts of this code are based on the original work of the Joomla project.
 **/

defined('_JEXEC') or die;

/**
 * Helper for mod_tags_filter
 *
 * @package     mod_tags_filter
 * @since       1.0
 */
abstract class ModTagsFilterHelper
{
	/**
	 * Get list of tags
	 *
	 * @param   \Joomla\Registry\Registry  &$params  module parameters
	 *
	 * @return  mixed
	 *
	 * @since   3.1
	 */
	public static function getList(&$params)
	{
		$db          = JFactory::getDbo();
		$user        = JFactory::getUser();
		$groups      = implode(',', $user->getAuthorisedViewLevels());
		$nowDate     = JFactory::getDate()->toSql();
		$nullDate    = $db->quote($db->getNullDate());

		$query = $db->getQuery(true)
			->select(
				array(
					$db->quoteName('tag_id') . ' AS tag_id',
					 't.title AS title',
				)
			)
			->group($db->quoteName(array('tag_id', 'title')))
			->from($db->quoteName('#__contentitem_tag_map', 'm'))
			->where($db->quoteName('t.access') . ' IN (' . $groups . ')');

		// Only return published tags
		$query->where($db->quoteName('t.published') . ' = 1 ');

		$query->join('INNER', $db->quoteName('#__tags', 't') . ' ON ' . $db->quoteName('tag_id') . ' = t.id')
		->join('INNER', $db->qn('#__ucm_content', 'c') . ' ON ' . $db->qn('m.core_content_id') . ' = ' . $db->qn('c.core_content_id'));

		$query->where($db->quoteName('m.type_alias') . ' = "' . 'com_content.article' . '"');

		// Only return tags connected to published articles
		$query->where($db->quoteName('c.core_state') . ' = 1')
			->where('(' . $db->quoteName('c.core_publish_up') . ' = ' . $nullDate
				. ' OR ' . $db->quoteName('c.core_publish_up') . ' <= ' . $db->quote($nowDate) . ')')
			->where('(' . $db->quoteName('c.core_publish_down') . ' = ' . $nullDate
				. ' OR  ' . $db->quoteName('c.core_publish_down') . ' >= ' . $db->quote($nowDate) . ')');

		$order_direction = $params->get('order_direction', 1) ? 'DESC' : 'ASC';

		$equery = $db->getQuery(true)
			->select(
				array(
					'a.tag_id',
					'a.title',
				)
			)
			->from('(' . (string) $query . ') AS a')
			->order('a.title' . ' ' . $order_direction);

		$query = $equery;

		$db->setQuery($query);

		try
		{
			$results = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			$results = array();
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		}

		return $results;
	}
}