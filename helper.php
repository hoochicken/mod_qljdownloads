<?php
/**
 * @package        mod_qljdownloads
 * @copyright    Copyright (C) 2015 ql.de All rights reserved.
 * @author        Mareike Riegel mareike.riegel@ql.de
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
use Joomla\Database\DatabaseDriver;
use Joomla\Input\Input;

defined('_JEXEC') or die;

class modQljdownloadsHelper
{
    public $params;
    public $module;
    /** @var DatabaseDriver */
    public $db;

    function __construct($module, $params, $db)
    {
        $this->module = $module;
        $this->params = $params;
        $this->db = $db;
    }

    public function getJdownloadsCategories($catId)
    {
        $catId = $this->cleanseAsArrayWithIntegers($catId);
        $query = $this->db->getQuery(true);
        $query->select('*');
        $query->from('#__jdownloads_categories');
        $query->where('published = 1');
        if (0 < count($catId)) $query->where(sprintf('catid IN(%s)', implode(',', $catId)));
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }

    public function getJdownloadsArticles($catId)
    {
        $catId = $this->cleanseAsArrayWithIntegers($catId);
        $query = $this->db->getQuery(true);
        $query->select('f.id, f.title, f.created, f.url_download, f.catid AS cat_id, c.title AS cat_title, c.cat_dir, c.alias AS cat_alias');
        $query->from('#__jdownloads_files f');
        $query->where('f.published = 1');
        $query->order('created DESC');
        if (0 < count($catId)) $query->where(sprintf('catid IN(%s)', implode(',', $catId)));
        $query->leftJoin('#__jdownloads_categories c', 'c.id = f.catid');
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }

    public function getCategoryIdByInput(Input $input, $param_name)
    {
        return $input->get($param_name);
    }

    private function cleanseAsArrayWithIntegers($value)
    {
        if (is_numeric($value)) $value = [$value];
        $value = array_filter($value, function($item) { if (!is_numeric($item)) return false; return true; });
        array_walk($value, function(&$item) {$item = (int) $item;});
        return $value;
    }
}
