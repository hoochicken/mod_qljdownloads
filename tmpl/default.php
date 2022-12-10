<?php
/**
 * @package        mod_qljdownloads
 * @copyright    Copyright (C) 2022 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->registerStyle('qljdownloads', 'mod_qljdownloads/styles.css');
$wa->useStyle('qljdownloads');

/** @var array $files [stClass] */
/** @var stdClass $module */
/** @var string $jdownloads_root */
?>

<div class="qljdownloads" id="module<?php echo $module->id ?>">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>#</th>
            <th><?php echo Text::_('MOD_QLJDOWNLOADS_TITLE'); ?></th>
            <th><?php echo Text::_('MOD_QLJDOWNLOADS_CREATED'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($files as $file) : ?>
            <tr>
                <td>
                    <?php echo $file->id; ?>
                </td>
                <td><span class="title"><a
                                href="<?php echo sprintf('%s/%s/%s', $jdownloads_root, $file->cat_dir, $file->url_download); ?>"
                                target="_blank"><?php echo $file->title; ?></a></span></td>
                <td><?php echo $file->created; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>