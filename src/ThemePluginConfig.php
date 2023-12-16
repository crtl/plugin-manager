<?php

class ThemePluginConfig
{

    protected function __construct(
        protected string $file,
        protected bool $hasConfig = false,
        protected array $config = [],
    ) {}


    public static function loadFromTheme(string $directory) {
        $file = rtrim($directory, "/") . "/wp-plugins.json";

        $hasConfig = file_exists($file);
        $config = $hasConfig ? json_decode(file_get_contents($file), true) : [];

        return new self($file, $hasConfig, $config);
    }


    public function addPlugin() {

    }

    public function removePlugin() {

    }

    public function updatePlugin() {

    }

}