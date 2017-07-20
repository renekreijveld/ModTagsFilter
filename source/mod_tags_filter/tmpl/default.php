<?php
/**
 * @package     mod_tags_filter
 * @version     1.0.1
 * @copyright   Copyright (C) 2017 Rene Kreijveld Webdevelopment, Inc. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE.txt
 *              Parts of this code are based on the original work of the Joomla project.
 **/

defined('_JEXEC') or die;
?>

<?php
if (!count($list))
{
	echo JText::_('MOD_TAGS_FILTER_NO_ITEMS_FOUND');
}
else
{
?>
<form method="POST" action="" id="tagfilterform">
	<ul class="nav nav-pills nav-stacked">
		<?php foreach ($list as $tag)
		{
			echo "<li><a onclick=\"filterTag($tag->tag_id)\">$tag->title</a></li>";
		}
		?>
		<li><a onclick="clearFilter()"><?php echo JText::_('MOD_TAGS_FILTER_RESET_FILTER'); ?></a></li>
	</ul>
	<input id="filter_tag" name="filter_tag" value="" type="hidden">	
</form>
<script type="text/javascript">
function filterTag(id) {
	document.getElementById('filter_tag').value = id;
	document.getElementById("tagfilterform").submit(); 
}
function clearFilter() {
	document.getElementById('filter_tag').value = "";
	document.getElementById("tagfilterform").submit(); 
}
</script>
<?php
}