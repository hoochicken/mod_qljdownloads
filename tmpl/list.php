<?php
/**
 * @package        PLG_CONTENT_QLJDOWNLOADS_
 * @copyright    Copyright (C) 2022 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerStyle('plg_content_qljdownloads', 'plg_content_qljdownloads/styles.css');
$wa->useStyle('plg_content_qljdownloads');

/** @var array $files [stClass] */
/** @var string $jdownloads_root */
/** @var JRegistry $params */
?>
<div class="qljdownloads">
    <ul>
        <?php foreach ($files as $file) : ?>
            <li>
                <?php echo $file->id; ?>
                <span class="title"><?php echo $file->link; ?>,
                <?php if ((bool)$params->get('cat_column_show', 1)): ?>
                    <?php echo $file->cat_label; ?>
                <?php endif; ?>,
                <?php echo $file->created; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
