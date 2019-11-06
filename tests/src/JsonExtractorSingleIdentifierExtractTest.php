<?php

namespace RoNoLo\JsonExtractor;

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