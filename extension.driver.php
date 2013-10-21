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

		public function install() {
			// Set default icons
			// The 3 included in the webfont with this extension are the dashboard, puzzle piece (blueprints) and settings (system) icons.
			// Saved to the config as a JSON-encoded array
			$config = json_encode(array('Blueprints' => 'blueprints', 'System' => 'system', 'Dashboard' => 'dashboard'));
			Symphony::Configuration()->set('navigation', $config, 'navigationicons');
			Symphony::Configuration()->write();
		}

		public function uninstall() {
			Symphony::Configuration()->remove('navigationicons');
			Symphony::Configuration()->write();
		}


	/*-------------------------------------------------------------------------
		Delegates:
	-------------------------------------------------------------------------*/

		public function appendAssets() {
			$config = Symphony::Configuration()->get('navigation', 'navigationicons');
			$script = new XMLElement('script', __('var navigationArr = '. $config .';'), array('type' => 'text/javascript'));
			Administration::instance()->Page->addElementToHead($script, 1000, true);
			Administration::instance()->Page->addScriptToHead(URL . '/extensions/navigationicons/assets/navigationicons.js', 1001, false);
			Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/navigationicons/assets/navigationicons.css', 'screen', 1002, false);
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
			$config = json_decode(Symphony::Configuration()->get('navigation', 'navigationicons'));

			Administration::instance()->Page->addScriptToHead(URL . '/extensions/navigationicons/assets/navigationicons.preferences.js', 4000, false);

			$fieldset = new XMLElement('fieldset');
			$fieldset->setAttribute('class', 'settings');
			$fieldset->appendChild(new XMLElement('legend', __('Navigation Icons')));
			//$fieldset->appendChild(new XMLElement('p', __(''), array('class' => 'help')));

			$div = new XMLElement('div', null, array('class' => 'frame'));
			$duplicator = new XMLElement('ol');
			$duplicator->setAttribute('class', 'navigationicons-duplicator');

			foreach ($this->navItems as $i=>&$item) {
				$li = new XMLElement('li');
				$li->appendChild(new XMLElement('label', __($item)));

				$val = array_key_exists($item, $config) ? $config->$item : '';
				$navLabel = new XMLElement('span', $item, array('data-icon' => $val));
				$iconInput = Widget::Input('settings[navigationicons][navigation][navitem'. $i .'][]', $val, 'text', array('class' => 'navIcon', 'placeholder' => 'Ligature or symbol'));
				$labelInput = Widget::Input('settings[navigationicons][navigation][navitem'. $i .'][]', $item, 'hidden');

				$li->appendChild($iconInput);
				$li->appendChild($labelInput);
				$li->appendChild($navLabel);
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
			$context['settings']['navigationicons']['navigation'] = json_encode($config);
		}



	}
