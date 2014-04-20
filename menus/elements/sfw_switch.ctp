<?php

/**
 * Menu switch used if theme.useSFW is true.
 *
 * This switch will display or hide NSFW content. It's included in some menus.
 *
 * Options:
 * ========
 * Configuration from theme:
 *  - useSFW
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Menus
 * @version       0.3
 */

// Use of SWF states in items
$useSFW = $this->Sbc->getConfig('theme.useSFW');

if ($useSFW):
		?>
		<li class="dropdown">
			<?php echo "<?php\n" ?>
			if ($seeNSFW === true) {
				$swfIcon = '<i class="fa fa-lock"></i> ' . __('Hide NSFW');
				$sfwLink = 'hide';
			} else {
				$swfIcon = '<i class="fa fa-unlock-alt"></i> ' . __('Show NSWF');
				$sfwLink = 'show';
			}
			echo $this->Html->link($swfIcon, array_merge($baseURL, array('switchNSFW' => $sfwLink)), array('escape' => false));
			<?php echo "?>";?>
		</li>

		<?php
	endif;