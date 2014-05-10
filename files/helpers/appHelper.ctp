<?php
/**
 * AppHelper file containing some methods to be used in views:
 *  - url() - For links with langauge support
 *  - obfuscate() - to obfuscate email adresses
 *  - gravatar() - to display gravatars
 *
 * Options:
 * ========
 * Options from theme:
 *  - useGravatars
 *  - languages.useLanguages
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Files
 * @version       0.3
 */

echo '<?php';?>

App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper {

	<?php
	if($this->Sbc->getConfig('theme.languages.useLanguages')===true):
	?>

	/**
	 * Replaces the CakePHP url method to add language settings in links.
	 *
	 * @param array|string $url The url to transform
	 * @param boolean $full
	 *
	 * @return string
	 */
	function url($url = null, $full = false) {
		$lang = Configure::read('Config.language');
		if (is_array($url) && !isset($url['language'])) {
			$url['language'] = $lang;
		}
		return parent::url($url, $full);
	}
	<?php
	endif;
	?>

	function obfuscate($email, $itag = true) {
		if ($itag == true) {
			$tag_open = '<i>';
			$tag_close = '</i>';
		} else {
			$tag_open = '_';
			$tag_close = '_';
		}
		$email = str_replace('@', ' ' . $tag_open . __('AT') . $tag_close . ' ', $email);
		$email = str_replace('.', ' ' . $tag_open . __('DOT') . $tag_close . ' ', $email);
		return ($email);
	}

	<?php
	if($this->Sbc->getConfig('theme.gravatar.useGravatar')):
		?>
	/**
	 * From Gravatar Help:
	 *        "A gravatar is a dynamic image resource that is requested from our server. The request
	 *        URL is presented here, broken into its segments."
	 *
	 * @author Lucas Araújo <araujo.lucas@gmail.com>
	 * @version 1.0
	 * @package Gravatar
	 *
	 * @param string $email Email
	 * @param int $s Gravatar size
	 * @param string $d Default image
	 * @param char $r Default rating
	 * @param type $img ?? Seems not used
	 * @param array $atts Array of options. i.e.: array('border'=>'none')
	 * @return string Img tag
	 */
	public function gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5(strtolower(trim($email)));
		$url .= "?s=$s&amp;d=$d&amp;r=$r";
		if ($img) {
			$url = '<img src="' . $url . '"';
			foreach ($atts as $key => $val)
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}
	<?php
	endif;
	?>

}
