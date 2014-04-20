<?php
/**
 * Index view for a gallery list.
 * All fields listed below are optionnal, but I recommend to set at least a title to your gallery...
 * Basically, a gallery:
 *  - have a title                   titleField
 *  - have a content/excerpt         contentField
 *  - was created by an user         authorField
 *  - may be safe or not for work    sfwField
 *  - can be anonymous.              anonField
 *  - can be public (+w)             publicField
 *
 * Other options (* is for default):
 *  - keepExtraFields         true|*false       set to true to keep extra fields
 *  - hideActionList          *true|false       set to false to show an action list
 *  - noToolbar               true|*false       set to true to hide the toolbar
 *  - languageFields          array             define fields that have internationalized content
 *  - listIsCompact           true|*false       set it to true if you want a compact list (no description)
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

// Hide actions list for entries
if (!isset($hideActionsList)) {
	$hideActionsList = false;
}

// No toolbar option
if (!isset($noToolbar)) {
	$noToolbar = false;
}

// Internationalized fields (for field display. If empty, selects all fields)
$languageFields = (!isset($languageFields)) ? array() : $languageFields;

/* ----------------------------------------------------------------------------
 * Fields
 */
// By default, the view is complete
$listIsCompact = false;

// SFW field. Can be empty/null.
if (!isset($sfwField)) {
	$sfwField = null;
	$sfwContent = null;
} else {
	$sfwContent = "<?php echo (\${$singularVar}['{$modelClass}']['{$sfwField}']==1)"
					. "?'<i class=\"fa fa-check text-success\" title=\"' . " . $this->iString('This content is safe for young people or work browsing') . " . '\" data-toggle=\"tooltip\"></i> '\n"
					. ":'<i class=\"fa fa-times text-warning\" title=\"' . " . $this->iString('This content may not be viewable in most circumstancies') . " . '\" data-toggle=\"tooltip\"></i> '; ?>\n";
}

// Title field. If empty, will use primary key
if (!isset($titleField)) {
	$titleField = null;
	$titleContent = null;
} else {
	$titleContent = "<?php echo \${$singularVar}['{$modelClass}']['{$titleField}']; ?>\n";
}

// Content field. If empty, the article list will be compact.
if (!isset($contentField)) {
	$contentField = null;
	$contentContent = null;
	// If no content, view will be compact
	$listIsCompact = true;
} else {
	// support for sfw states
	if ($sfwField) {
		$contentContent = "<?php echo (\${$singularVar}['{$modelClass}']['{$sfwField}']==1)?\${$singularVar}['{$modelClass}']['{$contentField}']:" . $this->iString('The content may not be safe for work and will not be displayed.') . "; ?>\n";
		// Style for content
		$contentStyle = " <?php echo (\${$singularVar}['{$modelClass}']['{$sfwField}']==0)?'text-muted':'';?>";
	} else {// No SFW field
		$contentContent = "<?php echo \${$singularVar}['{$modelClass}']['{$contentField}']; ?>\n";
		// Style for content:
		$contentStyle='';
	}
}

// Author field. Can be empty/null.
if (!isset($authorField)) {
	$authorField = null;
	$authorContent = null;
} else {
	$authorContent = (($listIsCompact) ? ' // ' : '') . "<?php echo \${$singularVar}['{$modelClass}']['{$authorField}']; ?>\n";
}

// Anon field. Can be empty/null.
if (!isset($anonField)) {
	$anonField = null;
}

// Public field. Can be empty
if (!isset($publicField)){
	$publicField= null;
}

/* ----------------------------------------------------------------------------
 * Prepare actions for each item
 */
if ($this->canDo('view') == true || $this->canDo('edit') == true || $this->canDo('delete') == true) {
	$haveActions = true;
} else {
	$haveActions = false;
}

// Actions button:
$actionsButton = null;
$actionsList = null;
// View
if ($this->canDo('view')) {
	$actionsList.="<?php echo \$this->Html->link(" . $this->iString('View') . ", array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn btn-primary btn-xs')); ?>\n";
}
// Edit
if ($this->canDo('edit')) {
	$actionsList.="<?php echo \$this->Html->link(" . $this->iString('Edit') . ", array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn btn-primary btn-xs')); ?>\n";
}
// Delete
if ($this->canDo('delete')) {
	$actionsList.="<?php echo \$this->Form->postLink(" . $this->iString('Delete') . ", array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn btn-danger btn-xs'), __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
}
if (!empty($actionsList)) {
	$actionsButton = "<span class=\"pull-right\"><div class=\"btn-group\">\n $actionsList</div></span>";
}

/* ----------------------------------------------------------------------------
 * Prepare fields to display
 */
if (count($languageFields) > 0) {
	$diff = array();
	$internationalizedFields = array();
	foreach ($languageFields as $lf) {
		foreach ($this->Sbc->getConfig('theme.language.available') as $l) {
			$diff[] = $lf . '_' . $l;
		}
		$fields[] = $lf;
	}
	$fields = array_diff($fields, $diff);
}

/* ----------------------------------------------------------------------------
 *
 * View
 *
 * --------------------------------------------------------------------------- */

/* ----------------------------------------------------------------------------
 * Toolbar include
 */
// Toolbar : Hidden controllers are handled in the toolbar template file
if ($noToolbar === false) {
	include dirname(__FILE__) . DS . 'common' . DS . 'toolbar_buttons.ctp';
}

/* ----------------------------------------------------------------------------
 * List
 */
?>
<?php echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n"; ?>
<div class="article-list">
	<?php
	// Everything on one line
	if ($listIsCompact == true):
		// sfw
		echo $sfwContent;
		echo (!is_null($sfwField)) ? ' - ' . $titleContent : $titleContent;
		echo $catContent;
		echo $cDateContent;
		echo $authorContent;

		//Actions
		if ($haveActions == true && $hideActionsList == false):
			echo $actionsButton;
		endif;
	else:
		// Header
		?>
		<div class="article-list-header">
			<?php
			echo $sfwContent;
			echo $titleContent;
			echo $catContent;

			// Actions
			if ($haveActions == true && $hideActionsList == false):
				echo $actionsButton;
			endif;
			?>
		</div>
		<div class="article-list-content<?php echo $contentStyle?>">
			<?php echo $contentContent; ?>
		</div>
		<div class="article-list-footer">
			<?php
			echo $authorContent;
			echo $cDateContent;
			echo $mDateContent;
			echo $licenseContent;
			?>
		</div>
		<?php
	endif;
	?>
	<?php
	/*
	  <tr>
	  <?php
	  if ($haveActions == true && $hideActionsList == false) {
	  ?>
	  <td class="actions">

	  </td>
	  <?php
	  }
	  foreach ($fields as $field) {
	  if (count($languageFields) > 0 && in_array($field, $languageFields)) {
	  $content = "((!empty(\${$singularVar}['{$modelClass}']['{$field}']))?\${$singularVar}['{$modelClass}']['{$field}']:'<i " . $this->v_tooltip("'." . $this->iString('This item has not been translated yet. This is the original version.') . ".'", 'fa fa-warning text-warning') . " ></i> '.\${$singularVar}['{$modelClass}']['{$field}_default'])";
	  } else {
	  $content = "\${$singularVar}['{$modelClass}']['{$field}']";
	  }
	  if (!in_array($field, $hiddenFields)) {
	  $isKey = false;
	  if (!empty($associations['belongsTo'])) {
	  foreach ($associations['belongsTo'] as $alias => $details) {
	  if ($field === $details['foreignKey']) {
	  $isKey = true;
	  ?>
	  <td>
	  <?php echo "<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('plugin' =>" . (($this->getControllerPluginName($details['controller']) == null) ? " null" : " '" . $this->getControllerPluginName($details['controller']) . "'") . ", 'controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>" ?>
	  </td>
	  <?php
	  break;
	  }
	  }
	  }
	  if ($isKey !== true) {
	  ?>
	  <td><?php echo "<?php echo $content; ?>" ?></td>
	  <?php
	  }
	  }
	  }
	  ?>
	  </tr>
	  <?php
	 */
	?>
</div>
<?php
echo "<?php endforeach; ?>\n";
?>

<?php
/* ---------------------------------------------------------------------------
 * Pagination
 */
include $themePath . 'views/common/pagination.ctp';

/* -----------------------------------------------------------------------------
 * Additionnal scripts and CSS
 */
include $themePath.'views/common/additionnal_js_css.ctp';
