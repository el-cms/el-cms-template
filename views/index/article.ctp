<?php
/**
 * Index view for an article list.
 *
 * Basically, an article:
 *  - have a title                   titleField
 *  - have a content/excerpt         contentField
 *  - have a creation date           cDateField
 *  - have a modification date       mDateField
 *  - have a category                catField
 *  - have a license                 licenseField
 *  - was written by an user         authorField
 *
 * Other options (* is for default):
 *  - keepExtraFields         true|*false       set to true to keep extra fields
 *  - hideActionList          *true|false       set to false to show an action list
 *  - noToolbar               true|*false       set to true to hide the toolbar
 *  - listIsCompact           true|*false       set it to true if you want a compact list (no description)
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Views
 * @version       0.3
 *
 * ---
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

/* ----------------------------------------------------------------------------
 * Preparing fields
 */
// By default, the view is complete
$listIsCompact = false;

// SFW field. Can be empty/null.
if (!isset($sfwField)) {
	// Field
	$sfwField = null;
	// Content for an icon in view
	$sfwContent = null;
} else {
	// Content for the icon in view
	$sfwContent = $this->v_prepareField($sfwField, $schema[$sfwField]);
}

// Title field. If empty, will use primary key
if (!isset($titleField)) {
	$titleField = null;
	$titleContent = null;
} else {
	$url = null;
	if ($this->canDo('view')) {
		$url = "array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}'])";
	}
	$titleContent = $this->v_prepareField($titleField, $schema[$titleField], array('url'=>$url));
}

// Content field. If empty, the article list will be compact.
if (!isset($contentField)) {
	$contentField = null;
	$contentContent = null;
	// If no content, view will be compact
	$listIsCompact = true;
} else {
	$contentContent = $this->v_prepareField($contentField, $schema[$contentField]);
}

// Creation date field. Can be empty/null.
if (!isset($cDateField)) {
	$cDateField = null;
	$cDateContent = null;
} else {
	$cDateContent = $this->v_prepareField($cDateField, $schema[$cDateField]);
	$cDateContent = $this->v_icon('calendar') . $cDateContent['displayString'];
}

// Modification date field. Can be empty/null.
if (!isset($mDateField)) {
	$mDateField = null;
	$mDateContent = null;
} else {
	$mDateContent = $this->v_prepareField($mDateField, $schema[$mDateField]);
	$mDateContent = $this->v_icon('calendar') . $mDateContent['displayString'];
}
// Category field. Can be empty/null.
if (!isset($catField)) {
	$catField = null;
	$catContent = null;
} else {
	// Search if this key is in a foreign table
	$fk = $this->v_isFieldKey($catField, $associations);
	if (is_array($fk)) {
		$catContent = $this->v_prepareFieldForeignKey($catField, $fk, $schema[$catField]);
	} else {
		$catContent = $this->v_prepareField($catField, $schema[$catField]);
	}
	$catContent = $catContent['displayString'];
}
// License field. Can be empty/null.
if (!isset($licenseField)) {
	$licenseField = null;
	$licenseContent = null;
} else {
	// Search if this key is in a foreign table
	$fk = $this->v_isFieldKey($licenseField, $associations);
	if (is_array($fk)) {
		$licenseContent = $this->v_prepareFieldForeignKey($licenseField, $fk, $schema[$licenseField]);
	} else {
		$licenseContent = $this->v_prepareField($licenseField, $schema[$licenseField]);
	}
	$licenseContent = $this->v_icon('tag') . $licenseContent['displayString'];
}
// Authorfield. Can be empty/null.
if (!isset($authorField)) {
	$authorField = null;
	$authorContent = null;
} else {
	// Search if this key is in a foreign table
	$fk = $this->v_isFieldKey($authorField, $associations);
	if (is_array($fk)) {
		$authorContent = $this->v_prepareFieldForeignKey($authorField, $fk, $schema[$authorField]);
	} else {
		$authorContent = $this->v_prepareField($authorField, $schema[$authorField]);
	}
	$authorContent = $this->v_icon('user') . $authorContent['displayString'];
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
if ($this->canDo('view') && empty($titleField)) {
	$actionsList.="\t\t\techo \$this->Html->link(" . $this->iString('View') . ", array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn btn-primary btn-xs'));\n";
}
// Edit
if ($this->canDo('edit')) {
	$actionsList.="\t\t\techo \$this->Html->link(" . $this->iString('Edit') . ", array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn btn-primary btn-xs'));\n";
}
// Delete
if ($this->canDo('delete')) {
	$actionsList.="\t\t\techo \$this->Form->postLink(" . $this->iString('Delete') . ", array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn btn-danger btn-xs'), __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
}
if (!empty($actionsList)) {
	$actionsButton = "\t\t<div class=\"pull-right\">\n\t\t\t<div class=\"btn-group\">\n\t\t\t<?php\n\t\t\t// Actions for current item\n$actionsList\t\t\t?>\n\t\t\t</div>\n\t\t</div>\n";
}

//Page headers and licensing
include $themePath . 'views/common/headers.ctp';

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
echo "<?php\nforeach (\${$pluralVar} as \${$singularVar}):\n";
if (!$this->s_haveSFW()):
	echo "\t// Verifying SFW state\n";
	echo "\tif(\${$singularVar}['$modelClass']['$sfwField'] == 0 && \$seeNSFW == false):?>\n";
	?>
	<div class="article-list">
		<div class="article-list-header text-muted">
			<?php echo "<?php echo __('This content may not be safe for work and will not be displayed.');?>\n" ?>
		</div>
	</div>
	<?php
	echo "<?php\n\telse:\n";
endif;
echo "?>";
?>
<div class="article-list">
	<?php
	// Everything on one line
	if ($listIsCompact == true):
		// sfw
		echo $sfwContent['displayString'];
		echo $titleContent['displayString'];
		echo "<?php echo " . $this->iString('in') . ";?> " . $catContent;
		echo $cDateContent;
		echo $authorContent;

		//Actions
		if ($haveActions == true && $hideActionsList == false):
			echo $actionsButton;
		endif;
	else:
		// Header
		echo "<div class=\"article-list-header\">\n";
		echo "<h2 class=\"inline\">{$sfwContent['displayString']} {$titleContent['displayString']}</h2>";
		echo "<span class=\"header-content\"><?php echo " . $this->iString('in') . ";?> $catContent</span>";

		// Actions
		if ($haveActions == true && $hideActionsList == false):
			echo $actionsButton;
		endif;
		// End of item headers
		echo "\t</div>\n\n";

		echo "\t<div class=\"article-list-content\">\n";
		echo $contentContent['displayString'];
		echo "\t</div>\n\n";

		echo "\t<div class=\"article-list-footer\">\n";
		echo implode("\n&nbsp;-&nbsp;", array_filter(array(
				$authorContent,
				$cDateContent,
				$mDateContent,
				$licenseContent
		)));
		echo "\t</div>\n";
	endif;
	?>
</div>
<?php
echo "<?php";
if (!$this->s_haveSFW()):
	echo "\nendif;\n";
endif;
echo "\nendforeach;?>\n";

/* ---------------------------------------------------------------------------
 * Pagination
 */
include $themePath . 'views/common/pagination.ctp';

/* -----------------------------------------------------------------------------
 * Additionnal scripts and CSS
 */
include $themePath . 'views/common/additionnal_js_css.ctp';
