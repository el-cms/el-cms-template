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
 */
//Page headers and licensing
include $themePath . 'views/common/headers.ctp';

// Preparing schema srtucture and fields to replace the ones from Cake
$this->s_prepareSchemaFields();
// Updating schema array
$schema = $this->templateVars['schema'];
// Updating fields array
$fields = $this->templateVars['fields'];

$haveFileField = $this->s_haveFileField($schema);

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

//
// No toolbar option
if (!isset($noToolbar)) {
	$noToolbar = false;
}

/* ----------------------------------------------------------------------------
 *
 * View
 *
 * --------------------------------------------------------------------------- */

/* ----------------------------------------------------------------------------
 * Toolbar
 */
if ($noToolbar === false):
	include dirname(__FILE__) . DS . 'common' . DS . 'toolbar_buttons.ctp';
endif;

// Date time picker JS
echo "<?php \techo \$this->Html->script('bootstrap-datetimepicker'); ?>\n";
$additionnalCSS['..::js::ckeditor::contents'] = true;
$additionnalJS['ckeditor::ckeditor'] = true;
?>

<div class="<?php echo $pluralVar; ?> form">
	<?php
	echo "<?php echo \$this->Form->create('$modelClass', array('role'=>'form'" . (($haveFileField) ? ", 'enctype'=>'multipart/form-data'" : '') . "$formClass)); ?>\n";
	?>
	<fieldset>
		<?php
		foreach ($fields as $field):

			// Remove PK if on an 'add' action
			if ((strpos($action, 'add') !== false && $field == $primaryKey) || in_array($field, $hiddenFields)):
				continue;
			else:
				$fieldContent = $this->v_prepareInputField($field, $schema[$field]);
				if($this->v_isFieldForeignKey($field, $associations)){
					$fieldContent['displayString']=$this->v_eFormInput($field);
				}
				echo "${fieldContent['displayString']}\n\n";
			endif;

		endforeach;
		// @todo Convert this with new methods
		if (!empty($associations['hasAndBelongsToMany'])):
			echo '<h2>Associated data:</h2>';
			foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData):
				echo "\t\techo \$this->Form->input('{$assocName}');\n";
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
include $themePath . 'views/common/additionnal_js_css.ctp';
