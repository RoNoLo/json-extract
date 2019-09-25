<?php

namespace RoNoLo\JsonExtractor\Service;

use PHPUnit\Framework\TestCase;
use RoNoLo\JsonExtractor\Exception\JsonExtractorException;

class JsonExtractorExceptionsTest extends TestCase
{
    /** @var JsonExtractorService */
    private $jsonExtractor;

    protected function setUp()
    {
        $this->jsonExtractor = new JsonExtractorService();
    }

    /**
     * @throws JsonExtractorException
     */
    public function testThrowsExceptionWhenNoJsonIsFoundInString()
    {
        $this->expectException(JsonExtractorException::class);
        $this->expectExceptionMessage("No JSON was found after given offset");

        $string = file_get_contents(__DIR__ . '/../dist/stringWithNoJson.txt');

        $this->jsonExtractor->extractAllJsonData($string);
    }

    /**
     * @throws JsonExtractorException
     */
    public function testThrowsExceptionWhenIdentifierWasNotFound()
    {
        $this->expectException(JsonExtractorException::class);
        $this->expectExceptionMessage("The identifier was not found in the string");

        $string = file_get_contents(__DIR__ . '/../dist/ek.html');

        $this->jsonExtractor->extractJsonAfterIdentifier("john", $string);
    }
}