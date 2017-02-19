<?php
	class csv {
	
		private	$_file,
			$_roles = array();
		
		public $fields,
			$data = array();
			
		public function __construct($file, $fields) {
			$this->_file = $file;
			$this->fields = $fields;
			$this->setFields();
			$this->import();
		}
		
		private function import() {
			// Get the posted data.
			
			$csv = $this->_file['tmp_name'];
			
			$header = true;
			$handle = fopen($csv,"r");
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				// Stores each csv line to an array
				// Check if header line
				if ( !$header ) {
					$i=0;
					$table = $this->getTable($heads[$i]);
					for ($i=0; $i < count($data); $i++) {
						$table = $this->getTable($heads[$i]);
						$field = $heads[$i];
						if ($table != '') {
							if ($table == 'users-roles') {
								$user[$table][$heads[$i]] = $data[$i] != 0 ? $this->getRoleID($heads[$i]) : $data[$i];
							} else {
								$user[$table][$heads[$i]] = $data[$i];
							}
						}
					}
					array_push($this->data, $user);
					
				}
				else {
					$heads = $data;
					$header = false;
				}
			}

		}
		
		private function getTable($field) {
			foreach ($this->fields as $table => $fieldnames) {
				if(in_array($field, $fieldnames)) return $table;
			}
		}
		
		private function setFields() {
			foreach ($this->fields['users-roles'] as $key=>$field) {
				unset($this->fields['users-roles'][$key]);
				$this->fields['users-roles'][] = $key;
				$this->_roles[$key] = $field['ID'];
			}
		}
		
		private function getRoleID($roleName) {
			return $this->_roles[$roleName];
		}
		
	}
?>
