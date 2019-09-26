# Json Extractor

## Abstract

Small class, which will try to extract JSON strings from a string. 
There are two functions which will achieve this.

## Use

```php
<?php
 
$jsonExtractor = new JsonExtractorService();
 
// $html contains a HTML full page string (as example)
$json = $jsonExtractor->extractJsonAfterIdentifier("foobar", $html);
```
 
This will expect the identifier somewhere in the given string. The identifier
position will act as starting point and the next valid JSON object or array will
be returned as PHP array.
 
```php
<?php
 
$jsonExtractor = new JsonExtractorService();
 
// $html contains a HTML full page string
$vars = $jsonExtractor->extractAllJsonData($html);
```

This will look for any JSON objects or arrays in the string and 
returns them as an array of arrays of JSON data. You may have to check the 
list of JSON data for data you was looking for.

It is recommend to break HTML down into smaller parts with a DOM parser,
like symfony/dom-crawler or similar. The smaller the portion is which shall
be parsed the better will be the result. 
 
## Motivation

One site had data which was put via JavaScript into the DOM, therefore it was 
not possible to scrape it via guzzle.