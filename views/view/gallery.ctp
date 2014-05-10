<?php
/**
 * This will display the content of a gallery :
 * The gallery itself should :
 *  - have a title                   titleField
 *  - have a content/excerpt         contentField
 *  - was created by an user         authorField
 *  - may be safe or not for work    sfwField
 *  - can be anonymous.              anonField
 *  - can be public (+w)             publicField
 *  - be tied to items model         itemsModel
 * Then, each item should :
 *  - have a title                   itemTitle
 *  - have a description             itemDescription
 *  - have a target file             itemTarget
 *  - be safe for work or not        itemSfw
 *  - have a license                 itemLicense
 *  - belongs to an user             itemAuthor
 *
 * Other options (* is for default):
 *  - keepExtraFields         true|*false       set to true to keep extra fields
 *  - noToolbar               true|*false       set to true to hide the toolbar
 *  - languageFields          array             define fields that have internationalized content
 *  - listIsCompact           true|*false       set it to true if you want a compact list (no description)
 *  - relatedDataHideActionsList true|*false    set it to true to disable actions on related (view/edit/delete) data.
 *  - hasMany_hideActions     true|*false       set it to true to hide actions on HasMany relations
 *  - hasMany_hiddenModels    array(model1, model2,...)   list of hidden HasMany related models
 *  - hasMany_hiddenModelFields array(model1(field1, field2)...) List of hidden fields in related models.
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Views
 * @version       0.3
 *
 */
//Page headers and licensing
include $themePath . 'views/common/headers.ctp';


/* ----------------------------------------------------------------------------
 * Current template options
 */

// No toolbar option
if (!isset($noToolbar)) {
	$noToolbar = false;
}

// Hidden fields for current model
if (!isset($hiddenFields) || !is_array($hiddenFields)) {
	$hiddenFields = array();
}

// Additionnal CSS
if (!isset($additionnalCSS) || !is_array($additionnalCSS)) {
	$additionnalCSS = array();
}
// Additionnal JS
if (!isset($additionnalJS) || !is_array($additionnalJS)) {
	$additionnalJS = array();
}

// Related data hide actions list for each entry
if (!isset($relatedDataHideActionsList)) {
	$relatedDataHideActionsList = false;
}

/* ----------------------------------------------------------------------------
 * related data options
 */
// Has Many : hide actions
if (!isset($hasMany_hideActions)) {
	$hasMany_hideActions = false;
}

// Has Many : hidden models
if (!isset($hasMany_hiddenModels) || !is_array($hasMany_hiddenModels)) {
	$hasMany_hiddenModels = array();
}

// Has Many : hidden models fields
if (!isset($hasMany_hiddenModelFields) || !is_array($hasMany_hiddenModelFields)) {
	$hasMany_hiddenModelFields = array();
}

// Internationalized fields (for field display. If empty, selects all fields)
$languageFields = (!isset($languageFields)) ? array() : $languageFields;

/* ----------------------------------------------------------------------------
 * Field references
 */
//
// Current gallery:
//
// Items model. Can't be null
if (!isset($itemsModel)) {
	$itemsModel = '';
	$this->speak(__d('superBake', 'An itemsModel option is required for this view'), 'warning');
}
// Gallery title. Can be null
if (!isset($titleField)) {
	$titleField = null;
}

//Gallery description. Optionnal
if (!isset($contentField)) {
	$contentField = null;
}

// Author. Optionnal
if (!isset($authorField)) {
	$authorField = null;
}

// SFW field. Optionnal
if (!isset($sfwField) || !$this->Sbc->getConfig('theme.sfw.useSFW')) {
	$sfwField = null;
}

// Anonymous field. Optionnal
if (!isset($anonField)) {
	$anonField = null;
}

// Public field. Optionnal.
if (!isset($publicField)) {
	$publicField = null;
}

//
// Gallery content:
//
// Item title/name. Optionnal
if (!isset($itemTitle)) {
	$itemTitle = null;
}

// Item description. Optionnal
if (!isset($itemDescription)) {
	$itemDescription = null;
}

// Item target/file. Optionnal
if (!isset($itemTarget)) {
	$itemTarget = null;
}

// Item SFW. Optionnal
if (!isset($itemSfw) || !$this->Sbc->getConfig('theme.sfw.useSFW')) {
	$itemSfw = null;
}

// Item licence. Optionnal
if (!isset($itemLicense)) {
	$itemLicense = null;
}

// Item author. Optionnal
if (!isset($itemAuthor)) {
	$itemAuthor = null;
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
 * Toolbar
 */
// This view represents an item:
$viewIsAnItem = true;
if ($noToolbar === false):
	include dirname(__FILE__) . DS . 'common' . DS . 'toolbar_buttons.ctp';
endif;
/* ----------------------------------------------------------------------------
 * Current record values
 */
?>

<div class="<?php echo $pluralVar; ?> view">
	<?php
	//
	// Informations on the gallery:
	echo "<?php\n\t\$string=" . $this->iString("This " . strtolower("$singularHumanName")) . ";\n";
	// Sfw:
	if (!is_null($sfwField)):
		echo "\tif(\${$singularVar}['{$modelClass}']['{$sfwField}'] === ". $this->Sbc->getConfig('theme.sfw.fieldSafeContent')."):\n"
		. "\t\t\$string .= " . $this->iString(' is safe for work.') . ";\n"
		. "\telse:\n"
		. "\t\t\$string .= " . $this->iString(' is NOT safe for work.') . ";\n"
		. "\tendif;\n\n";
	endif;
	// User:
	if (!is_null($authorField)):
		// Anon field
		if (!is_null($anonField)):
			echo "\tif(\${$singularVar}['{$modelClass}']['{$anonField}'] === 1):\n"
			. "\t\t\$string .= " . $this->iString('It has been created by an anonymous author') . ";\n"
			. "\telse:\n";
		else:
			echo "\t\tif(\${$singularVar}['{$modelClass}']['{$contentField}'] === 1):\n";
		endif;
		echo "\t\t\$string .= ' ' . " . $this->iString('It has been created by') . " . ' ' . \${$singularVar}['{$modelClass}']['{$authorField}'];\n"
		. "\t\tendif;\n\n";
	endif;

	echo "\techo \$string;\n\t?>\n";
	if (!is_null($contentField)):
		?>
		<div class="description">
			<?php echo "<?php echo \${$singularVar}['{$modelClass}']['{$contentField}']?>\n"; ?>
		</div>
		<?php
	endif;
	?>
</div>
<?php
//
// Items
//

if (!is_null($itemsModel)):
	unset($associations['hasMany'][$itemsModel]);
	?>
	<div class="itemList">
		<?php
		$otherSingularVar = ucfirst(Inflector::variable(inflector::singularize($itemsModel)));
		echo "<?php\n\tforeach(\${$singularVar}['{$otherSingularVar}'] as \${$itemsModel}):\n"
		. "\t\techo 'pouet';"
		. "\tendforeach;?>\n";
		?>
	</div>
	<?php
endif;
/* ----------------------------------------------------------------------------
 * HasOne associations
 */
if (!empty($associations['hasOne'])):
	foreach ($associations['hasOne'] as $alias => $details):
		?>
		<div class="related">
			<h3><?php echo "<?php echo " . $this->iString("Related " . Inflector::humanize($details['controller'])) . "; ?>"; ?></h3>
			<?php echo "<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n"; ?>
			<dl>
				<?php
				foreach ($details['fields'] as $field):
					echo "\t\t<dt><?php echo " . $this->iString(Inflector::humanize($field)) . "; ?></dt>\n";
					echo "\t\t<dd>\n\t<?php echo \${$singularVar}['{$alias}']['{$field}']; ?>\n&nbsp;</dd>\n";
				endforeach;
				?>
			</dl>
			<?php echo "<?php endif; ?>\n"; ?>
			<div class="actions">
				<ul>
					<li><?php echo "<?php echo \$this->Html->link(" . $this->iString("Edit " . Inflector::humanize(Inflector::underscore($alias))) . ", " . $this->url('edit', $details['controller'], null, "\${$singularVar}['{$alias}']['{$details['primaryKey']}']") . "); ?></li>\n"; ?>
				</ul>
			</div>
		</div>
		<?php
	endforeach;
endif;

/* ----------------------------------------------------------------------------
 * HasMany associations
 */
if (empty($associations['hasMany'])):
	$associations['hasMany'] = array();
endif;
if (empty($associations['hasAndBelongsToMany'])):
	$associations['hasAndBelongsToMany'] = array();
endif;
$relations = array_merge($associations['hasMany'], $associations['hasAndBelongsToMany']);
$i = 0;

$has_assoc = 0;
$active = 'class="active"';
$inline_active = ' active';
$lis = '';
$divs = '';
foreach ($relations as $alias => $details):
	// CamelCasing controller name
	$ccController = Inflector::camelize($details['controller']);
	if (!in_array($ccController, $hasMany_hiddenModels)):
		$has_assoc+=1;

		$otherSingularVar = Inflector::variable($alias);
		$otherPluralHumanName = Inflector::humanize($details['controller']);
		// Tabs headers
		$lis.="\t\t\t<li $active>\n\t\t\t\t<a href=\"#tab{$details['controller']}\" data-toggle=\"tab\"><?php echo " . $this->iString($otherPluralHumanName) . "; ?> <span class=\"badge\"><?php echo count(\${$singularVar}['{$alias}']); ?></span></a>\n\t\t\t</li>\n";
		// Tabs contents
		$divs.="\t\t<div class=\"tab-pane $inline_active\" id=\"tab{$details['controller']}\">\n";

		// Table data
		$divs.= "\t\t\t<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n";
		$divs.= "\t\t\t<table class=\"table table-hover table-condensed tableSection\">\n";
		$divs.= "\t\t\t\t<thead>\n";
		$divs.= "\t\t\t\t\t<tr>\n";

		// Actions Col
		if ($relatedDataHideActionsList === false && $hasMany_hideActions === false):
			$divs.= "\t\t\t\t\t\t<th class=\"actionsCol\"><?php echo __('Actions'); ?></th>\n";
		endif;
		// Fields
		foreach ($details['fields'] as $field):
			if ((isset($hasMany_hiddenModelFields[Inflector::camelize($details['controller'])]) && !in_array($field, $hasMany_hiddenModelFields[Inflector::camelize($details['controller'])])) || !isset($hasMany_hiddenModelFields[Inflector::camelize($details['controller'])])):
				$divs.= "\t\t\t\t\t\t<th><?php echo " . $this->iString(Inflector::humanize($field)) . "; ?></th>\n";
			endif;
//			$divs.= "\t\t\t\t\t\t<th><?php echo " . $this->iString(Inflector::humanize($field)) . "; ? ></th>\n";
		endforeach;

		$divs.= "\t\t\t\t\t</tr>\n";
		$divs.= "\t\t\t\t</thead>\n";
		$divs.= "\t\t\t\t<tbody>\n";
		$divs.= "\t\t\t\t<?php\n";
		$divs.= "\t\t\t\tforeach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}): ?>\n";
		$divs.= "\t\t\t\t\t<tr>\n";

		// Testing actions
		if ($relatedDataHideActionsList === false):
			if ($hasMany_hideActions == false):
				$hasActions = 0;
				$actions = '';
				$disabled = '';
				// "View" action
				if ($this->canDo('view', null, $ccController)):
					$hasActions = 1;
					$actions.= "\t\t\t\t\t\t\t\t\t<li><?php echo \$this->Html->Link('<i class=\"fa fa-eye\"></i> ' . __('View')," . $this->url('view', $details['controller'], null, "\${$otherSingularVar}['{$details['primaryKey']}']") . ", array('title'=>__('View'), 'escape'=> false));?></li>\n";
				endif;
				// "Edit" action
				if ($this->canDo('edit', null, $ccController)):
					$hasActions = 1;
					$actions.="\t\t\t\t\t\t\t\t\t<li><?php echo \$this->Html->Link('<i class=\"fa fa-pencil\"></i> ' . __('Edit')," . $this->url('edit', $details['controller'], null, "\${$otherSingularVar}['{$details['primaryKey']}']") . ", array('title'=>__('Edit'), 'escape'=> false));?></li>\n";
				endif;
				// "Delete" action
				if ($this->canDo('view', null, $ccController)):
					$hasActions = 1;
					$actions.= "\t\t\t\t\t\t\t\t\t<li><?php echo \$this->Form->postLink('<i class=\"fa fa-trash-o\"></i> ' .__('Delete'), " . $this->url('delete', $details['controller'], null, "\${$otherSingularVar}['{$details['primaryKey']}']") . ", array('confirm'=>__('Are you sure you want to delete %s?', \${$otherSingularVar}['{$details['primaryKey']}']), 'title'=>__('Delete'), 'escape'=>false)); ?></li>\n";
				endif;

				// Disabled button state
				if ($hasActions == 0):
					$disabled = ' disabled';
				endif;
				$divs.= "\t\t\t\t\t\t<td class=\"actions\">\n";
				$divs.= "\t\t\t\t\t\t\t<div class=\"btn-group\">\n";
				$divs.= "\t\t\t\t\t\t\t\t<a class=\"btn btn-xs btn-default dropdown-toggle$disabled\" data-toggle=\"dropdown\" href=\"#\">\n";
				$divs.= "\t\t\t\t\t\t\t\t\t<i class=\"fa fa-cog\"></i>\n";
				$divs.= "\t\t\t\t\t\t\t\t\t<span class=\"caret\"></span>\n";
				$divs.= "\t\t\t\t\t\t\t\t</a>\n";
				$divs.= "\t\t\t\t\t\t\t\t<ul class=\"dropdown-menu\">\n";
				$divs.= $actions;
				$divs.= "\t\t\t\t\t\t\t\t</ul>\n";
				$divs.= "\t\t\t\t\t\t\t</div>\n";
				$divs.= "\t\t\t\t\t\t</td>\n";
			endif;
		endif;
		foreach ($details['fields'] as $field):
			if ((isset($hasMany_hiddenModelFields[Inflector::camelize($details['controller'])]) && !in_array($field, $hasMany_hiddenModelFields[Inflector::camelize($details['controller'])])) || !isset($hasMany_hiddenModelFields[Inflector::camelize($details['controller'])])):
				$divs.= "\t\t\t\t\t\t<td><?php echo \${$otherSingularVar}['{$field}']; ?></td>\n";
			endif;
		endforeach;
		$divs.= "\t\t\t\t\t</tr>\n";
		$divs.= "\t\t\t\t<?php endforeach; ?>\n";
		$divs.= "\t\t\t\t</tbody>\n";
		$divs.= "\t\t\t</table>\n";
		$divs.= "\t\t<?php else: ?>\n";
		$divs.= "\t\t<div class=\"text-info\">\n\t\t\t<?php echo " . $this->iString('No "' . strtolower($otherPluralHumanName) . '" associated to current ' . $singularVar) . "; ?>\n\t\t</div>\n";
		$divs.= "\t\t<?php endif; ?>\n";
		$divs.= "\t\t</div>\n";
		$active = '';
		$inline_active = '';
	endif;
endforeach;
/* ----------------------------------------------------------------------------
 * Display associations
 */
if ($has_assoc > 0):
	echo "\t<h2><?php echo __('Related data'); ?></h2>\n";
	echo "\t\t<ul class=\"nav nav-tabs\">\n";
	echo $lis;
	echo "\t\t</ul>\n";
	echo "\t<div class=\"tab-content\">\n";
	echo $divs;
	echo "\t</div>\n";
endif;

/* -----------------------------------------------------------------------------
 * Additionnal scripts and CSS
 */
include $themePath . 'views/common/additionnal_js_css.ctp';
