<?php
/**
 * "View" action for El-CMS
 *
 * Options:
 * ========
 *  - recursiveDepth      int, 0*             Default find depth for associations
 *  - conditions          array|null*         List of conditions for an item to be deleted
 *  - layout              string, null*       Alternative layout
 *
 * Other:
 * ======
 *  Nothing
 *
 * @copyright     Copyright 2012, Manuel Tancoigne (http://experimentslabs.com)
 * @author        Manuel Tancoigne <m.tancoigne@gmail.com>
 * @link          http://experimentslabs.com Experiments Labs
 * @license       GPL v3 (http://www.gnu.org/licenses/gpl.html)
 * @package       ELCMS.superBake.Templates.Elabs.Actions
 * @version       0.3
 */
/* ----------------------------------------------------------------------------
 *
 * Options
 *
 * --------------------------------------------------------------------------*/
// Include common options
include 'common/common_options.ctp';

/* ----------------------------------------------------------------------------
 * Current action options:
 */
// Default recursive find
$recursiveDepth = (!isset($options['recursiveDepth'])) ? 0 : $options['recursiveDepth'];

//// Internationalized fields (for field selections. If empty, select all fields)
//$languageFields = (!isset($options['languageFields'])) ? array() : $options['languageFields'];

// Conditions (for paginate)
$conditions = (!isset($options['conditions'])) ? array() : $options['conditions'];

/* ----------------------------------------------------------------------------
 *
 * Action
 *
 * --------------------------------------------------------------------------*/
?>

/**
* <?php echo $admin . $a ?> method from snippet <?php echo __FILE__ ?>
*
* @throws NotFoundException
* @param string $id
* @return void
*/
public function <?php echo $admin . $a ?>($id = null) {
<?php
	// Support for a different layout. Look at the snippet for more info.
	include $themePath . 'actions/snippets/layout_support.ctp';

	// Fields
	$findFields='';
//	if ($this->Sbc->getConfig('theme.language.useLanguages') === true):
//		// Language fields should be set in config, so we check
//		if (count($languageFields) === 0):
//			$this->speak(__d('superBake', ' - No languageField defined. All fields will be returned.'), 'warning');
//		else:
//			? >
//			$lang = Configure::read('Config.language');
//			$fallback = Configure::read('website.defaultLang');
//			//<?php
//			$findFields.="\t\t\t'fields' => array(\n\t\t\t\t// Fallback\n";
//			foreach ($languageFields as $l):
//				'_' . $this->Sbc->getConfig('theme.language.fallback');
//				$findFields.="\t\t\t\t'$currentModelName.{$l}_' . \$fallback . ' as {$l}_default',\n";
//				$findFields.="\t\t\t\t'$currentModelName.{$l}_' . \$lang . ' as $l',\n";
//				foreach ($this->Sbc->getConfig('theme.language.available') as $lang):
//					unset($fields[$l . '_' . $lang]);
//				endforeach;
//			endforeach;
//
//			// Other fields:
//			$findFields.="\t\t\t\t// Other fields\n";
//			foreach ($fields as $f => $fConfig):
//				$findFields.="\t\t\t\t'$currentModelName.$f',\n";
//			endforeach;
//			// Related fields
//			$findFields.="\t\t\t// BelongsTo fields\n";
//			foreach ($modelObj->belongsTo as $f => $fConfig):
//				// Finding fields params:
//				$fElements = explode('.', $fConfig['className']);
//				// Plugin and model
//				if (count($fElements) > 1):
//					$fPlugin = $fElements[0] . '.';
//					$fModel = $fElements[1];
//				else:
//					$fPlugin = null;
//					$fModel = $fElements[0];
//				endif;
//
//				// Fields
//				App::uses($fModel, $fPlugin . 'Model');
//				if (!class_exists($fModel)):
//					$this->speak(__d('superBake', 'You should already have baked the controller dependencies (linked models) to build this method with the current options set. Please try again.'), 'error', 0, 1, 2);
//					$this->_stop();
//				endif;
//				$lModel = ClassRegistry::init($fModel);
//				$displayField = $lModel->displayField;
//				$primaryKey = $lModel->primaryKey;
//
//				$findFields.="\t\t\t\t'$fModel.$primaryKey',\n";
//				$findFields.="\t\t\t\t'$fModel.$displayField',\n";
//			endforeach;
//			$findFields.="\t\t\t),\n";
//		endif;
//	endif;
	// Conditions
	$findConditions = '';
	if (count($conditions) > 0):
		foreach ($conditions as $k => $v):
			/**
			 * @todo: replace this with $this->c_indexConditions($v). Find a way to access the Sbc class from Theme class.
			 */
			switch($v):
				case '%self%':
					if($this->isComponentEnabled('Auth')):
						$userId=Inflector::singularize(Inflector::tableize($this->Sbc->getConfig('theme.components.Auth.userModel'))).'_'.$this->Sbc->getConfig('theme.components.Auth.userModelPK');
						$findConditions.="'$k' => \$this->Session->read('Auth.".$this->Sbc->getConfig('theme.components.Auth.userModel').".".$this->Sbc->getConfig('theme.components.Auth.userModelPK')."'),\n";
					endif;
					break;
				default:
					$findConditions.="'$k' => " . $this->c_indexConditions($v) . ",\n";
					break;
			endswitch;
		endforeach;
	endif;
?>
	$options = array(
		'conditions' => array(
			'<?php echo $currentModelName; ?>.' . $this-><?php echo $currentModelName; ?>->primaryKey => $id,
			<?php echo $findConditions?>
			),
		<?php echo $findFields;?>);
	$<?php echo lcfirst($currentModelName); ?>Data = $this-><?php echo $currentModelName; ?>->find('first', $options);
	if (empty($<?php echo lcfirst($currentModelName); ?>Data)) {
		throw new NotFoundException(<?php echo $this->iString('Invalid ' . strtolower($singularHumanName)) ?>);
	}
	$this->set('<?php echo $singularName; ?>', $<?php echo lcfirst($currentModelName); ?>Data);
	<?php
	$fieldToDisplay=(!empty($modelObj->displayField)) ? 'displayField' : 'primaryKey';
	?>
	$this->set('title_for_layout', <?php echo $this->iString(ucfirst(Inflector::singularize(Inflector::humanize(Inflector::underscore($currentModelName)))) . ': %s', '$' . lcfirst($currentModelName) . "Data['$currentModelName'][\$this->${currentModelName}->$fieldToDisplay]"); ?>);
}
