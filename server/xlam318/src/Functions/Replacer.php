<?php

namespace Src\Functions;

class Replacer
{
    private $replacement;
    public function __construct(array $replacement)
    {
        $this->replacement = $replacement;
    }
    private function replaceLine($line)
    {
        foreach ($this->replacement as $key => $value) {
            if (stristr($line, $key)) {
                return $value;
            }
        }
        return $line;
    }
    public function replace()
    {
        if (file_exists(CONFIG_PATH)) {
            $data = file(CONFIG_PATH);
            $data = array_map(array($this, 'replaceLine'), $data);
            file_put_contents(CONFIG_PATH, $data);
            return true;
        } else {
            return false;
        }
    }
}
