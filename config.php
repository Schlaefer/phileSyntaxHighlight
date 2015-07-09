<?php

return [
    /**
     * Callback invoked when Geshi object is created.
     *
     * Allows configuration according to Geshi documentation.
     *
     * @param GeSHi $geshi - Geshi object to configure.
     * @return void
     */
  'geshiConfigurator' => function ($geshi) {
      $geshi->set_tab_width(2);
      $geshi->enable_classes();
  }
];
