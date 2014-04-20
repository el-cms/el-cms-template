<?php
/**
 * Main layout.
 *
 * Particularities:
 * ================
 *		- A colored line appears under the menu, and the color changes with the prefix
 *			(css: #<prefix>-line)
 *		- Border color of the main container can change on different plugins:
 *			(css: .container-<plugin>, .container-other). To use this, set
 *			'<file>.options.usePluginColors: true' in config and bake.
 *
 * Don't forget the css.
 *
 * Options:
 * ========
 *  - usePluginColors     true,false*         If enabled, an HTML class with the name of the current plugin will be added for the content container.
 *  - userLine            string,'public'*    CSS class of the "user line", which is under the main navbar.
 *  - style               string,'style'*     Default CSS file to use in template
 *  - menu                string,$userLine*   Menu element name to include as menu
 *
 * Other options:
 * ==============
 *  - If AuthComponent is enabled, a login link will be created in footer
 *  - If theme.languages.useLanguages = true, language links will be made in footer
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
// Specific colors depending on the plugin:
if(!isset($usePluginColors)){
	$usePluginColors=false;
}

// User line is a colored line under the navbar showing which prefix is crrently used.
if (!isset($userLine)) {
	$userLine = 'public';
}

// Default CSS
if(!isset($style)){
	$style='style';
}
// Menu
if (!isset($menu)) {
	$menu = $userLine;
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
		<!--<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
					<?php echo "<?php echo \$this->Html->link('" . $this->Sbc->getConfig('general.siteName') . "', '/', array('class'=>'navbar-brand'));?>"; ?>
        </div>

        <div class="collapse navbar-collapse">
					<?php echo "<?php echo \$this->element('menus/$menu');?>" ?>
        </div>
      </div>

			<div id="<?php echo $userLine ?>-line"></div>

    </div>

    <div class="container<?php echo (($usePluginColors) ? " container-<?php echo (!empty(\$baseURL['plugin']))?\$baseURL['plugin']:'other' ?>" : '') ?>" id="page-content">
			<div class="row">
				<div class="col-lg-12">
					<?php echo "<?php echo \$this->Session->flash(); ?>\n" ?>
					<h1 class="main-title"><?php echo "<?php echo \$title_for_layout; ?>"; ?></h1>
					<?php echo "<?php echo \$this->fetch('content'); ?>\n" ?>
				</div>
			</div>
    </div>

		<nav class="navbar navbar-default navbar-fixed-bottom" id="footer" role="navigation">
			<div class="container">
				<?php echo "<?php echo \$this->element('footer') ?>\n" ?>
			</div>
		</nav>
    <!--
		<! Bootstrap core JavaScript
    <! ==================================================
    <! Placed at the end of the document so the pages load faster
		<! -->
		<?php echo "<?php echo \$this->Html->script('bootstrap.min'); ?>" ?>
		<script>
			$("[data-toggle='tooltip']").tooltip();
		</script>
  </body>
</html>
