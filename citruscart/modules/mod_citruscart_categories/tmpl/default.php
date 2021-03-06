<?php
/*------------------------------------------------------------------------
# com_citruscart
# ------------------------------------------------------------------------
# author   Citruscart Team  - Citruscart http://www.citruscart.com
# copyright Copyright (C) 2014 Citruscart.com All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://citruscart.com
# Technical Support:  Forum - http://citruscart.com/forum/index.html
-------------------------------------------------------------------------*/
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

Citruscart::load( 'CitruscartSelect', 'library.select' );

$document = JFactory::getDocument();
$document->addStyleSheet( JURI::root(true).'/modules/mod_citruscart_categories/tmpl/citruscart_categories.css'); ?>

<!-- product category div starts -->
<div id="citruscart_category">
	<h5 class="categories_head"><?php echo JText::_('MOD_CITRUSCART_CATEGORY') ?></h5>
	<ul id="citruscart_categories_mod" class="unstyled nav nav-tab">
	<?php foreach ($items as $item) : ?>
		<?php if (($item->level)<$depthlevel) :?>
		<li class="product_categories level<?php echo $item->level?>">
			<a href="<?php echo JRoute::_( "index.php?option=com_citruscart&view=products&filter_category=".$item->category_id.$item->slug."&Itemid=".$item->itemid ); ?>"><?php echo $item->category_name; ?></a>
		</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
</div><!-- product category div ends -->
