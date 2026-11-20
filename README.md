Module List Generator (module_list_generator)
=========
This module's purpose is to generate a text list of module information that is compiled on a spreadsheet containing multiple sites. The master module list is stored in a text custom block called "Module list." The block is not recreated on install to allow any necessary customizations. The module list should contain only the machine name, and each module machine name should be separated by a space, making it easy to copy and paste directly into source.

The default output produced by the drush command custom:generateModuleList shows the full list based on the modules included in the custom block. The modules on a particular site can be enabled ("Y"), uninstalled but available ("N") or non-existent ("N/A").
