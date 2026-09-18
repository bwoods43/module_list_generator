Module List Generator (module_list_generator)
=========
This module's purpose is to generate a text list of module information that is compiled on a spreadsheet containing multiple sites. The master module list is stored in data/master_module_list.csv, and the modules on a particular site can be enabled ("Y"), uninstalled but available ("N") or non-existent ("N/A"). A drush command (custom:generateModuleList) produces the final list.
