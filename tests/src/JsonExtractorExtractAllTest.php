<?php

declare(strict_types=1);

namespace RoNoLo\JsonExtractor\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RoNoLo\JsonExtractor\JsonExtractorService;

class JsonExtractorExtractAllTest extends TestCase
{
    private JsonExtractorService $jsonExtractor;

    protected function setUp(): void
    {
        $this->jsonExtractor = new JsonExtractorService();
    }

    #[DataProvider('canExtractAllJsonObjectsDataProvider')]
    public function testCanExtractAllJsonObjects(string $testFile, int $expected): void
    {
        $html = (string) file_get_contents($testFile);

        $result = $this->jsonExtractor->extractAllJsonData($html);

        $this->assertCount($expected, $result);
    }

    public static function canExtractAllJsonObjectsDataProvider(): array
    {
        return [
            [
                __DIR__ . '/../fixtures/ek.html',
                14,
            ],
            [
                __DIR__ . '/../fixtures/zldo.html',
                35,
            ],
            [
                __DIR__ . '/../fixtures/t.html',
                35,
            ],
        ];
    }
}
