<?php

	Class extension_navigationicons extends Extension {

		private $navItems; // Will contain an array of the admin navigation groups

		public function getSubscribedDelegates() {
			return array(
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'InitaliseAdminPageHead',
					'callback'	=>	'appendAssets'
				),
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'NavigationPreRender',
					'callback'	=> 'parseNav'
				),
				array(
					'page'	=> '/system/preferences/',
					'delegate'	=> 'AddCustomPreferenceFieldsets',
					'callback'	=> 'appendPreferences'
				),
				array(
					'page'	=> '/system/preferences/',
					'delegate'	=> 'Save',
					'callback'	=> 'savePreferences'
				)
			);
		}

	/*-------------------------------------------------------------------------
		Install:
	-------------------------------------------------------------------------*/

		public function install() {
			// Set default icons
			// The 3 included in the webfont with this extension are the dashboard, puzzle piece (blueprints) and settings (system) icons.
			// Saved to the config as a JSON-encoded array
			$config = static::encode(array('Blueprints' => 'blueprints', 'System' => 'system', 'Dashboard' => 'dashboard'));
			Symphony::Configuration()->set('navigation', $config, 'navigationicons');
			Symphony::Configuration()->write();
		}

		public function uninstall() {
			Symphony::Configuration()->remove('navigationicons');
			Symphony::Configuration()->write();
		}

	/*-------------------------------------------------------------------------
		Utils:
	-------------------------------------------------------------------------*/

		public static function encode($array) {
			return json_encode($array);
		}

		public static function decode($string, $asArray = false) {
			$string = stripslashes($string);
			$string = str_replace('\\', '', $string);
			$string = @json_decode($string, $asArray);
			if (!$string) {
				return array();
			}
			return $string;
		}

		private static function getAuthor() {
			$author = null;
			if (is_callable(array('Symphony', 'Author'))) {
				$author = Symphony::Author();
			} else {
				$author = Administration::instance()->Author;
			}
			return $author;
		}

	/*-------------------------------------------------------------------------
		Delegates:
	-------------------------------------------------------------------------*/

		public function appendAssets() {
			$config = static::decode(Symphony::Configuration()->get('navigation', 'navigationicons'), true);
			if (is_array($config)) {
				foreach ($config as $key => $value) {
					$config[__($key)] = __($value);
				}
			} else {
				$config = array();
			}
			$config = static::encode($config);
			$script = new XMLElement('script', __('var navigationArr = '. $config  .';'), array('type' => 'text/javascript'));
			Administration::instance()->Page->addElementToHead($script, 1000, true);
			Administration::instance()->Page->addScriptToHead(URL . '/extensions/navigationicons/assets/navigationicons.js', 1001, false);
			Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/navigationicons/assets/navigationicons.css', 'screen', 1002, false);
			// Reset the value in the array, to prevent fighting against
			// the Configuration::write method which add slashes
			Symphony::Configuration()->set('navigation', $config, 'navigationicons');
		}

		public function parseNav($context) {
			// Creates an array containing the current admin navigation groups
			$items = array();
			foreach ($context['navigation'] as &$item) {
				$items[] = $item['name'];
			}
			$this->navItems = $items;
		}

		public function appendPreferences($context) {
			$config = static::decode(Symphony::Configuration()->get('navigation', 'navigationicons'), true);

			Administration::instance()->Page->addScriptToHead(URL . '/extensions/navigationicons/assets/navigationicons.preferences.js', 4000, false);

			$fieldset = new XMLElement('fieldset');
			$fieldset->setAttribute('class', 'settings');
			$fieldset->appendChild(new XMLElement('legend', __('Navigation Icons')));
			//$fieldset->appendChild(new XMLElement('p', __(''), array('class' => 'help')));

			if (self::getAuthor()->get('language') != 'en') {
				$div = new XMLElement('div', null, array('class' => 'frame'));
				$p = new XMLElement('h2', __('You can not edit navigations icons if not in English'));
				$div->appendChild($p);
				$fieldset->appendChild($div);
				$context['wrapper']->appendChild($fieldset);
				return;
			}

			$div = new XMLElement('div', null, array('class' => 'frame'));
			$duplicator = new XMLElement('ol');
			$duplicator->setAttribute('class', 'navigationicons-duplicator');

			foreach ($this->navItems as $i=>&$item) {
				$li = new XMLElement('li');
				$li->appendChild(new XMLElement('label', $item));

				$val = array_key_exists($item, $config) ? $config[$item] : '';
				$navLabel = new XMLElement('span', $item, array('data-icon' => $val));
				$iconInput = Widget::Input('settings[navigationicons][navigation][navitem'. $i .'][]', $val, 'text', array('class' => 'navIcon', 'placeholder' => 'Ligature or symbol'));
				$labelInput = Widget::Input('settings[navigationicons][navigation][navitem'. $i .'][]', $item, 'hidden');

				$li->appendChild($navLabel);
				$li->appendChild($iconInput);
				$li->appendChild($labelInput);
				$duplicator->appendChild($li);
			}

			$div->appendChild($duplicator);
			$fieldset->appendChild($div);
			$fieldset->appendChild(new XMLElement('p', __('The default webfont included with this extension contains 3 icons: a dashboard gauge (ligature of "dashboard"), a puzzle piece (ligature of "blueprints") and a settings cog (ligature of "system"). View the <code>README</code> for an overview on generating and using custom icon sets.'), array('class' => 'help navigationiconshelp')));

			$context['wrapper']->appendChild($fieldset);
		}

		public function savePreferences(array &$context){
			$config = array();
			foreach ($context['settings']['navigationicons']['navigation'] as &$item) {
				if ($item[0] != '') $config[$item[1]] = $item[0];
			}
			//print_r($config);
			$context['settings']['navigationicons']['navigation'] = static::encode($config);
		}



	}
