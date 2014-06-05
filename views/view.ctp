<?php
/**
 * "View" view (used to display an item)
 *
 * Options from view/controller/part config:
 *  - noToolbar
 *  - hiddenFields
 *  - additionnalCSS
 *  - additionnalJS
 *  - relatedDataHideActionsList
 *  - hasMany_hideActions
 *  - hasMany_hiddenModels
 *  - hasMany_hiddenModelFields
 *  - languageFields
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
/* ----------------------------------------------------------------------------
 * Preparing data from model
 */

// Preparing schema srtucture and fields to replace the ones from Cake
$this->s_prepareSchemaFields();
// Updating schema array
$schema = $this->templateVars['schema'];
// Updating fields array
$fields = $this->templateVars['fields'];


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
 * Options from theme
 */

//// Fields that possibly contain nsfw data:
//$nsfwDataFields=$this->Sbc->getConfig('theme.nsfwDataFields');
//// Fields that possibly can define if an item is safe or not:
//$sfwField=$this->Sbc->getConfig('theme.sfw.sfwField');
//
//// Fields that defines a post as anonymous
//$anonField=$this->Sbc->getConfig('theme.anon.field');
//// Fields that can contain data compromising user anonymity
//$anonDataFields=$this->Sbc->getConfig('theme.anon.dataFields');

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

/* ---------------------------------------------------------------------------
 *
 * Preparing contents
 *
 * --------------------------------------------------------------------------- */

// Fields that are on the big side
$textFields = array();
// Fields in a smaller column
$regularFields = array();
$i = 0;
foreach ($fields as $field) {

	$isTextField = false;

	$key = $this->v_isFieldKey($field, $associations);

	// Field is "just" a field
	if (!is_array($key)) {
		// Preparing string to display
		$fieldContent = $this->v_prepareField($field, $schema[$field]);

		/*
		 * Big content fields or fields to put aside ?
		 */
		// Field may be a big text field to put aside:
		if (in_array($schema[$field]['type'], array('string', 'text'))) {
			// I deliberatedly search in array to leave possiblility to add new field
			// types, bypassing the string lenght
			if ($schema[$field]['length'] > 50 || in_array($schema[$field]['type'], array('text'))) {
				$textFields[$field] = array(
						'field' => "echo " . $this->iString(Inflector::humanize($field)) . ";",
						'content' => $fieldContent['displayString']
				);
				$isTextField = true;
			}
		}
		if ($isTextField == false) {
			$regularFields[$field] = array(
					'field' => "echo " . $this->iString(Inflector::humanize($field)) . ";",
					'content' => $fieldContent['displayString']
			);
		}
	} else {
		// Foreign key:
		$fieldContent = $this->v_prepareFieldForeignKey($field, $key, $schema[$field]);
		$regularFields[$field] = array(
				'field' => "echo " . $this->iString(Inflector::humanize($field)) . ";",
				'content' => $fieldContent['displayString']
		);
	}
} // end foreach

/* ----------------------------------------------------------------------------
 *
 * View
 *
 * --------------------------------------------------------------------------- */
/* ----------------------------------------------------------------------------
 * Headers and licensing
 */
include $themePath . 'views/common/headers.ctp';

/* ----------------------------------------------------------------------------
 * Toolbar
 */
// This view represents an item:
$viewIsAnItem = true;
if ($noToolbar === false):
	include dirname(__FILE__) . DS . 'common' . DS . 'toolbar_buttons.ctp';
endif;
/* ----------------------------------------------------------------------------
 * Current view
 */
?>
<div class="<?php echo $pluralVar; ?> view">
	<?php
	if (count($textFields) > 0):
		echo "<div class=\"row\">\n";
		echo "\t<div class=\"col-sm-4\">\n";
	endif;
	?>
	<dl class="dl-horizontal">
		<?php
		foreach ($regularFields as $field):
			echo "\t\t<dt><?php {$field['field']} ?></dt>\n";
			echo "\t\t<dd>{$field['content']}</dd>\n";
		endforeach;
		?>
	</dl>
	<?php
	/* ----------------------------------------------------------------------------
	 *
	 * Associations
	 *
	 */

	/* ----------------------------------------------------------------------------
	 * HasOne associations
	 */
	$hasOne = '';
	if (!empty($associations['hasOne'])):
		foreach ($associations['hasOne'] as $alias => $details):

			// Keep a copy of the original fields list for later use
			$originalFieldsList = $details['fields'];
			// Prepare the fields
			$details = $this->s_prepareSchemaRelatedFields($alias, $details, true);

			// View for hasOne associations.
			$hasOne.= "<?php if (isset(\${$singularVar}['{$alias}']['{$details['primaryKey']}'])): ?>\n";
			$hasOne.= '<div class="related">';
			$hasOne.= "<h3><?php echo " . $this->iString("Related " . Inflector::humanize($details['controller'])) . "; ?></h3>";
			$hasOne.="<dl class=\"dl-horizontal\">";

			// Fields
			foreach ($details['fields'] as $field):

				$fieldContent = $this->v_prepareRelatedField($field, $details, $originalFieldsList, true);
				$hasOne.= "\t\t<dt><?php echo " . $this->iString(Inflector::humanize($field)) . "; ?></dt>\n";
				$hasOne.= "\t\t<dd>\n\t{$fieldContent['displayString']}\n</dd>\n";

			endforeach;

			$hasOne.="</dl>";
			if ($this->canDo('edit', null, $details['controller'])):
				$hasOne.='<div class="actions">';
				$hasOne.="<ul>";
				$hasOne.="<li><?php echo \$this->Html->link(" . $this->iString("Edit " . Inflector::humanize(Inflector::underscore($alias))) . ", " . $this->url('edit', $details['controller'], null, "\${$singularVar}['{$alias}']['{$details['primaryKey']}']") . "); ?></li>\n";
				$hasOne.="</ul>";
				$hasOne.="</div>";
			endif;

			$hasOne.="</div>";
			$hasOne.="<?php endif; ?>\n";
		endforeach;
	endif; // End of hasOne associations
	echo $hasOne;

	if (count($textFields) > 0):
		echo "\t</div>\n";
		echo "\t<div class=\"col-sm-8\">";
		echo "\t\t<dl>\n";
		foreach ($textFields as $field):
			echo "\t\t\t<dt><?php {$field['field']} ?></dt>\n";
			echo "\t\t\t<dd>{$field['content']}</dd>\n";
		endforeach;
		echo "\t\t</dl>\n";
		echo "\t</div>\n";
	endif;
	?>
</div>
<?php
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
// Number of relations. If there is more than one, we should display a tabbed list.
$relationsCount = count($relations);

$has_assoc = 0;
$active = 'class="active"';
$inline_active = ' active';
$lis = '';
$divs = '';
foreach ($relations as $alias => $details):
	// Copying the original fields list
	$originalFieldList = $details['fields'];
	// Updating details infos
	$details = $this->s_prepareSchemaRelatedFields($alias, $details);
	// CamelCasing controller name
	$ccController = Inflector::camelize($details['controller']);
	if (!in_array($ccController, $hasMany_hiddenModels)):
		$has_assoc+=1;

		$otherSingularVar = Inflector::variable($alias);
		$otherPluralHumanName = Inflector::humanize($details['controller']);

		// Testing actions
		if ($relatedDataHideActionsList === false):
			if ($hasMany_hideActions == false):
				$actionsButton = '';
				$hasActions = 0;
				$actions = array();
				// "View" action
				if ($this->canDo('view', null, $ccController)):
					$hasActions += 1;
					$actions[] = "<?php echo \$this->Html->Link('<i class=\"fa fa-eye\"></i> ' . __('View')," . $this->url('view', $details['controller'], null, "\${$otherSingularVar}['{$details['primaryKey']}']") . ", array('title'=>__('View'), 'escape'=> false));?>";
				endif;
				// "Edit" action
				if ($this->canDo('edit', null, $ccController)):
					$hasActions += 1;
					$actions[]="<?php echo \$this->Html->Link('<i class=\"fa fa-pencil\"></i> ' . __('Edit')," . $this->url('edit', $details['controller'], null, "\${$otherSingularVar}['{$details['primaryKey']}']") . ", array('title'=>__('Edit'), 'escape'=> false));?>";
				endif;
				// "Delete" action
				if ($this->canDo('delete', null, $ccController)):
					$hasActions += 1;
					$actions[]= "<?php echo \$this->Form->postLink('<i class=\"fa fa-trash-o\"></i> ' .__('Delete'), " . $this->url('delete', $details['controller'], null, "\${$otherSingularVar}['{$details['primaryKey']}']") . ", array('confirm'=>__('Are you sure you want to delete %s?', \${$otherSingularVar}['{$details['primaryKey']}']), 'title'=>__('Delete'), 'escape'=>false)); ?>";
				endif;

				if ($hasActions > 1):
					$actionsButton.= "\t\t\t\t\t\t\t<div class=\"btn-group\">\n";
					$actionsButton.= "\t\t\t\t\t\t\t\t<a class=\"btn btn-xs btn-default dropdown-toggle\" data-toggle=\"dropdown\" href=\"#\">\n";
					$actionsButton.= "\t\t\t\t\t\t\t\t\t<i class=\"fa fa-cog\"></i>\n";
					$actionsButton.= "\t\t\t\t\t\t\t\t\t<span class=\"caret\"></span>\n";
					$actionsButton.= "\t\t\t\t\t\t\t\t</a>\n";
					$actionsButton.= "\t\t\t\t\t\t\t\t<ul class=\"dropdown-menu\">\n";
					foreach ($actions as $action) {
						$actionsButton.="\t\t\t\t\t\t\t\t\t<li>$action</li>\n";
					}
					$actionsButton.= "\t\t\t\t\t\t\t\t</ul>\n";
					$actionsButton.= "\t\t\t\t\t\t\t</div>\n";
				else:
					$actionsButton.="\t\t\t\t\t\t\t<div class=\"btn btn-xs btn-default\">$actions[0]</div>";
				endif;
			endif;
		endif;

		// Tabs headers
		if ($relationsCount > 1):
			$lis.="\t\t\t<li $active>\n\t\t\t\t<a href=\"#tab{$details['controller']}\" data-toggle=\"tab\"><?php echo " . $this->iString($otherPluralHumanName) . "; ?> <span class=\"badge\"><?php echo count(\${$singularVar}['{$alias}']); ?></span></a>\n\t\t\t</li>\n";
			// Tabs contents
			$divs.="\t\t<div class=\"tab-pane $inline_active\" id=\"tab{$details['controller']}\">\n";
		else:
			$lis.= "<h2><?php echo " . $this->iString('Related ' . $otherPluralHumanName) . "?></h2>\n";
		endif;

		// Table data
		$divs.= "\t\t\t<?php if (!empty(\${$singularVar}['{$alias}'])): ?>\n";
		$divs.= "\t\t\t<table class=\"table table-hover table-condensed tableSection\">\n";
		$divs.= "\t\t\t\t<thead>\n";
		$divs.= "\t\t\t\t\t<tr>\n";

		// Actions Col
		if ($relatedDataHideActionsList === false && $hasMany_hideActions === false):
			$divs.= "\t\t\t\t\t\t<th class=\"actionsCol\"><?php echo __('Actions'); ?></th>\n";
		endif;

		//
		// Fields
		//

  // Headers
		foreach ($details['fields'] as $field):
			$divs.= "\t\t\t\t\t\t<th><?php echo " . $this->iString(Inflector::humanize($field)) . "; ?></th>\n";
		endforeach;

		$divs.= "\t\t\t\t\t</tr>\n";
		$divs.= "\t\t\t\t</thead>\n";
		$divs.= "\t\t\t\t<tbody>\n";
		$divs.= "\t\t\t\t<?php\n";
		$divs.= "\t\t\t\tforeach (\${$singularVar}['{$alias}'] as \${$otherSingularVar}): ?>\n";
		$divs.= "\t\t\t\t\t<tr>\n";
		$divs.= "\t\t\t\t\t\t<td class=\"actions\">\n";
		$divs.= $actionsButton;
		$divs.= "\t\t\t\t\t\t</td>\n";
		foreach ($details['fields'] as $field):
			$fieldContent = $this->v_prepareRelatedField($field, $details, $originalFieldList);
			$divs.= "\t\t\t\t\t\t<td>\n{$fieldContent['displayString']}\n</td>\n";
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
	if ($relationsCount > 1):
		echo "\t<h2><?php echo __('Related data'); ?></h2>\n";
		echo "\t\t<ul class=\"nav nav-tabs\">\n";
		echo $lis;
		echo "\t\t</ul>\n";
		echo "\t<div class=\"tab-content\">\n";
		echo $divs;
		echo "\t</div>\n";
	else:
		echo $lis;
		echo $divs;
	endif;
endif;
?>
</div>
<?php
/* -----------------------------------------------------------------------------
 * Additionnal scripts and CSS
 */
include $themePath . 'views/common/additionnal_js_css.ctp';
