<?php

/**
 * PHP file for EL-CMS
 *
 * This file should be included in any template that needs a "related controllers actions" part.
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Views
 * @version       0.3
 *
 * ----
 *
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
// ---
// Resetting options to defaults if not defined
// ---
// Hidden controllers
if (!isset($toolbarHiddenControllers)) {
	$toolbarHiddenControllers = array();
}

// Is the view displaying an item ? Define this variable in views that represents an item
// and the delete/Edit actions will be created.
if (!isset($viewIsAnItem)) {
	$viewIsAnItem = false;
}

//// Actions per row
//if (!isset($actionsPerRow)) {
//	$actionsPerRow = 4;
//}

// Default button size:
// --------------------
// Large: btn-lg
// Default: leave empty
// Small : btn-sm
// Extra small: $btnSize
if (!isset($btnSize)) {
	$btnSize = 'btn-xs';
}

// No toolbar ?
if (!isset($noToolbar) || empty($noToolbar)) {
	$noToolbar = false;
}

if ($noToolbar == false) {
	// ---
	// Creating the toolbar
	// ---
	// Definying final toolbar (will contain all the elements)
	$toolbar = '';

//	// Number of toolbar groups. Used to count items on rows. Default to -1 to know
//	// that no row is currently opened
//	$tbRowElements = -1;
	// Total number of elements in the toolbar (used to display it or not)
	$toolbarElements = 0;

	// ---
	// Toolbar for current controller
	// ---
	$current_toolbar = array();

	// Index
	if ($this->canDo('index')) {
		$title = $this->iString("List " . strtolower($pluralHumanName));
		$current_toolbar[] = "<?php echo \$this->Html->Link('<i class=\"fa fa-" . $this->Sbc->getConfig("plugins." . $this->Sbc->pluginName($plugin) . ".parts.$currentPart.controller.actions.$admin.index.options.icon") . "\"></i> ' . " . $title . "," . $this->url('index', $pluralVar) . ", array('class'=>'btn $btnSize btn-default', 'title' => " . $title . ", 'escape' => false));?>\n";
	}

	// Add
	if ($this->canDo('add')) {
		$title = $this->iString("New " . strtolower($singularHumanName));
		$current_toolbar[] = "<?php echo \$this->Html->Link('<i class=\"fa fa-" . $this->Sbc->getConfig("plugins." . $this->Sbc->pluginName($plugin) . ".parts.$currentPart.controller.actions.$admin.add.options.icon") . "\"></i> ' . " . $title . "," . $this->url('add', $pluralVar) . ", array('class'=>'btn $btnSize btn-default', 'title' => " . $title . ", 'escape' => false));?>\n";
	}

	// Edit (Only on view)
	if ($this->canDo('edit') && $viewIsAnItem) {
		$title = $this->iString("Edit");
		$current_toolbar[] = "<?php echo \$this->Html->Link('<i class=\"fa fa-" . $this->Sbc->getConfig("plugins." . $this->Sbc->pluginName($plugin) . ".parts.$currentPart.controller.actions.$admin.edit.options.icon") . "\"></i> ' . " . $title . "," . $this->url('edit', $pluralVar, null, "\${$singularVar}['{$modelClass}']['{$primaryKey}']") . ", array('class'=>'btn $btnSize btn-default', 'title'=>" . $title . ", 'escape'=> false));?>\n";
	}

	// (Only on view)
	if ($this->canDo('delete') && $viewIsAnItem) {
		$title = $this->iString("Delete");
		$current_toolbar[] = "<?php echo \$this->Form->postLink('<i class=\"fa fa-" . $this->Sbc->getConfig("plugins." . $this->Sbc->pluginName($plugin) . ".parts.$currentPart.controller.actions.$admin.delete.options.icon") . "\"></i> '." . $title . ", " . $this->url('delete', null, null, "\${$singularVar}['{$modelClass}']['{$primaryKey}']") . ", array('confirm' => __('Are you sure you want to delete # %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}']), 'title'=>__('Delete this entry'),'class'=>'btn $btnSize btn-warning',  'escape'=>false)); ?>";
	}
	// Toolbar : Current controller
	if (count($current_toolbar) > 0) {
//		// Row management
//		if ($tbRowElements == -1) {
//			$toolbar.= $this->v_newRow('open');
//			$tbRowElements = 1;
//		}
		// Element
		$toolbar.= $this->v_newButtonGroup($current_toolbar);
		$toolbarElements++;
//		// Row management
//		if ($tbRowElements == $actionsPerRow) {
//			$toolbar.= $this->v_newRow('close');
//			$tbRowElements = -1;
//		}

		// ---
		// Related controllers actions :
		// ---
		$done = array();
		foreach ($associations as $type => $data) {

			foreach ($data as $alias => $details) {
				if ($details['controller'] != $this->name && !in_array($details['controller'], $done) && !in_array(Inflector::camelize($details['controller']), $toolbarHiddenControllers)) {
//					// Row management
//					if ($tbRowElements == -1) {
//						$toolbar.= $this->v_newRow('open');
//						$tbRowElements = 1;
//					}
//					$current_controller_actions = 0;
					$current_toolbar = array();
					// Related controllers actions : List / Add
					if ($this->canDo('index', null, $details['controller'])) {
						$current_controller_actions = 1;
						$title = $this->iString('List ' . strtolower(Inflector::humanize($details['controller'])));
						$current_toolbar[] = "<?php echo \$this->Html->Link('<i class=\"fa fa-" . $this->Sbc->getConfig("plugins." . $this->Sbc->getControllerPlugin(Inflector::camelize($details['controller'])) . ".parts." . $this->Sbc->getControllerPart(Inflector::camelize($details['controller'])) . ".controller.actions.$admin.index.options.icon") . "\"></i> ' . " . $title . "," . $this->url('index', $details['controller']) . ", array('title'=>" . $title . ", 'escape'=> false));?>\n";
					}
					if ($this->canDo('add', null, $details['controller'])) {
						$current_controller_actions = 1;
						$title = $this->iString('New ' . strtolower(Inflector::humanize(Inflector::singularize($details['controller']))));
						$current_toolbar[] = "<?php echo \$this->Html->Link('<i class=\"fa fa-" . $this->Sbc->getConfig("plugins." . $this->Sbc->getControllerPlugin(Inflector::camelize($details['controller'])) . ".parts." . $this->Sbc->getControllerPart(Inflector::camelize($details['controller'])) . ".controller.actions.$admin.add.options.icon") . "\"></i> ' . " . $title . "," . $this->url('add', $details['controller']) . ", array('title'=>" . $title . ", 'escape'=> false));?>\n";
					}
					// Creating toolbar for controller and adding to the /global/ toolbar
					if ($current_controller_actions == 1) {
						$toolbar.=$this->v_newDropdownButton("'<i class=\"fa fa-" . $this->Sbc->getConfig("plugins." . $this->Sbc->getControllerPlugin(Inflector::camelize($details['controller'])) . ".parts." . $this->Sbc->getControllerPart(Inflector::camelize($details['controller'])) . ".options.icon") . "\"></i> '." . $this->iString(ucfirst(strtolower(Inflector::humanize($details['controller'])))), $current_toolbar, $btnSize);
						// Incrementing the number of elements in row
//						$tbRowElements++;
					}
					$done[] = $details['controller'];
					$toolbarElements++;
//					// Row management
//					if ($tbRowElements == $actionsPerRow) {
//						$toolbar.= $this->v_newRow('close');
//						$tbRowElements = -1;
//					}
				}
			}
		}
//		// Row management
//		if ($tbRowElements > -1) {
//			$toolbar.=$this->v_newRow('close');
//		}
		// Toolbars wrapping
		if ($toolbarElements > 0) {
			echo "<div class=\"toolbar\">\n\t<strong><?php echo " . $this->iString('Tools:') . ";?></strong>\n$toolbar\n</div>\n";
		}
	}
}
?>