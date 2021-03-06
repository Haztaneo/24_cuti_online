<?php

/**
 * jQuery combobox Yii extension
 * 
 * Allows selecting a value from a dropdown list or entering in text.
 * Also works as an autocomplete for items in the select.
 *
 * @copyright © Digitick <www.digitick.net> 2011
 * @license GNU Lesser General Public License v3.0
 * @author Ianaré Sévi
 * @author Jacques Basseck
 *
 */
Yii::import('zii.widgets.jui.CJuiInputWidget');

/**
 * Base class.
 */
class EJuiComboBox extends CJuiInputWidget
{
	/**
	 * @var array the entries that the autocomplete should choose from.
	 */
	public $data = array();
	/**
	 * @var string A jQuery selector used to apply the widget to the element(s).
	 * Use this to have the elements keep their binding when the DOM is manipulated
	 * by Javascript, ie ajax calls or cloning.
	 * Can also be useful when there are several elements that share the same settings,
	 * to cut down on the amount of JS injected into the HTML.
	 */
	public $assoc=true;
	public $scriptSelector;
	public $defaultOptions = array('allowText' => true);

	protected function setSelector($id, $script, $event=null)
	{
		if ($this->scriptSelector) {
			if (!$event)
				$event = 'focusin';
			$js = "jQuery('body').delegate('{$this->scriptSelector}','{$event}',function(e){\$(this).{$script}});";
			$id = $this->scriptSelector;
		}
		else
			$js = "jQuery('#{$id}').{$script}";
		return array($id, $js);
	}

	public function init()
	{
		$cs = Yii::app()->getClientScript();
		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets');
		$cs->registerScriptFile($assets . '/jquery.ui.widget.min.js');
		$cs->registerScriptFile($assets . '/jquery.ui.combobox.js');

		parent::init();
	}

	/**
	 * Run this widget.
	 * This method registers necessary javascript and renders the needed HTML code.
	 */
	/*public function run()
	{
		list($name, $id) = $this->resolveNameID();

		if (is_array($this->data) && !empty($this->data)){
			$data = array_combine($this->data, $this->data);
			array_unshift($data, null);
		}
		else
			$data = array();

		echo CHtml::dropDownList(null, null, $data, array('id' => $id . '_select', 'style'=>'display:none;'));

		if ($this->hasModel())
			echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
		else
			echo CHtml::textField($name, $this->value, $this->htmlOptions);

		$this->options = array_merge($this->defaultOptions, $this->options);

		$options = CJavaScript::encode($this->options);

		$cs = Yii::app()->getClientScript();

		$js = "combobox({$options});";

		list($id, $js) = $this->setSelector($id, $js);
		$cs->registerScript(__CLASS__ . '#' . $id, $js);
	}*/
	
	public function run()
	{
		list($name, $id) = $this->resolveNameID();
		if (is_array($this->data) && !empty($this->data)){
			//if $data is not an assoc array make each value its key
			$data=($this->assoc)?$this->data:array_combine($this->data, $this->data);
			
			//does the same as array_unshift($data,null) but does not break assoc arrays
			$data=array(""=>"")+$data;
		}
		
		else
			$data = array();
		
		if ($this->hasModel())
			echo CHtml::activeDropDownList($this->model,$this->attribute,$data);
		else
			echo CHtml::dropDownList($name, $this->value, $data);
		
		echo CHtml::textField(null,($this->hasModel()?$data[CHtml::resolveValue($this->model,$this->attribute)]:$data[$this->value]),array('id'=>$id.'_combobox', 'placeholder' => 'Nama Associate', 'class'=>'span10',));
		$this->options = array_merge($this->defaultOptions, $this->options);
		$options = CJavaScript::encode($this->options);
		$cs = Yii::app()->getClientScript();
		$js = "combobox({$options});";
		list($id, $js) = $this->setSelector($id.'_combobox', $js);
		$cs->registerScript(__CLASS__ . '#' . $id, $js);
	}

}
