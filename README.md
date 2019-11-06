# Json Extractor

## Abstract

Small class, which will try to extract JSON strings from a string. 
There are two functions which will achieve this.

## Installation

```bash
composer require ronolo/jsonextract
```

If that does not work, you may have to add the repository to the top level composer.json like this:

```json
{
  "repositories": [
     {
        "type": "vcs",
        "url":  "https://github.com/ronolo/jsonextract.git"
    }
  ]
}
```

## Use

```php
<?php
use RoNoLo\JsonExtractor\JsonExtractorService; 
 
$jsonExtractor = new JsonExtractorService();
 
// $html contains a HTML full page string (as example)
$html = file_get_content('foo/bar.html');
$json = $jsonExtractor->extractJsonAfterIdentifier("foobar", $html);
```
 
This will expect the identifier somewhere in the given string. The identifier
position will act as starting point and the next valid JSON object or array will
be returned as PHP array.
 
```php
<?php
use RoNoLo\JsonExtractor\JsonExtractorService; 
 
$jsonExtractor = new JsonExtractorService();
 
// $html contains a HTML full page string
$html = file_get_content('foo/bar.html');
$vars = $jsonExtractor->extractAllJsonData($html);
```

This will look for any JSON objects or arrays in the string and 
returns them as an array of arrays of JSON data. You may have to check the 
list of JSON data for data you was looking for.

It is recommend to break HTML down into smaller parts with a DOM parser,
like symfony/dom-crawler or similar. The smaller the portion is which shall
be parsed the better will be the result.

## Can extract

Correct JSON

```json
{
  "paging": {
    "pageNum": 1,
    "pageSize": 25,
    "numFound": 1,
    "last": 1,
    "lastUncapped": 1,
    "display": [1]
  }
}
``` 

Incorrect Single-Quotes JSON

```
{
  'paging': {
    'pageNum': 1,
    'pageSize': 25,
    'numFound': 1,
    'last': 1,
    'lastUncapped': 1,
    'display': [1]
  }
}
``` 

Javascript Objects (Thanks to the CJSON.php)

```
{
  paging: {
    pageNum: 1,
    pageSize: 25,
    numFound: 1,
    last: 1,
    lastUncapped: 1,
    display: [1]
  }
}
```

 
## Motivation

One site had data which was put via JavaScript into the DOM, therefore it was 
not possible to scrape it via guzzle.