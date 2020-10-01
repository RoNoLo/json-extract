<?php

namespace RoNoLo\JsonExtractor;

use RoNoLo\JsonExtractor\JsonExtractorException;

include_once __DIR__ . '/CJSON.php';

class JsonExtractorService
{
    private $string;

    /**
     * Will extract the first JSON of a declaration like this:
     *
     * <script type="bernd"> { ... }
     * var foo = { ... }
     * var foo = [ ... ]
     * foo = { ... }
     *
     * @param string $identifier An identifier as a starting point
     * @param string $text Text
     *
     * @return array
     *
     * @throws \RoNoLo\JsonExtractor\JsonExtractorException
     */
    public function extractJsonAfterIdentifier(string $identifier, string $text)
    {
        $this->string = $text;
        $this->ensureAnyJsonAfterOffset();

        $this->ensureIdentifierInString($identifier);

        $pos = strpos($this->string, $identifier);
        $this->ensureAnyJsonAfterOffset($pos);

        $startPosition = $this->findJsonStart($pos + 1);
        $endPosition = $this->findJsonEnd($startPosition);
        $json = $this->decodeJson($startPosition, $endPosition);

        $this->string = null;

        return $json;
    }

    /**
     * Will extract all JSON data it can find in a string.
     *
     * Only objects will be extracted.
     *
     * @param string $text Text
     *
     * @return array
     *
     * @throws \RoNoLo\JsonExtractor\JsonExtractorException
     */
    public function extractAllJsonData(string $text)
    {
        $this->string = $text;
        $this->ensureAnyJsonAfterOffset();

        $vars = [];
        $offset = 0;
        while (true) {
            try {
                $startPosition = $this->findJsonStart($offset);
            } catch (JsonExtractorException $e) {
                $this->string = null;

                if (!count($vars)) {
                    throw new JsonExtractorException("No valid JSON could be found in the given string.");
                }

                return $vars;
            }

            try {
                $endPosition = $this->findJsonEnd($startPosition);
                $json = $this->decodeJson($startPosition, $endPosition);

                $vars[] = $json;
                $offset = $endPosition;
            } catch (JsonExtractorException $e) {
                $offset = $startPosition + 1;
            }
        }
    }

    /**
     * Searches for the opening bracket for the next JSON object or array.
     *
     * @param int $offset Offset
     *
     * @return int
     *
     * @throws JsonExtractorException
     */
    private function findJsonStart(int $offset = 0)
    {
        // Now find the very next symbol which needs to be { or [
        $strLength = strlen($this->string);
        for ($i = $offset; $i < $strLength; $i++) {
            if (in_array($this->string[$i], ['[', '{'])) {
                return $i;
            }
        }

        throw new JsonExtractorException("Could not find any JSON object or array after the given offset.");
    }

    /**
     * Searches for the closing bracket for the current JSON object or array.
     *
     * @param int $startPosition
     *
     * @return int
     *
     * @throws JsonExtractorException
     */
    private function findJsonEnd(int $startPosition)
    {
        $symbolOpen = $this->string[$startPosition];
        $symbolClose = $symbolOpen === '{' ? '}' : ']';

        $stack = [];
        $stack[] = $symbolClose;

        $i = $startPosition;
        while (true) {
            $i++;

            if (!isset($this->string[$i])) {
                throw new JsonExtractorException("End of JSON object / JSON array could not be found.");
            }

            if ($this->string[$i] == $symbolOpen) {
                $stack[] = $symbolClose;
                continue;
            }

            if ($this->string[$i] == $symbolClose) {
                array_pop($stack);

                if (!count($stack)) {
                    return $i;
                }
                continue;
            }
        }
    }

    /**
     * @param int $startPosition
     * @param int $endPosition
     *
     * @return mixed
     *
     * @throws JsonExtractorException
     */
    private function decodeJson(int $startPosition, int $endPosition)
    {
        $json = substr($this->string, $startPosition, $endPosition - $startPosition + 1);

        $return = json_decode($json, JSON_OBJECT_AS_ARRAY);

        if (!$return) {
            $return = \CJSON::decode($json);

            if (!$return || is_array($return) && !count(array_filter($return))) {
                $errorMessage = json_last_error_msg();

                throw new JsonExtractorException($errorMessage);
            }
        }

        return $return;
    }

    /**
     * @param string $identifier
     *
     * @throws JsonExtractorException
     */
    private function ensureIdentifierInString(string $identifier)
    {
        $pos = strpos($this->string, $identifier);

        if ($pos === false) {
            throw new JsonExtractorException("The identifier was not found in the string.");
        }
    }

    /**
     * @param int $offset
     *
     * @throws JsonExtractorException
     */
    private function ensureAnyJsonAfterOffset($offset = 0)
    {
        $object = strpos($this->string, '{', $offset);
        $array = strpos($this->string, '[', $offset);

        if ($object === false && $array === false) {
            throw new JsonExtractorException("No JSON was found after given offset.");
        }
    }
}