<?php

declare(strict_types=1);

namespace RoNoLo\JsonExtractor;

class JsonExtractorService
{
    private ?string $string = null;

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
     * @throws JsonExtractorException
     */
    public function extractJsonAfterIdentifier(string $identifier, string $text): array
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

        return (array) $json;
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
     * @throws JsonExtractorException
     */
    public function extractAllJsonData(string $text): array
    {
        $this->string = $text;
        $this->ensureAnyJsonAfterOffset();

        $vars = [];
        $offset = 0;
        while (true) {
            try {
                $startPosition = $this->findJsonStart($offset);
            } catch (JsonExtractorException) {
                $this->string = null;

                if ($vars === []) {
                    throw new JsonExtractorException("No valid JSON could be found in the given string.");
                }

                return $vars;
            }

            try {
                $endPosition = $this->findJsonEnd($startPosition);
                $json = $this->decodeJson($startPosition, $endPosition);

                $vars[] = $json;
                $offset = $endPosition;
            } catch (JsonExtractorException) {
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
    private function findJsonStart(int $offset = 0): int
    {
        // Now find the very next symbol which needs to be { or [
        $strLength = strlen($this->string);
        for ($i = $offset; $i < $strLength; $i++) {
            if ($this->string[$i] === '[' || $this->string[$i] === '{') {
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
    private function findJsonEnd(int $startPosition): int
    {
        $symbolOpen = $this->string[$startPosition];
        $symbolClose = $symbolOpen === '{' ? '}' : ']';

        $stack = [$symbolClose];

        $i = $startPosition;
        while (true) {
            $i++;

            if (!isset($this->string[$i])) {
                throw new JsonExtractorException("End of JSON object / JSON array could not be found.");
            }

            if ($this->string[$i] === $symbolOpen) {
                $stack[] = $symbolClose;
                continue;
            }

            if ($this->string[$i] === $symbolClose) {
                array_pop($stack);

                if ($stack === []) {
                    return $i;
                }
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
    private function decodeJson(int $startPosition, int $endPosition): mixed
    {
        $json = substr($this->string, $startPosition, $endPosition - $startPosition + 1);

        $return = json_validate($json) ? json_decode($json, true) : null;

        if (!$return) {
            $return = CJSON::decode($json);

            if (!$return || (is_array($return) && count(array_filter($return)) === 0)) {
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
    private function ensureIdentifierInString(string $identifier): void
    {
        if (!str_contains($this->string, $identifier)) {
            throw new JsonExtractorException("The identifier was not found in the string.");
        }
    }

    /**
     * @param int $offset
     *
     * @throws JsonExtractorException
     */
    private function ensureAnyJsonAfterOffset(int $offset = 0): void
    {
        $object = strpos($this->string, '{', $offset);
        $array = strpos($this->string, '[', $offset);

        if ($object === false && $array === false) {
            throw new JsonExtractorException("No JSON was found after given offset.");
        }
    }
}
