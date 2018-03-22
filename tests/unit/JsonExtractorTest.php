<?php

namespace RoNoLo\JsonExtractor;

use PHPUnit\Framework\TestCase;

class JsonExtractorTest extends TestCase
{
    /**
     * @dataProvider canExtractJsonVariableDataProvider
     *
     * @param $html
     * @param $expected
     * @param $var
     * @throws Exception
     */
    public function testCanExtractJsonVariable($html, $expected, $var)
    {
        $html = file_get_contents($html);

        $expected = json_decode(file_get_contents($expected), JSON_OBJECT_AS_ARRAY);

        $je = new JsonExtractor($html);

        $result = $je->extractVariable($var);

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
                __DIR__ . '/../dist/berndResult.json',
                'bernd',
            ],
        ];
    }

    /**
     * @dataProvider canExtractAllJsonObjectsDataProvider
     */
    public function testCanExtractAllJsonObjects($testFile, $expected)
    {
        $html = file_get_contents($testFile);

        $je = new JsonExtractor($html);

        $result = $je->extractAllJsonData();

        $this->assertEquals($expected, count($result));
    }

    public function canExtractAllJsonObjectsDataProvider()
    {
        return [
            [
                __DIR__ . '/../dist/ek.html',
                13,
            ],
            [
                __DIR__ . '/../dist/zldo.html',
                23,
            ],
        ];
    }
}