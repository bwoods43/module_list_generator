<?php

namespace Drupal\module_list_generator;

use Symfony\Component\Process\Process;

class ComposerInspector {

  protected $rootPath;

  public function __construct(string $rootPath) {
    // Inject or pass the Drupal root directory (DRUPAL_ROOT)
    $this->rootPath = $rootPath;
  }

  /**
   * Runs 'composer show [package_name]' to fetch details.
   * Leave blank to list all installed packages.
   */
  public function showPackageInfo(string $package = ''): string {
    $command = ['composer', 'show', '--name-only'];
    
    if (!empty($package)) {
      $command[] = $package;
    }

		// remove /web from root path because the composer file sits outside of it
    $process = new Process($command, str_replace("/web", "", $this->rootPath));
    $process->run();

    if (!$process->isSuccessful()) {
      throw new \RuntimeException($process->getErrorOutput());
    }

    return $process->getOutput();
  }
}