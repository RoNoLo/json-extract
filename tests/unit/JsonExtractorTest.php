<?php

namespace RoNoLo\JsonExtractor;

use PHPUnit\Framework\TestCase;

class JsonExtractorTest extends TestCase
{
    /** @var JsonExtractor */
    private $jsonExtractor;

    protected function setUp()
    {
        $this->jsonExtractor = new JsonExtractor();
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

        $expected = json_decode(file_get_contents($expected), JSON_OBJECT_AS_ARRAY);

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
        ];
    }

    /**
     * @dataProvider canExtractAllJsonObjectsDataProvider
     * @throws JsonExtractorException
     */
    public function testCanExtractAllJsonObjects($testFile, $expected)
    {
        $html = file_get_contents($testFile);

        $result = $this->jsonExtractor->extractAllJsonData($html);

        $this->assertEquals($expected, count($result));
    }

    public function canExtractAllJsonObjectsDataProvider()
    {
        return [
            [
                __DIR__ . '/../dist/ek.html',
                11,
            ],
            [
                __DIR__ . '/../dist/zldo.html',
                28,
            ],
        ];
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
    public function testThrowsExceptionWhenBrokenJsonIsFound()
    {
        $this->expectException(JsonExtractorException::class);
        $this->expectExceptionMessage("End of JSON object / array could not be found");

        $string = file_get_contents(__DIR__ . '/../dist/stringWithBrokenJson.txt');

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