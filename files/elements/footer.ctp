<?php
/**
 * Footer bar for layouts.
 *
 * Options:
 * ========
 * Options from theme:
 *   - language.uselanguage
 *
 * Other:
 * ======
 * AuthComponent should enabled to create a login/admin link
 *
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Files
 * @version       0.3
 */
/* ----------------------------------------------------------------------------
 *
 * Options
 *
 * --------------------------------------------------------------------------*/

// Use the languages or not:
$useLang = $this->Sbc->getConfig('theme.language.useLanguages');

// Use Auth (for footer links to admin)
$useAuth = $this->isComponentEnabled('Auth');

/* ----------------------------------------------------------------------------
 *
 * Element
 *
 * --------------------------------------------------------------------------*/
?>

<!--nocache-->
<?php
if ($useAuth):
	echo "<?php
					if (is_array(\$this->Session->read('Auth.".$this->Sbc->getConfig('theme.components.Auth.userModel')."'))) {
						echo \$this->Html->Link('Admin', array('admin' => 'admin', 'plugin' => 'blog', 'controller' => 'posts', 'action' => 'index'), array('class' => 'btn btn-xs btn-warning'));
					}?>\n";
endif;
if ($useLang):
	echo "<?php echo \$this->element('lang-bar')?>\n";
endif;
?>&nbsp;
<!--/nocache-->
&copy; <?php echo date('Y');?> - <?php echo $this->Sbc->getConfig('general.siteName') ?> /
<?php
if ($useAuth):
	//Getting vars
	$userModel = $this->Sbc->getConfig('theme.components.Auth.userModel');
	$llController = Inflector::underscore(Inflector::pluralize($userModel));
	$llAction = $this->Sbc->getConfig('theme.components.Auth.loginAction');
	$llPlugin = $this->getControllerPluginName($llController);
	$llPlugin = ($llPlugin === null || empty($llPlugin)) ? "null" : "'$llPlugin'";
	echo "<?php echo \$this->Html->link(" . $this->iString('Login') . ", array('admin' => null, 'plugin' => $llPlugin, 'controller'=> '$llController', 'action' => '$llAction'));?> / ";
endif;
?>
<i class="fa fa-cog"></i> <?php echo "<?php echo \$this->Html->link('Manu', array('admin' => null, 'plugin' => null, 'controller' => 'pages', 'action' => 'display', 'manu')) ?>"; ?>