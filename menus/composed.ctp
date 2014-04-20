<?php
/**
 * Menu template for SuperBake
 *
 * This template is a composed menu file: it will contain only defined thing, so
 * if you create plugins/controllers/actions undefined in th menu options, they
 * won't be created.
 *
 * you should define your menu as follow:
 * [menuname]:
 *   options:
 *     elements:
 *       Blog:            #plugin name
 *         Posts:         #controller name
 *           index: News  #action name
 *       %userMenu%       # Additionnal user menu (menus/elements/user_menu.ctp)
 *
 * Note that you should also define the used prefixes as for the standard menu
 * template (menu.ctp).
 *
 * Options for this menu:
 * ======================
 * From theme:
 *  - useSFW          true|false*         If true, an item to switch nsfw items will be created
 *                                        Note that the option is handled in a separate menu element
 * For the menu:
 *  - elements: List of elements
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Menus
 * @version       0.3
 */

?>
<div class="collapse navbar-collapse">
	<ul class="nav navbar-nav">
	<?php
	foreach ($elements as $element => $c):
		switch ($c['t']):
			// CakePHP url:
			case 'cakeUrl':
				$pluginName = Inflector::underscore($this->Sbc->getPluginName($c['p']));
				$prefixName = $this->getActionPrefix($c['a']);
				$actionName = $this->getActionName($c['a']);
				$optionsList='';
				if(!empty($c['o'])):
					foreach($c['o'] as $k=>$v):
					$optionsList.=', ';
						if(!empty($v)):
							$optionsList.="'$k' => $v, ";
						else:
							$optionsList.="'$k',";
						endif;
					endforeach;
				endif;
				echo "\t<li>"
				. "<?php echo \$this->Html->link('"
				. $this->v_icon($this->Sbc->getConfig("plugins.${c['p']}.options.icon"))."'."
				. $this->iString($element) . ","
				. " array('plugin'=>" . (is_null($pluginName) ? 'null' : "'" . $pluginName . "'") . ","
				. "'admin'=>" . (is_null($prefixName) ? 'false' : "'" . $prefixName . "'") . ","
				. "'controller'=> '" . Inflector::underscore($c['c']) . "',"
				. "'action'=>'$actionName'$optionsList), array('escape'=>false)); ?>"
				. "</li>\n";
				break;
			case 'text':
				echo "\t<li><?php echo \$this->Html->link("
				. ((!empty($c['icon']))?"'".$this->v_icon($c['icon'])."' . ":'')
				.$this->iString($element) . ", '{$c['url']}', array('escape' => false, 'target' => '_blank')"
				.");?></li>\n";
			case 'sfwSwitch':
				include 'elements/sfw_switch.ctp';
				break;
			case 'userMenu':
				$isPublicMenu = (!empty($c['mustBeLoggedIn']))?$c['mustBeLoggedIn']:false;
				include 'elements/user_menu.ctp';
				break;
			default:
				break;
		endswitch;
	endforeach;
	?>

	</ul>
</div>