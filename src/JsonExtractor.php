<?php

namespace RoNoLo\JsonExtractor;

class JsonExtractor
{
    private $string;
    private $stringOrg;

    public function __construct($string)
    {
        $this->string = $string;
    }

    /**
     * Will extract the JSON of a declaration like this:
     *
     * var foo = { ... }
     * var foo = [ ... ]
     * foo = { ... }
     *
     * It needs to have a name equals object or array.
     *
     * @param $var
     * @return string
     * @throws \Exception
     */
    public function extractVariable($var)
    {
        $startPosition = $this->findVariableStart($var);
        $endPosition = $this->findVariableEnd($startPosition, $this->string);

        $json = substr($this->string, $startPosition, $endPosition - $startPosition + 1);

        return json_decode($json, JSON_OBJECT_AS_ARRAY);
    }

    /**
     * Will extract all JSON data from all <script>...</script> tags.
     *
     * Only objects will be extracted.
     */
    public function extractAllJsonData()
    {
        $this->stringBackup();

        $scriptTags = $this->findAllScriptTags();
        $vars = $this->findAllJsonObjects($scriptTags);

        $this->stringRestore();

        return $vars;
    }

    /**
     * @param $var
     * @return bool|int
     * @throws \Exception
     */
    private function findVariableStart($var)
    {
        // Find the variable name
        $pos = strpos($this->string, $var);

        // Find the next = sign
        $pos = strpos($this->string, '=', $pos + 1);

        // Now find the very next symbol which needs to be { or [
        $strLength = strlen($this->string);
        for ($i = $pos; $i < $strLength; $i++) {
            if (in_array($this->string[$i], ['[', '{'])) {
                return $i;
            }
        }

        throw new \Exception("Could not find JSON object or array, with the given name");
    }

    /**
     * @param $startPosition
     * @return mixed
     * @throws \Exception
     */
    private function findVariableEnd($startPosition, &$string)
    {
        $symbolOpen = $string[$startPosition];
        $symbolClose = $symbolOpen === '{' ? '}' : ']';

        $stack = [];
        $stack[] = $symbolClose;

        $i = $startPosition;
        while (true) {
            $i++;

            $foo = $string[$i];

            if (!isset($string[$i])) {
                throw new \Exception("End of JSON object / array could not be found");
            }

            if ($string[$i] == $symbolOpen) {
                $stack[] = $symbolClose;
                continue;
            }

            if ($string[$i] == $symbolClose) {
                array_pop($stack);

                if (!count($stack)) {
                    return $i;
                }
                continue;
            }
        }
    }

    private function stringBackup()
    {
        $this->stringOrg = $this->string;
    }

    private function stringRestore()
    {
        $this->string = $this->stringOrg;
    }

    private function findAllScriptTags()
    {
        if (preg_match_all('#<script(.*?)>(.*?)</script>#is', $this->string, $matches)) {
            $list = [];
            foreach ($matches[2] as $script) {
                if (trim($script) !== '') {
                    $list[] = trim($script);
                }
            }

            return $list;
        }

        return [];
    }

    private function findAllJsonObjects($scriptTags)
    {
        $vars = [];
        foreach ($scriptTags as $scriptTag) {
            $result = $this->extractJsonObjects($scriptTag);

            if ($result) {
                $vars = array_merge($vars, $result);
            }
        }

        return $vars;
    }

    private function extractJsonObjects($scriptTag)
    {
        // Quick check if the string contains something useful.
        if (strpos($scriptTag, '{') === false) {
            return null;
        }

        $vars = [];
        $offset = 0;
        while (true) {
            $start = strpos($scriptTag, '{', $offset);

            if ($start === false) {
                return $vars;
            }

            $end = $this->findVariableEnd($start, $scriptTag);

            $json = substr($scriptTag, $start, $end - $start + 1);

            $data = json_decode($json, JSON_OBJECT_AS_ARRAY);

            if ($data) {
                $vars[] = $data;
                $offset = $end;
            } else {
                $offset = $start + 1;

                $error = json_last_error();
                $errorMsg = json_last_error_msg();
            }
        }

        return $vars;
    }
}