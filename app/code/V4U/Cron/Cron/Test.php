<?php

namespace V4U\Cron\Cron;

use \Psr\Log\LoggerInterface;

class Test {

  protected $logger;

  public function __construct(LoggerInterface $logger) {

    $this->logger = $logger;

  }

  /**

    * Write to system.log

    *

    * @return void

  */

  public function execute() {

    // Do your Stuff

    $this->logger->info('Cron Works');

  }

}
