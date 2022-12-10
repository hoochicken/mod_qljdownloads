<?php
/**
 * @package		mod_qlqljdownloads
 * @copyright	Copyright (C) 2015 ql.de All rights reserved.
 * @author 		Mareike Riegel mareike.riegel@ql.de
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class modQljdownloadsHelper
{
    public $params;
    public $module;

	function __construct($module,$params)
    {
        $this->module=$module;
        $this->params=$params;
    }

    public function someMethod()
    {

	}
}
