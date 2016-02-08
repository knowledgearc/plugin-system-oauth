<?php
/**
 * @copyright   Copyright (C) 2014-2016 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$plugins = ModOauthLoginHelper::getPlugins();
$type = ModOauthLoginHelper::getType();
$return = ModOauthLoginHelper::getReturnUrl($params, $type);

require JModuleHelper::getLayoutPath('mod_oauth_login', $params->get('layout', 'default'));
