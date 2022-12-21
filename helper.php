<?php
/**
 * @package        mod_qljdownloads
 * @copyright    Copyright (C) 2022 ql.de All rights reserved.
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

    const JDOWNLOADS_ROOT = 'ROOT';
    const JDOWNLOADS_FOLDERSEPARATOR = '/';

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
        if (0 < count($catId)) $query->where(sprintf('id IN(%s)', implode(',', $catId)));
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }

    public function getJdownloadsByCategory($catId)
    {
        $catId = $this->cleanseAsArrayWithIntegers($catId);
        $query = $this->db->getQuery(true);
        $query->select('f.id, f.title, f.created, f.url_download, f.catid AS cat_id, c.title AS cat_title, c.cat_dir, c.alias AS cat_alias');
        $query->from('#__jdownloads_files f');
        $query->where('f.published = 1');
        $query->order('f.created DESC');
        if (0 < count($catId)) $query->where(sprintf('f.catid IN(%s)', implode(',', $catId)));
        $query->leftJoin('#__jdownloads_categories c', 'c.id = f.catid');
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }

    public function getJdownloadsByFileId($fileId)
    {
        $fileId = $this->cleanseAsArrayWithIntegers($fileId);
        $query = $this->db->getQuery(true);
        $query->select('f.id, f.title, f.created, f.url_download, f.catid AS cat_id, c.title AS cat_title, c.cat_dir, c.alias AS cat_alias');
        $query->from('#__jdownloads_files f');
        $query->where('f.published = 1');
        $query->order('f.created DESC');
        if (0 < count($fileId)) $query->where(sprintf('f.id IN(%s)', implode(',', $fileId)));
        $query->leftJoin('#__jdownloads_categories c', 'c.id = f.catid');
        $this->db->setQuery($query);
        return $this->db->loadObjectList();
    }

    public function getJdownloadsById($ids)
    {
        $ids = $this->cleanseAsArrayWithIntegers($ids);
        if (0 >= count($ids)) {
            return [];
        }
        $query = $this->db->getQuery(true);
        $query->select('f.id, f.title, f.created, f.url_download, f.catid AS cat_id, c.title AS cat_title, c.cat_dir, c.alias AS cat_alias');
        $query->from('#__jdownloads_files f');
        $query->where('f.published = 1');
        $query->order('created DESC');
        $query->where(sprintf('id IN(%s)', implode(',', $ids)));
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

    public function enrichFile($file, $params, $jdownloads_root = '/jdownloads')
    {
        $this->category_path = [];
        $file->category_path = $this->getCategoryPath($file->cat_id);
        krsort($file->category_path);
        $category_path = $file->category_path;
        $href = $this->getJdHref($jdownloads_root, $category_path, $file);
        $file->category_path = json_encode($file->category_path);
        $file->href = $href;
        $file->label = $this->getLabel($params->get('label_scheme', '{title} ({id})'), $file);
        $file->cat_label = $this->getLabel($params->get('cat_label_scheme', '{cat_title} ({cat_id})'), $file);
        $file->link = $this->getHtmlLink($file->href, $file->label, $params->get('target', '_blank'));
        return $file;
    }

    private function getCategoryPath(int $catId): array
    {
        if (0 === $catId || 1 === $catId) return $this->category_path;
        $cat = $this->getJdownloadsCategories([$catId]);
        if (0 === count($cat)) return $this->category_path;
        $cat = array_pop($cat);
        if (0 === $cat->id || 1 === $cat->id || self::JDOWNLOADS_ROOT === $cat->title) return $this->category_path;
        $this->category_path[] = $cat;
        return $this->getCategoryPath($cat->parent_id);
    }

    public function getJdHref(string $jdownloads_root, array $category_path, $file): string
    {
        $path = [];
        foreach ($category_path as $cat) {
            $path[] = $cat->cat_dir;
        }
        $cat_path = implode(self::JDOWNLOADS_FOLDERSEPARATOR, $path);
        return sprintf('%s/%s/%s', $jdownloads_root, $cat_path, $file->url_download);
    }

    public function getHtmlLink($link, $label, $target = '_blank'): string
    {
        $targetAttr = !empty($target) ? sprintf('target="%s"', $target) : '';
        return sprintf('<a href="%s" %s>%s</a>', $link, $targetAttr, $label);
    }

    public function getLabel($labelScheme, $file): string
    {
        $placeholder = array_keys((array)$file);
        array_walk($placeholder, function (&$item) {
            $item = '{' . $item . '}';
        });
        $value = (array)$file;
        return str_replace($placeholder, $value, $labelScheme);
    }
}
