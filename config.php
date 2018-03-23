<?php

return [
    /**
     * $geshi->enable_class();
     */
    'enable_classes' => true,
    /**
     * Callback invoked when Geshi object is created.
     *
     * Allows additional configuration according to Geshi documentation.
     *
     * @param GeSHi $geshi - Geshi object to configure.
     * @return void
     */
    'geshiConfigurator' => function (\Geshi $geshi) {
        $geshi->set_tab_width(2);
    }
];
