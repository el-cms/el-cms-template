<?php

/**
 * Flash messages template
 *
 * Options:
 * ========
 *  - alertClass     string|null*          CSS class of message
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Common
 * @version       0.3
 */
/* ----------------------------------------------------------------------------
 *
 * Options
 *
 * -------------------------------------------------------------------------- */
if (!isset($alertClass)) {
	$alertClass = null;
}
/* ----------------------------------------------------------------------------
 *
 * Element
 *
 * -------------------------------------------------------------------------- */

switch ($alertClass) {
	case 'error':
		$icon = 'warning-sign';
		$class = "danger";
		break;
	case 'warning':
		$icon = 'warning-sign';
		$class = "warning";
		break;
	case 'success':
		$icon = 'check';
		$class = "success";
		break;
	default:
		$icon = 'info-sign';
		$class = "info";
		break;
}
$content = $this->v_icon($icon)
				. "<?php
	if (is_array(\$message)) {
		echo \$this->Html->nestedList(\$message);
	} else {
		echo \$message;
	}
	?>";

echo $this->v_alert($content, $alertClass, true);
