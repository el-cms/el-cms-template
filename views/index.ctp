<?php
/**
 * Index view
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Views
 * @version       0.3
 *
 *
 * Options for this view:
 * ======================
 *  - hiddenFields
 *  - sortableFields
 *  - additionnalCSS
 *  - additionnalJS
 *  - hideActionsList
 *  - noToolbar
 *  -
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

// Hidden fields
//if (!isset($hiddenFields) || !is_array($hiddenFields)) {
//	$hiddenFields = array();
//}

// Sortable fields
//if (!isset($unSortableFields) || !is_array($unSortableFields)) {
//	$unSortableFields = array();
//}

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
//$languageFields = (!isset($languageFields)) ? array() : $languageFields;

/* ----------------------------------------------------------------------------
 * Prepare actions for each item
 */
if ($this->canDo('view') === true || $this->canDo('edit') === true || $this->canDo('delete') === true) {
	$haveActions = true;
} else {
	$haveActions = false;
}

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
 * Toolbar include
 */
// Note that hidden controllers are handled in the toolbar template file
if ($noToolbar === false):
	include dirname(__FILE__) . DS . 'common' . DS . 'toolbar_buttons.ctp';
endif;

/* ----------------------------------------------------------------------------
 * Table of data
 */
?>
<div class="<?php echo $pluralVar; ?> index">
	<table class="table table-striped">
		<tr>
			<?php
			// Headers for actions
			if ($haveActions == true && $hideActionsList == false):
				?>
				<th class="actionsCol"><?php echo "<?php echo " . $this->iString('Actions') . "; ?>"; ?></th>
				<?php
			endif;
			foreach ($fields as $field):
				// Field name in table header
				echo "<th>\n"
				. $this->v_paginatorField($field, $unSortableFields)
				. "\n</th>";
			endforeach;
			?>
		</tr>
		<?php echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n"; ?>
		<tr>
			<?php
			if ($haveActions == true && $hideActionsList == false):
				?>
				<td class="actionsCol">
					<div class='btn-group'>
						<?php
						// View
						if ($this->canDo('view')):
							echo "<?php echo \$this->Html->link('" . $this->v_icon('eye', "'.__('View').'") . "', array('action' => 'view', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn btn-primary btn-xs', 'escape'=>false)); ?>\n";
						endif;
						// Edit
						if ($this->canDo('edit')):
							echo "<?php echo \$this->Html->link('" . $this->v_icon('pencil', "'.__('Edit').'") . "', array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn btn-primary btn-xs', 'escape'=>false)); ?>\n";
						endif;
						// Delete
						if ($this->canDo('delete')):
							echo "<?php echo \$this->Form->postLink('" . $this->v_icon('times', "'.__('Delete').'") . "', array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class'=>'btn btn-danger btn-xs', 'escape'=>false), __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
						endif;
						?>
					</div>
				</td>
				<?php
			endif;

			foreach ($fields as $field):

				// Preparing strings to display
				$fieldContent = $this->v_prepareField($field, $schema[$field]);
				$key = $this->v_isFieldKey($field, $associations);
				?>
				<td<?php echo $fieldContent['tdClass'] ?>>
					<?php
					// Foreign key
					if (is_array($key)):
						$fieldContent = $this->v_prepareFieldForeignKey($field, $key, $schema[$field]);
					endif;
					echo $fieldContent['displayString'] . "\n";
					?>
				</td>
				<?php
			endforeach;
			?>
		</tr>
		<?php
		echo "<?php endforeach; ?>\n";
		?>
	</table>

	<?php
	/* ---------------------------------------------------------------------------
	 * Pagination
	 */
	include $themePath . 'views/common/pagination.ctp';
	?>
</div>

<?php
/* -----------------------------------------------------------------------------
 * Additionnal scripts and CSS
 */
include $themePath . 'views/common/additionnal_js_css.ctp';
