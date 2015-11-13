<?php
/**
 * @package     Authentication.Modules
 * @subpackage  OAuth
 *
 * @copyright   Copyright (C) 2014-2015 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

class ModOauthLoginHelper
{
    /**
     * Retrieve the url where the user should be returned after logging in.
     *
     * @param   \Joomla\Registry\Registry  $params  module parameters
     * @param   string                     $type    return type
     *
     * @return string
     */
    public static function getReturnUrl($params, $type)
    {
        $app  = JFactory::getApplication();
        $item = $app->getMenu()->getItem($params->get($type));

        if ($item) {
            $url = 'index.php?Itemid=' . $item->id;
        } else {
            // Stay on the same page
            $url = JUri::getInstance()->toString();
        }

        return base64_encode($url);
    }

    /**
     * Returns the current users type
     *
     * @return string
     */
    public static function getType()
    {
        $user = JFactory::getUser();

        return (!$user->get('guest')) ? 'logout' : 'login';
    }

    public static function getPlugins()
    {
        return JPluginHelper::getPlugin('authentication');
    }
}
