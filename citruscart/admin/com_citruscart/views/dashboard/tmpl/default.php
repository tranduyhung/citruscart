<?php
/*------------------------------------------------------------------------
# com_citruscart - citruscart
# ------------------------------------------------------------------------
# author    Citruscart Team - Citruscart http://www.citruscart.com
# copyright Copyright (C) 2014 - 2019 Citruscart.com All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://citruscart.com
# Technical Support:  Forum - http://citruscart.com/forum/index.html
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php
require_once(JPATH_ADMINISTRATOR.'/components/com_citruscart/library/select.php');
JHtml::_('script', 'media/citruscart/js/citruscart.js', false, false);
JHtml::_('stylesheet', 'media/citruscart/css/admin.css');
JHtml::_('script', 'libraries/dioscouri/highroller/highcharts/highcharts.js', false, false);
JHtml::_('script', 'libraries/dioscouri/highroller/highcharts/highcharts.src.js', false, false);
?>
<?php $state =$this->state; ?>
<?php $form = $this->form;
?>
<?php $items =$this->items;
echo $this->loadTemplate('submenu');
require_once JPATH_SITE . '/libraries/dioscouri/library/grid.php';
?>

<?php  DSC::loadHighcharts(); ?>

	<?php echo CitruscartGrid::pagetooltip( JFactory::getApplication()->input->getString('view') ); ?>
	<table style="width: 100%;">
	<tr>
		<td style="width: 70%; max-width: 70%; vertical-align: top; padding: 0px 5px 0px 5px;">

		    <form action="<?php echo JRoute::_($form['action'] )?>" method="post" name="adminForm" enctype="multipart/form-data">

		    <table class="table table-striped table-bordered" style="margin-bottom: 5px;">
			<thead class="dashboard_head">
			<tr>
				<th class="dashboard_citruscart"><span class="titles"><?php echo JText::_('COM_CITRUSCART_RANGE'); ?></span></th>
				<th class="dashboard_citruscart"><span class="titles"><?php echo JText::_('COM_CITRUSCART_REVENUE'); ?></span></th>
				<th class="dashboard_citruscart"><span class="titles"><?php echo JText::_('COM_CITRUSCART_ORDERS'); ?></span></th>
				<th class="dashboard_citruscart"><div class="titles"><?php echo JText::_('COM_CITRUSCART_TOTAL_ORDER_ITEMS')?></span></th>
			    
			</tr>
			</thead>
			<tbody>
			<tr>
				<?php $attribs = array('class' => 'inputbox', 'onchange' => 'document.adminForm.submit();'); ?>
				<?php
				//this is dumb, but it makes the dashboard work until caching issue is resolve
				 if($state->stats_interval) : ?>
				<td class="dashboard_profits_data float-shadow"><h3><?php echo CitruscartSelect::range($state->stats_interval, 'stats_interval', $attribs); ?></h3></td>
				<?php else :?>
				<td class="dashboard_profits_data float-shadow"><h3><?php echo CitruscartSelect::range($state->stats_interval, 'stats_interval', $attribs, null, true ); ?></h3></td>
				<?php endif ?>
				<td class="dashboard_profits_data float-shadow"><h1><?php echo CitruscartHelperBase::currency( $this->sum ); ?></h1></td>
				<td class="dashboard_profits_data float-shadow"><h1><?php echo CitruscartHelperBase::number( $this->total, array('num_decimals'=>'0') ); ?></h1></td>
			    <td class="dashboard_profits_data"><h1><?php echo CitruscartHelperBase::number($this->orderedItems, array('num_decimals'=>'0'));?></h1></td>
			    
			</tr>
			</tbody>
			</table>
            <div class="section">
                <?php

                require_once(JPATH_SITE.'/libraries/dioscouri/highroller/highroller/highroller.php');
                $chart = new HighRoller();
                $chart->chart->renderTo = 'chart';
                $chart->chart->type = 'mixed';

                $chart->plotOptions = new stdClass();
                $chart->plotOptions->column = new stdClass();
                $chart->plotOptions->column->pointStart = strtotime( $this->revenue[0][0] ) * 1000;
                $chart->plotOptions->column->pointInterval = $this->interval->pointinterval;
                $chart->plotOptions->line = new stdClass();
                $chart->plotOptions->line->pointStart = strtotime( $this->orders[0][0] ) * 1000;
                $chart->plotOptions->line->pointInterval = $this->interval->pointinterval;

                $chart->xAxis = new stdClass();
                $chart->xAxis->labels = new stdClass();
                $chart->xAxis->type = 'datetime';
                $chart->xAxis->tickInterval = $chart->plotOptions->line->pointInterval;
                $chart->xAxis->labels->rotation = -45;
                $chart->xAxis->labels->align = 'right';
                $chart->xAxis->labels->step = $this->interval->step;

                $left_y_axis = new stdClass();
                $left_y_axis->title = new stdClass();
                $left_y_axis->title->text = JText::_( 'COM_CITRUSCART_REVENUE' );
                $left_y_axis->min = 0;
                $left_y_axis->minRange = 8;
                $left_y_axis->allowDecimals = false;
                $left_y_axis->endOnTick = true;

                $right_y_axis = new stdClass();
                $right_y_axis->title = new stdClass();
                $right_y_axis->title->text = JText::_( 'COM_CITRUSCART_ORDERS' );
                $right_y_axis->min = 0;
                $right_y_axis->minRange = 8;
                $right_y_axis->allowDecimals = false;
                $right_y_axis->endOnTick = true;
                $right_y_axis->opposite = true;

                $chart->yAxis = array($left_y_axis, $right_y_axis);

                $chart->legend->borderWidth = '1';

                $series = new HighRollerSeriesData();
                $series->addName(JText::_( 'COM_CITRUSCART_REVENUE' ))->addData( $this->revenue );
                $series->type = 'column';
                $chart->addSeries($series);

                $series = new HighRollerSeriesData();
                $series->addName(JText::_( 'COM_CITRUSCART_ORDERS' ))->addData( $this->orders );
                $series->yAxis = 1;
                $series->type = 'line';
                $chart->addSeries($series);
                ?>

                <div id="chart" style="width: 100%;"></div>

                <script type="text/javascript">
                  <?php echo $chart->renderChart();?>
                </script>

            </div>
            <?php echo $this->form['validate']; ?>
            </form>

            <td style="vertical-align: top; width: 30%; min-width: 30%; padding: 0px 5px 0px 5px;">
			<?php
			require_once(JPATH_SITE.'/libraries/dioscouri/library/parameter.php');
			$attribs 	= array();
			$attribs['style'] = 'xhtml';
			$modules[] = JModuleHelper::getModule("mod_citruscart_salestatistics");
			$modules[] = JModuleHelper::getModule("mod_citruscart_recentorders");
			$modules[] = JModuleHelper::getModule("mod_citruscart_search_admin");
			$document	= JFactory::getDocument();
			$renderer	= $document->loadRenderer('module');
			$attribs 	= array();
			$attribs['style'] = 'xhtml';
			foreach ( $modules as $mod )
			{
				echo $renderer->render($mod, $attribs);
			}
			?>
		</td>
		</tr>

</table>
