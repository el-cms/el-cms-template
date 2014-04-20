<?php
/**
 * Pagination links for index views
 * 
 *
 * This file mus be included once in all of your template views, as it defines
 * some vars used in the process (and creates the copyright header)
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
?>
<div class="text-center">
	<ul class="pagination">
		<?php echo "<?php
		if (\$this->Paginator->hasPrev()):
			echo \$this->Paginator->first('<i class=\"fa fa-angle-double-left\"></i>', array('tag' => 'li', 'escape' => false));
			echo \$this->Paginator->prev('<i class=\"fa fa-angle-left\"></i>', array('tag' => 'li', 'escape' => false));
		else:
			?>"; ?>
		<li class="disabled"><span><i class="fa fa-angle-double-left"></i></span></li>
		<li class="disabled"><span><i class="fa fa-angle-left"></i></span></li>
		<?php echo "<?php
		endif;
		echo \$this->Paginator->numbers(array(
			'tag' => 'li',
			'modulus' => '4',
			'separator' => '',
			'currentClass' => 'active',
		));
		if (\$this->Paginator->hasNext()):
			echo \$this->Paginator->next('<i class=\"fa fa-angle-double-left\"></i>', array('tag' => 'li', 'escape' => false));
			echo \$this->Paginator->last('<i class=\"fa fa-angle-left\"></i>', array('tag' => 'li', 'escape' => false));
		else:
			?>
			<li class=\"disabled\"><span><i class=\"fa fa-angle-right\"></i></span></li>
			<li class=\"disabled\"><span><i class=\"fa fa-angle-double-right\"></i></span></li>
<?php endif; ?>"; ?>
	</ul>
	<br />
	<small class="hidden-sm"><?php echo "<?php echo \$this->Paginator->counter('Page {:page}/{:pages}') ?>"; ?></small>
</div>