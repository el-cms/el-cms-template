<?php
/**
 * Menu template for El-CMS
 *
 * Options:
 * ========
 *  - hiddenPlugins              array					List of plugins to remove from menu
 *  - hiddenControllers          array					List of controllers to remove from menu
 *  - hiddenControllersActions   array					List of actions to remove from some controllers
 *  - hiddenActions              array					List of actions to remove
 *  - isPublicMenu               bool, false    Define if this menu is public or not.
 *  - prefixes                   array          List of prefixes to add in menu
 *  - haveSfwSwitch              bool, false    Defines if the menu have a SFW switch
 *  - haveUserMenu               bool, false    Defines if the menu have a User menu
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Menus
 * @version       0.3
 */
/* ----------------------------------------------------------------------------
 *
 * Options
 *
 * --------------------------------------------------------------------------*/

// Hidden plugins
if (!isset($hiddenPlugins) || !is_array($hiddenPlugins)) {
	$hiddenPlugins = array();
}
// Hidden controllers
if (!isset($hiddenControllers) || !is_array($hiddenControllers)) {
	$hiddenControllers = array();
}
// Hidden actions in controllers
if (!isset($hiddenControllerActions) || !is_array($hiddenControllerActions)) {
	$hiddenControllerActions = array();
}
// Globally hidden actions
if (!isset($hiddenActions) || !is_array($hiddenActions)) {
	$hiddenActions = array();
}

// Is the menu the main public menu ?
// Use this option to do specific things for public menu
// I use it to run Auth check on public menus to display or not the logged-in user links
if (!isset($isPublicMenu)) {
	$isPublicMenu = false;
}

// Safe for Work switch
if(!isset($haveSfwSwitch)){
	$haveSfwSwitch=false;
}
// User menu
if(!isset($haveUserMenu)){
	$haveUserMenu=false;
}

// Getting all actions
$menu = $this->Sbc->getActionsAll();

/* ----------------------------------------------------------------------------
 *
 * Menu
 *
 * --------------------------------------------------------------------------*/

// plugins
?>
<div class="collapse navbar-collapse">
	<ul class="nav navbar-nav">
		<?php
		foreach ($menu as $plugin => $pluginConfig):
			if (!in_array($plugin, $hiddenPlugins)):
				?>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-<?php echo $this->Sbc->getConfig("plugins.$plugin.options.icon") ?>"></i> <?php echo "<?php echo " . $this->iString($pluginConfig['displayName']) . "?>" ?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<?php
						//Controllers
						foreach ($pluginConfig['controllers'] as $controller => $controllerConfig):
							if (!in_array($controller, $hiddenControllers)):
								?>
								<li role="presentation" class="dropdown-header"><i class="fa fa-<?php echo $this->Sbc->getConfig("plugins.$plugin.parts." . $this->Sbc->getControllerPart($controller) . ".options.icon") ?>"></i> <?php echo "<?php echo " . $this->iString($controllerConfig['displayName']) . "?>" ?></li>
									<?php
									// Prefixes
									foreach ($controllerConfig['prefixes'] as $prefix => $actions):
										// Adding controller to hidden controllers to avoid index notices later.
										if (!isset($hiddenControllerActions[$controller])):
											$hiddenControllerActions[$controller] = array();
										endif;
										// $prefixes is one option from the config file.
										if (in_array($prefix, $prefixes)):
											// Actions
											foreach ($actions as $action):
												if (!in_array($action, $hiddenActions) && !in_array($action, $hiddenControllerActions[$controller])):
													switch ($action):
														case 'index':
															$actionName = "List " . strtolower($controllerConfig['displayName']);
															break;
														case 'add':
															$actionName = "New " . strtolower(Inflector::singularize($controllerConfig['displayName']));
															break;
														case 'register':
															$actionName = 'Register';
															break;
														case 'login':
															$actionName = 'Log in';
															break;
														case 'logout':
															$actionName = 'Log out';
															break;
														default:
															$actionName = Inflector::humanize(Inflector::underscore($action)) . ' ' . $controllerConfig['displayName'];
															break;
													endswitch;
													echo "<li><?php echo \$this->Html->link(" . $this->iString($actionName) . ', ' . $this->url($action, $controller, $prefix) . "); ?></li>\n";
												endif;
											endforeach;
											// SuperBake menu, adding versions for different prefixes in it
											if ($plugin == 'Sb') {
												echo '<li role="presentation" class="dropdown-header"><i class="fa fa-plus"></i> Other actions</li>';
												// Getting prefixes not shown on this page
												$prefixesList = array_diff($this->Sbc->getPrefixesList(), $prefixes);
												foreach ($prefixesList as $prefix) {
													echo "<li><?php echo \$this->Html->link(" . $this->iString(ucfirst($prefix) . ' site') . ", " . $this->url('index', 'Posts', $prefix) . ")?></li>\n";
												}
											}
										endif;
									endforeach;
							endif;
							endforeach;
							?>
					</ul>
				</li>
				<?php
			endif;
		endforeach;

		// SFW switch
		if($haveSfwSwitch):
			include 'elements/sfw_switch.ctp';
		endif;
		// Users menu
		if($haveUserMenu):
			include 'elements/user_menu.ctp';
		endif;
		?>
	</ul>
</div>