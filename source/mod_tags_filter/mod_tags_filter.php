<?php
/**
 * @package     mod_tags_filter
 * @version     1.0.4
 * @copyright   Copyright (C) 2017 Rene Kreijveld Webdevelopment, Inc. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *              Parts of this code are based on the original work of the Joomla project.
 **/

defined('_JEXEC') or die;

// Include the tags_filter functions only once
JLoader::register('ModTagsFilterHelper', __DIR__ . '/helper.php');

$cacheparams = new stdClass;
$cacheparams->cachemode = 'safeuri';
$cacheparams->class = 'ModTagsFilterHelper';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = array('id' => 'array', 'Itemid' => 'int');

if ($params->get('show_active', 1) == 1)
{
	$jinput = JFactory::getApplication()->input;
	$active_tagid = $jinput->get('filter_tag', 0, 'INT');
}
else
{
	$active_tagid = -1;	
}

$list = JModuleHelper::moduleCache($module, $params, $cacheparams);
$all_text = $params->get('all_text');

if (!count($list) && !$params->get('no_results_text'))
{
	return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');

require JModuleHelper::getLayoutPath('mod_tags_filter', $params->get('layout', 'default'));