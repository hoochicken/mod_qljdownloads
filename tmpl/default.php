<?php
/**
 * @package		mod_qljdownloads
 * @copyright	Copyright (C) 2022 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
use Joomla\CMS\Factory;

defined('_JEXEC') or die;
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerStyle('qljdownloads', 'mod_qljdownloads/styles.css');
$wa->useStyle('qljdownloads');

/** @var array $files [stClass] */
/** @var stdClass $module  */
/** @var string $jdownloads_root  */
?>

<div class="qljdownloads" id="module<?php echo $module->id ?>">
    <?php foreach ($files as $file) : ?>
        <div class="file">
            <div class="title"><a href="<?php echo sprintf('%s/%s/%s', $jdownloads_root, $file->cat_dir, $file->url_download); ?>"><?php echo $file->title; ?></a></div>
        </div>
    <?php endforeach; ?>
    <pre>
        <?php print_r($files); ?>
    </pre>
</div>