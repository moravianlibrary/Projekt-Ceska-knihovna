<?php $this->beginContent('//layouts/main'); ?>
<div class="container">
	<div class="span-19">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<div class="span-5 last">
		<div id="sidebar">
		<?php
			$menu = array_merge($this->menu->items(), $this->menu->items($this->id.'_print'));
			if ($menu != array())
			{
				$this->beginWidget('zii.widgets.CPortlet', array(
					'title'=>Yii::t('app', 'Menu'),
				));
				$this->widget('zii.widgets.CMenu', array(
					'items'=>$menu,
					'htmlOptions'=>array('class'=>'operations'),
				));
				$this->endWidget();
			}
			/*
			if ($this->menu->items($this->id.'_print') != array())
			{
				$this->beginWidget('zii.widgets.CPortlet', array(
					'title'=>Yii::t('app', 'Print'),
				));
				$this->widget('zii.widgets.CMenu', array(
					'items'=>$this->menu->items($this->id.'_print'),
					'htmlOptions'=>array('class'=>'operations'),
				));
				$this->endWidget();
			}
			*/
			if ($this->menu->items('_setting') != array())
			{
				$this->beginWidget('zii.widgets.CPortlet', array(
					'title'=>Yii::t('app', 'Settings'),
				));
				$this->widget('zii.widgets.CMenu', array(
					'items'=>$this->menu->items('_setting'),
					'htmlOptions'=>array('class'=>'operations'),
				));
				$this->endWidget();
			}
		?>
		</div><!-- sidebar -->
	</div>
</div>
<?php $this->endContent(); ?>