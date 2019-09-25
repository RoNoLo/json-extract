<?php

namespace RoNoLo\JsonExtractor\Service;

use PHPUnit\Framework\TestCase;

class JsonExtractorSingleIdentifierExtractTest extends TestCase
{
    /** @var JsonExtractorService */
    private $jsonExtractor;

    protected function setUp()
    {
        $this->jsonExtractor = new JsonExtractorService();
    }

    /**
     * @dataProvider canExtractJsonVariableDataProvider
     *
     * @param $html
     * @param $expected
     * @param $var
     * @throws \Exception
     */
    public function testCanExtractJsonVariable($html, $expected, $var)
    {
        $html = file_get_contents($html);

        $expected = json_decode(file_get_contents($expected), true);

        $result = $this->jsonExtractor->extractJsonAfterIdentifier($var, $html);

        $this->assertEquals($expected, $result);
    }

    public function canExtractJsonVariableDataProvider()
    {
        return [
            [
                __DIR__ . '/../dist/ek.html',
                __DIR__ . '/../dist/adsAsJsonResult.json',
                'adsAsJson',
            ],
            [
                __DIR__ . '/../dist/ek.html',
                __DIR__ . '/../dist/iam_dataResult.json',
                'iam_data',
            ],
            [
                __DIR__ . '/../dist/ek.html',
                __DIR__ . '/../dist/universalAnalyticsOptsResult.json',
                'universalAnalyticsOpts',
            ],
            [
                __DIR__ . '/../dist/ek.html',
                __DIR__ . '/../dist/bannerOptsResult.json',
                'bannerOpts',
            ],
            [
                __DIR__ . '/../dist/ek.html',
                __DIR__ . '/../dist/berndResult.json',
                'bernd',
            ],
            [
                __DIR__ . '/../dist/ek.html',
                __DIR__ . '/../dist/BelenConfResult.json',
                'BelenConf',
            ],
        ];
    }
}