<?php
/**
 * @package        mod_qljdownloads
 * @copyright    Copyright (C) 2022 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
use Joomla\Database\DatabaseDriver;

defined('_JEXEC') or die;
/** @var $module */
/** @var $params */
require_once dirname(__FILE__) . '/helper.php';
$helper = new modQljdownloadsHelper($module, $params, \Joomla\CMS\Factory::getContainer()->get(DatabaseDriver::class));

$fileId = (int)$params->get('file_id_by_list', 0);
$fileId = (0 < $fileId)
    ? $fileId
    : (int)$params->get('file_id_manual', 0);

$catedoryId = (int)$params->get('category_id', 0);
if ((bool) $params->get('get_category_id_by_input', false)) {
    $param_name = $params->get('param_name', '');
    $catedoryIdInput = $helper->getCategoryIdByInput(\Joomla\CMS\Factory::$application->getInput(), $param_name);
    if (!empty($catedoryIdInput)) $catedoryId = $catedoryIdInput;
}

$files = ('category' === $params->get('display', 'file'))
    ? $helper->getJdownloadsByCategory($catedoryId)
    : $helper->getJdownloadsByFileId($fileId);

array_walk($files, function(&$item) use ($params, $module) {
    $item = modQljdownloadsHelper::enrichFiles($item, $params, $module); }
);

$jdownloads_root = $params->get('jdownloads_root', '/jdownloads');

require JModuleHelper::getLayoutPath('mod_qljdownloads', $params->get('layout', 'default'));
