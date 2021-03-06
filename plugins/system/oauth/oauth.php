<?php
/**
 * @package     JSpace.Plugin
 *
 * @copyright   Copyright (C) 2014-2016 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Listens for oAuth authorization tokens.
 *
 * @package  JSpace.Plugin
 */
class PlgSystemOauth extends JPlugin
{
    /**
     * Check for oAuth authentication/authorization requests and fire
     * appropriate oAuth events.
     *
     * @return  void
     */
    public function onAfterRoute()
    {
        if (JFactory::getApplication()->getName() == 'site') {
            $app = JFactory::getApplication();
            $session = JFactory::getSession();
            $uri = clone JUri::getInstance();

            $task = JArrayHelper::getValue($uri->getQuery(true), 'task');
            $task = $session->get('oauth.task', $task);

            $session->clear('oauth.task');

            $plugin = $session->get('oauth.plugin', $app->input->get('plugin'));

            $session->clear('oauth.plugin');

            if ($task) {
                JPluginHelper::importPlugin('authentication', $plugin);
                $dispatcher = JEventDispatcher::getInstance();

                if ($task == 'oauth.authenticate') {
                    $data = $app->getUserState('users.login.form.data', array());
                    $data['return'] = $app->input->get('return', null);
                    $app->setUserState('users.login.form.data', $data);

                    // update the current task and pass the plugin to the session.
                    $session->set('oauth.task', 'oauth.authorize');
                    $session->set('oauth.plugin', $plugin);

                    $dispatcher->trigger('onOauthAuthenticate', array());
                } else if ($task == 'oauth.authorize') {
                    $array = $dispatcher->trigger('onOauthAuthorize', array());

                    // redirect user to appropriate area of site.
                    if ($array[0] === true) {
                        $data = $app->getUserState('users.login.form.data', array());
                        $app->setUserState('users.login.form.data', array());

                        $user = JFactory::getUser();

                        if ($this->params->get('review_profile', false) &&
                            !(bool)$user->getParam("profile.reviewed", false)) {
                            $item = JFactory::getApplication()->getMenu('site')->getItems(
                                array('link'),
                                array('index.php?option=com_users&view=profile&layout=edit'),
                                true);

                            if ($item) {
                                $redirect = 'index.php?Itemid='.$item->id;
                            } else {
                                $redirect = 'index.php?option=com_users&view=profile&layout=edit';
                            }

                            if ($return) {
                                $redirect .= '&return='.$return;
                            }

                            $user->setParam('profile.reviewed', (int)true);
                            $user->save();
                        } else {
                            if ($return = JArrayHelper::getValue($data, 'return')) {
                                $redirect = base64_decode($return);
                            } else {
                                $redirect = JUri::current();
                            }
                        }

                        $app->redirect(JRoute::_($redirect, false));
                    } else {
                        $app->redirect(JRoute::_('index.php?option=com_users&view=login', false));
                    }
                }
            }
        }
    }
}
