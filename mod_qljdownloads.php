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
$obj_helper = new modQljdownloadsHelper($module, $params, \Joomla\CMS\Factory::getContainer()->get(DatabaseDriver::class));

// $categories = $obj_helper->getJdownloadsCategories();
$catedoryId = (int)$params->get('category_id', 0);
$files = $obj_helper->getJdownloadsArticles($catedoryId);
$jdownloads_root = $params->get('jdownloads_root', '/jdownloads');

require JModuleHelper::getLayoutPath('mod_qljdownloads', $params->get('layout', 'default'));
