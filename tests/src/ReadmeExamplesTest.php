<?php

declare(strict_types=1);

namespace RoNoLo\JsonExtractor\Tests;

use PHPUnit\Framework\TestCase;
use RoNoLo\JsonExtractor\JsonExtractorService;

class ReadmeExamplesTest extends TestCase
{
    private JsonExtractorService $jsonExtractor;

    protected function setUp(): void
    {
        $this->jsonExtractor = new JsonExtractorService();
    }

    public function testCanExtractStandardJsonFromReadme(): void
    {
        $html = <<<HTML
        <html>
        <head>
            <script type="text/javascript">
                var foobar = {
                  "paging": {
                    "pageNum": 1,
                    "pageSize": 25,
                    "numFound": 1,
                    "last": 1,
                    "lastUncapped": 1,
                    "display": [1]
                  }
                };
            </script>
        </head>
        </html>
        HTML;

        $expected = [
            'paging' => [
                'pageNum' => 1,
                'pageSize' => 25,
                'numFound' => 1,
                'last' => 1,
                'lastUncapped' => 1,
                'display' => [1],
            ],
        ];

        $result = $this->jsonExtractor->extractJsonAfterIdentifier('foobar', $html);

        $this->assertSame($expected, $result);
    }

    public function testCanExtractSingleQuotedJsonFromReadme(): void
    {
        $html = <<<HTML
        <div>
            Some text before foobar = {
              'paging': {
                'pageNum': 1,
                'pageSize': 25,
                'numFound': 1,
                'last': 1,
                'lastUncapped': 1,
                'display': [1]
              }
            };
        </div>
        HTML;

        $expected = [
            'paging' => [
                'pageNum' => 1,
                'pageSize' => 25,
                'numFound' => 1,
                'last' => 1,
                'lastUncapped' => 1,
                'display' => [1],
            ],
        ];

        $result = $this->jsonExtractor->extractJsonAfterIdentifier('foobar', $html);

        $this->assertSame($expected, $result);
    }

    public function testCanExtractJavascriptObjectsFromReadme(): void
    {
        $html = <<<HTML
        <script>
            var config = {
              paging: {
                pageNum: 1,
                pageSize: 25,
                numFound: 1,
                last: 1,
                lastUncapped: 1,
                display: [1]
              }
            };
        </script>
        HTML;

        $expected = [
            'paging' => [
                'pageNum' => 1,
                'pageSize' => 25,
                'numFound' => 1,
                'last' => 1,
                'lastUncapped' => 1,
                'display' => [1],
            ],
        ];

        $result = $this->jsonExtractor->extractJsonAfterIdentifier('config', $html);

        $this->assertSame($expected, $result);
    }

    public function testCanExtractArrayAfterIdentifier(): void
    {
        $html = <<<HTML
        <script>
            var items = ["first", "second", {"key": "value"}];
        </script>
        HTML;

        $expected = [
            'first',
            'second',
            ['key' => 'value'],
        ];

        $result = $this->jsonExtractor->extractJsonAfterIdentifier('items', $html);

        $this->assertSame($expected, $result);
    }

    public function testCanExtractAllJsonDataFromMixedHtml(): void
    {
        $html = <<<HTML
        <html>
        <body>
            <script>
                var standardJson = {"key": "val1"};
                var singleQuotes = {'item': 100};
                var jsObject = {name: 'john'};
            </script>
        </body>
        </html>
        HTML;

        $result = $this->jsonExtractor->extractAllJsonData($html);

        $this->assertCount(3, $result);
        $this->assertSame(['key' => 'val1'], $result[0]);
        $this->assertSame(['item' => 100], $result[1]);
        $this->assertSame(['name' => 'john'], $result[2]);
    }
}
