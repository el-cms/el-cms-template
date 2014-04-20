<?php
/**
 * Form view (used for add and edit actions)
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Views
 * @version       0.3
 *
 * ----
 *  This file is part of EL-CMS.
 *
 *  EL-CMS is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  EL-CMS is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *
 *  You should have received a copy of the GNU General Public License
 *  along with EL-CMS. If not, see <http://www.gnu.org/licenses/>
 */
//Page headers and licensing
include $themePath . 'views/common/headers.ctp';

/* ----------------------------------------------------------------------------
 * Current template options
 */
// Additionnal CSS
if (!isset($additionnalCSS) || !is_array($additionnalCSS)) {
	$additionnalCSS = array();
}

// Additionnal JS
if (!isset($additionnalJS) || !is_array($additionnalJS)) {
	$additionnalJS = array();
}

// Form style:
if (!isset($formClass)) {
	$formClass = $this->Sbc->getConfig('theme.formClass');
	if (!empty($formClass)) {
		$formClass = ", 'class'=>'$formClass'";
	}
}

// Hidden fields
if (!isset($hiddenFields) || !is_array($hiddenFields)) {
	$hiddenFields = array();
}

// File field
if (!isset($fileField)) {
	$fileField = null;
}

// No toolbar option
if (!isset($noToolbar)) {
	$noToolbar = false;
}

/* ----------------------------------------------------------------------------
 *
 * View
 *
 *---------------------------------------------------------------------------*/

/* ----------------------------------------------------------------------------
 * Toolbar
 */
if ($noToolbar === false):
	include dirname(__FILE__) . DS . 'common' . DS . 'toolbar_buttons.ctp';
endif;

// Date time picker JS
echo "<?php \techo \$this->Html->script('bootstrap-datetimepicker'); ?>\n";
?>

<div class="<?php echo $pluralVar; ?> form">
	<?php
	$hasFileField = (is_null($fileField) == false) ? ", 'enctype'=>'multipart/form-data'" : '';
	echo "<?php echo \$this->Form->create('$modelClass', array('role'=>'form'$hasFileField$formClass)); ?>\n";
//	echo "<?php echo \$this->Html->script('bootstrap-datetimepicker.min', array('inline' => false)); ? >\n";
//	echo "<?php echo \$this->Html->script('locales/bootstrap-datetimepicker.fr', array('inline' => false)); ? >\n";
//	echo "<?php echo \$this->Html->css('datetimepicker')? >\n";
	?>
	<fieldset>
		<?php
		foreach ($fields as $field):
			//Skipping primary key
			if ((strpos($action, 'add') !== false && $field == $primaryKey) || in_array($field, $hiddenFields)):
				continue;
			else:
				$displayField = true;
				$displayLabel = true;
			//
			// Field type
			//
			switch ($schema[$field]['type']):
					case 'datetime':
						if (in_array($field, array('updated', 'created', 'modified'))): // && strpos($action, 'add') == true) {
							$displayField = false;
						else:
							$fieldHTML = $this->v_dateField($field);
//							$additionnalCSS['bootstrap-datetimepicker'] = true;
//							$additionnalJS['bootstrap-datetimepicker'] = true;
						endif;
						break;
					case 'text':
						$fieldHTML = "\t\t<?php echo \$this->Form->input('$field', array('div'=>false, 'label'=>false, 'class'=>'ckeditor form-control', 'placeholder'=>'$field')); ?>\n";
						$additionnalCSS['..::js::ckeditor::contents'] = true;
						$additionnalJS['ckeditor::ckeditor'] = true;
						break;
					default:
						//
						// Field name
						//
					switch ($field):
							case 'password':
								$fieldValue = null;
								$fieldRequired = "true";
								if (strpos($action, 'edit') == true):
									$fieldValue = "'value'=>null, ";
									$fieldRequired = "false";
								endif;
								$fieldHTML = "\t\t<?php echo \$this->Form->input('$field', array('div'=>false, 'label'=>false, $fieldValue'required'=>$fieldRequired, 'class'=>'form-control', 'type'=>'password', 'placeholder'=>'$field')); ?>\n";
								$fieldHTML .= "\t\t<?php echo \$this->Form->input('{$field}2', array('div'=>false, 'label'=>false, 'class'=>'form-control', 'type'=>'password', 'placeholder'=>'Re-type your password')); ?>\n";
								break;
							case $primaryKey:
								$displayLabel = false;
								$fieldHTML = "\t\t<?php echo \$this->Form->input('$field', array('div'=>false, 'label'=>false, 'class'=>'form-control', 'placeholder'=>'$field')); ?>\n";
								break;
							default:
								// Set type to file if fileField
								$isfileField = (!is_null($fileField) && $field == $fileField['name']) ? "'type'=>'file', " : "";
								// No classes for file input
								$inputClass = (!is_null($fileField) && $field == $fileField['name']) ? "" : "'class'=>'form-control', ";
								$fieldHTML = "\t\t<?php echo \$this->Form->input('$field', array($isfileField'div'=>false, 'label'=>false, $inputClass'placeholder'=>'$field')); ?>\n";
								break;
					endswitch;
					break;
				endswitch;
				if ($displayField == true):
					// Opens row
					if ($displayLabel):
						echo $this->v_formOpenGroup($field, $this->iString(ucfirst(strtolower(Inflector::humanize($field)))));
					endif;
					// Field
					echo $fieldHTML;
					// Close row
					if ($displayLabel):
						echo $this->v_formCloseGroup();
					endif;
				endif;
			endif;
		endforeach;
		if (!empty($associations['hasAndBelongsToMany'])):
			echo '<h2>Associated data:</h2>';
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData):
				echo $this->v_formOpenGroup($assocName, $this->iString(ucfirst(strtolower(Inflector::humanize($assocName)))));
				echo "\t\techo \$this->Form->input('{$assocName}');\n";
				echo $this->v_formCloseGroup();
			endforeach;
		endif;
		// Submit button
		echo $this->v_formOpenGroup();
		echo "\t\t<?php echo \$this->Form->submit(__('Save'), array('class'=>'btn btn-primary'));?>\n";
		echo $this->v_formCloseGroup();
		?>
	</fieldset>
	<?php
	echo "<?php echo \$this->Form->end(); ?>\n";
	?>
</div>

<?php

/* -----------------------------------------------------------------------------
 * Additionnal scripts and CSS
 */
include $themePath.'views/common/additionnal_js_css.ctp';