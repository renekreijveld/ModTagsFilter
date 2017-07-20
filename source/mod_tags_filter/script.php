<?php
/**
 * @package     mod_tags_filter
 * @version     1.0.2
 * @copyright   Copyright (C) 2017 Rene Kreijveld Webdevelopment, Inc. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');

class mod_tags_filterInstallerScript
{
	public function preflight($type, $parent)
	{

		if ($type == 'uninstall')
		{
			return true;
		}

		$app = JFactory::getApplication();

		$jversion = new JVersion();
		if (!$jversion->isCompatible('3.7.0'))
		{
			$app->enqueueMessage(JText::_('MOD_TAGS_FILTER_PREFLIGHT_ERROR_TEXT'), 'error');
			return false;
		}

		return true;
	}
}