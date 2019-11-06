<?php

namespace RoNoLo\JsonExtractor;

use PHPUnit\Framework\TestCase;
use RoNoLo\JsonExtractor\JsonExtractorException;

class JsonExtractorExtractAllTest extends TestCase
{
    /** @var JsonExtractorService */
    private $jsonExtractor;

    protected function setUp()
    {
        $this->jsonExtractor = new JsonExtractorService();
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