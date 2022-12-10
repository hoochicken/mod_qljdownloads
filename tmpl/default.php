<?php
/**
 * @package		mod_qljdownloads
 * @copyright	Copyright (C) 2022 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerStyle('qljdownloads', 'mod_qlqljdownloads/styles.css');
$wa->useStyle('qljdownloads');
?>

<div class="qlqljdownloads" id="module<?php echo $module->id ?>">
</div>