<?php
/**
 * Language bar
 *
 * Options:
 * ========
 * No option
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Files
 * @version       0.3
 */
?>
<div class="btn-group">
	<?php echo "<?php
	\$baseUrl = array(
		'admin' => false,
		'plugin' => \$this->request->plugin,
		'controller' => \$this->request->controller,
		'action' => \$this->request->action,
	);
	// passed params
	foreach(\$this->request->pass as \$named){
		\$baseUrl[]=\$named;
	}
	foreach (Configure::read('Config.languages') as \$code => \$language) { // show links for translated version
		\$baseUrl['language'] = \$code;
		echo \$this->Html->link(\$this->Html->image('flags/' . \$code . '.gif', array('class' => (Configure::read('Config.language') == \$code) ? 'language-selected' : '')), \$baseUrl, array('escape' => false, 'class' => 'btn btn-xs btn-navbar'));
	}
	?>"?>
</div>&nbsp;