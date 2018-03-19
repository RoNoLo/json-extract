<?php

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
                __DIR__ . '/../resources/ek.html',
                __DIR__ . '/../resources/adsAsJsonResult.json',
                'adsAsJson',
            ],
            [
                __DIR__ . '/../resources/ek.html',
                __DIR__ . '/../resources/iam_dataResult.json',
                'iam_data',
            ],
            [
                __DIR__ . '/../resources/ek.html',
                __DIR__ . '/../resources/universalAnalyticsOptsResult.json',
                'universalAnalyticsOpts',
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
                __DIR__ . '/../resources/ek.html',
                36,
            ],
            [
                __DIR__ . '/../resources/zldo.html',
                23,
            ],
        ];
    }
}