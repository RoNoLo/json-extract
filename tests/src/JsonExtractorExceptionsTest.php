<?php

declare(strict_types=1);

namespace RoNoLo\JsonExtractor\Tests;

use PHPUnit\Framework\TestCase;
use RoNoLo\JsonExtractor\JsonExtractorException;
use RoNoLo\JsonExtractor\JsonExtractorService;

class JsonExtractorExceptionsTest extends TestCase
{
    private JsonExtractorService $jsonExtractor;

    protected function setUp(): void
    {
        $this->jsonExtractor = new JsonExtractorService();
    }

    public function testThrowsExceptionWhenNoJsonIsFoundInString(): void
    {
        $this->expectException(JsonExtractorException::class);
        $this->expectExceptionMessage("No JSON was found after given offset.");

        $string = (string) file_get_contents(__DIR__ . '/../fixtures/stringWithNoJson.txt');

        $this->jsonExtractor->extractAllJsonData($string);
    }

    public function testThrowsExceptionWhenIdentifierWasNotFound(): void
    {
        $this->expectException(JsonExtractorException::class);
        $this->expectExceptionMessage("The identifier was not found in the string.");

        $string = (string) file_get_contents(__DIR__ . '/../fixtures/ek.html');

        $this->jsonExtractor->extractJsonAfterIdentifier("john", $string);
    }
}
