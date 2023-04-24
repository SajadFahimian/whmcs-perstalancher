<?php

namespace Src\Functions;

class Replacer
{
    private $path;
    private $replacement;
    public function __construct(String $path, Array $replacement)
    {
        $this->path = dirname($path, 1) . getenv('CONFIG_PATH');
        $this->replacement = $replacement;
    }
    private function replaceLine($line)
    {
        foreach ($this->replacement as $key => $value) {
            if(stristr($line, $key)) {
                return $value;
            }
        }
        return $line;
    }
    public function replace() {
        if (file_exists($this->path)) {
            $data = file($this->path);
            $data = array_map(array($this, 'replaceLine'), $data);
            file_put_contents($this->path, $data);
            return true;
        } else {
            return false;
        }
    }
}
