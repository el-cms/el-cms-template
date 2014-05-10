<?php
/**
 * Roles model modifications for EL-CMS
 *
 * This file contains methods and vars used in the Group model to make it works
 * as requester (for ACLs)
 *
 * Options:
 * ========
 * None
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Models
 * @version       0.3
 */
?>
	/**
	 * Binds <?php echo $name ?> to nothing
	 * @return null
	 */
	public function parentNode() {
		return null;
	}