<?php

declare(strict_types=1);

namespace RoNoLo\JsonExtractor\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RoNoLo\JsonExtractor\JsonExtractorService;

class JsonExtractorSingleIdentifierExtractTest extends TestCase
{
    private JsonExtractorService $jsonExtractor;

    protected function setUp(): void
    {
        $this->jsonExtractor = new JsonExtractorService();
    }

    #[DataProvider('canExtractJsonVariableDataProvider')]
    public function testCanExtractJsonVariable(string $html, string $expected, string $var): void
    {
        $htmlContent = (string) file_get_contents($html);

        $expectedData = json_decode((string) file_get_contents($expected), true);

        $result = $this->jsonExtractor->extractJsonAfterIdentifier($var, $htmlContent);

        $this->assertEquals($expectedData, $result);
    }

    public static function canExtractJsonVariableDataProvider(): array
    {
        return [
            [
                __DIR__ . '/../fixtures/ek.html',
                __DIR__ . '/../fixtures/adsAsJsonResult.json',
                'adsAsJson',
            ],
            [
                __DIR__ . '/../fixtures/ek.html',
                __DIR__ . '/../fixtures/iam_dataResult.json',
                'iam_data',
            ],
            [
                __DIR__ . '/../fixtures/ek.html',
                __DIR__ . '/../fixtures/universalAnalyticsOptsResult.json',
                'universalAnalyticsOpts',
            ],
            [
                __DIR__ . '/../fixtures/ek.html',
                __DIR__ . '/../fixtures/bannerOptsResult.json',
                'bannerOpts',
            ],
            [
                __DIR__ . '/../fixtures/ek.html',
                __DIR__ . '/../fixtures/berndResult.json',
                'bernd',
            ],
            [
                __DIR__ . '/../fixtures/ek.html',
                __DIR__ . '/../fixtures/BelenConfResult.json',
                'BelenConf',
            ],
        ];
    }
}
