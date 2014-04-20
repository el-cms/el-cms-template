<?php
/**
 * Layout designed for login view. It has no navbar, so no menus and the main container
 * is small
 *
 * Options:
 * ========
 *  - style       string, 'style'*          Main CSS file
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Files
 * @version       0.3
 */
//Page headers and licensing
include $themePath . 'views/common/headers-files.ctp';

/* ----------------------------------------------------------------------------
 *
 * Options
 *
 * --------------------------------------------------------------------------*/
// Default CSS
if(!isset($style)){
	$style='style';
}

// Use the languages or not:
$useLang=$this->Sbc->getConfig('theme.language.useLanguages');

// Use Auth (for footer links to admin
$useAuth=$this->isComponentEnabled('Auth');

/* ----------------------------------------------------------------------------
 *
 * Layout
 *
 *---------------------------------------------------------------------------*/
?>
<!DOCTYPE html>
<html lang="<?php echo ($useLang) ? '<?php echo substr($lang, 0, 2) ?>' : substr($this->Sbc->getConfig('theme.language.fallback'), 0, 2) ?>">
	<head>
		<?php echo "<?php echo \$this->Html->charset(); ?>"; ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

		<title>
			<?php echo "<?php echo \$title_for_layout; ?>"; ?> - <?php echo $this->Sbc->getConfig('general.siteName'); ?>
		</title>
		<?php echo "<?php
		echo \$this->Html->meta('icon');

		echo \$this->Html->script('jquery-1.10.2.min.js');

		echo \$this->Html->css('$style');

		echo \$this->fetch('meta');
		echo \$this->fetch('css');
		echo \$this->fetch('script');
	?>" ?>
	</head>
	<body>
		<div class="container" id="page-content">
			<div class="row">
				<div class="login-box col-lg-4 col-lg-offset-4">
					<!--nocache-->
					<?php echo "<?php echo \$this->Session->flash(); ?>" ?>
					<!--/nocache-->
					<h1 class="main-title"><?php echo "<?php echo \$title_for_layout ;?>" ?></h1>
					<?php echo "<?php echo \$content_for_layout; ?>"; ?>
					<div class="login-footer">
						<?php echo "\n<?php echo \$this->element('footer') ?>\n" ?>
					</div>
				</div>
			</div>
		</div>
		<!--->
		<! Bootstrap core JavaScript
		<! ==================================================
		<! Placed at the end of the document so the pages load faster
		<! -->
		<?php echo "<?php echo \$this->Html->script('bootstrap.min'); ?>" ?>
	</body>
</html>
