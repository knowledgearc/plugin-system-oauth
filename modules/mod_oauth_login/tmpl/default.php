<?php
/**
 * @copyright   Copyright (C) 2014-2015 KnowledgeArc Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
?>

<?php if (JFactory::getUser()->guest) : ?>
    <?php foreach ($plugins as $plugin) : ?>
<form action="<?php echo JRoute::_('index.php?task=oauth.authenticate&plugin='.$plugin->name.'&return='.$return); ?>" method="post">
    <?php echo JLayoutHelper::render('button', $plugin, JPATH_ROOT.'/plugins/authentication/'.$plugin->name.'/layout'); ?>
    <?php echo JHtml::_('form.token'); ?>
</form>
    <?php endforeach; ?>
<?php endif; ?>
