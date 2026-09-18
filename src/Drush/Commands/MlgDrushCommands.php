<?php

namespace Drupal\module_list_generator\Drush\Commands;

use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;

/**
 * A custom Drush commandfile.
 */
final class MlgDrushCommands extends DrushCommands {

  /**
   * Generate module list
   *
   * @param string $name
   *   The name of the person to greet.
   * @param array $options
   *   An array of options passed to the command.
   */
  #[CLI\Command(name: 'custom:generateModuleList', aliases: ['gmlist'])]
	public function generateModuleList():void {

		$composer_show = \Drupal::service('module_list_generator.composer_inspector');
		$available_modules = $composer_show->showPackageInfo("drupal/*");
		$available_modules_array = explode("\n", $available_modules);
		
		// Inject the 'extension.path.resolver' service into your class, then call:
		$module_path = \Drupal::service('extension.path.resolver')->getPath('module', 'module_list_generator');
		$file_path = $module_path . '/data/master_module_list.csv';
		
		// Get master module list
    // The leading backslash ensures it looks in the global space, not the namespace.
    $module_list_array = \module_list_generator_read_csv_to_array($file_path);

		// loop through master list to find what we have
		$computed_list =[];
		foreach ($module_list_array as $key=>$value) {
			if (in_array("drupal/" . $value, $available_modules_array)) {
				$computed_list[$value] = 'N';
			} else {
				$computed_list[$value] = 'N/A';			
			}
		}

		// get enabled modules
		$enabled_modules = \Drupal::moduleHandler()->getModuleList();
		$module_machine_names = array_keys($enabled_modules);
		
		// loop through the updated list to mark yes on ones that are enabled
		$final_list = [];
		foreach ($computed_list as $key=>$value) {
			if (in_array($key, $module_machine_names)) {
				$final_list[$key] = 'Y';
			} else {
				// can't rely only on composer list because submodules do not appear there
				// 1. Check if the module code exists in the file system
				$module_exists_in_files = \Drupal::service('extension.list.module')->exists($key);
				
				// 2. Check if the module is currently enabled/installed
				$module_is_enabled = \Drupal::moduleHandler()->moduleExists($value);

				if ($module_exists_in_files && !$module_is_enabled) {
					$final_list[$key] = 'N';							
				} else {
					$final_list[$key] = $value;							
				}
			}
		}
		
		$message = '';
		// output only values
		foreach ($final_list as $value) {
    	$message .= $value . "\n";
		}
		
    $this->output()->writeln($message);	
	}
}