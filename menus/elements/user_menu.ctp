<?php
/**
 * Users menu that displays links for the current user.
 *
 * Options:
 * ========
 *  - isPublicMenu       bool, false*      If set to true, this is meant to be used in public menus,
 *                                         so there is a check to see if an user is logged in.
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Menus
 * @version       0.3
 */
// Users menu, if logged in
if ($isPublicMenu):
	echo "<?php if (is_array(\$this->Session->read('Auth.User'))) { ?>\n";
endif;

// Gravatar support
$userIcon=($this->Sbc->getConfig('theme.gravatar.useGravatar'))?"<?php echo \$this->Html->image(\$this->Html->gravatar(\$this->Session->read('Auth.User.email'), 25), array('class'=>'nav-avatar'))?>":"<i class=\"fa fa-user\"></i>";
?>
<li class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo "$userIcon <?php echo \$this->Session->read('Auth.User.username') ?>" ?> <b class="caret"></b></a>
	<ul class="dropdown-menu">
		<li><?php echo "<?php echo \$this->Html->link(" . $this->iString('Dashboard') . ", " . $this->url('dashboard', 'Users', 'user') . '); ?>' ?></li>
		<li><?php echo "<?php echo \$this->Html->link(" . $this->iString('Profile') . ", " . $this->url('view', 'Users', 'user', "\$this->Session->read('Auth.User.id')") . '); ?>' ?></li>
		<li><?php echo "<?php echo \$this->Html->link(" . $this->iString('Log out') . ", " . $this->url('logout', 'Users', 'public') . '); ?>' ?></li>
	</ul>
</li>
<?php
if ($isPublicMenu):
	echo "<?php\n}\n?>";
endif;
?>