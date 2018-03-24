# Json Extractor

## Abstract

Small class, which will try to extract JSON strings from a string. 
There are two functions which will achieve this.

## Use

```php
<?php
 
$jsonExtractor = new JsonExtractor();
 
// $html contains a HTML full page string (as example)
$json = $jsonExtractor->extractJsonAfterIdentifier("foobar", $html);
```
 
 This will expect the identifier somewhere in the given string. The identifier
 position will act as starting point and the next valid JSON object or array will
 be returned as PHP array.
 
```php
<?php
 
$jsonExtractor = new JsonExtractor();
 
// $html contains a HTML full page string
$vars = $jsonExtractor->extractAllJsonData($html);
```

This will look for any JSON objects or arrays in the string and 
returns them as an array of arrays of JSON data. You may have to check the 
list of JSON data for data you was looking for.
 
## Limitations
 
Only objects and arrays are supported. Because of no further dependencies 
only JSON valid to the json_decode() function is supported. That means JSON
with single quotes and JSON which is a true object notation to JavaScript 
may not be supported (were the keys have no quotes).

## Motivation

One site had data which was put via JavaScript into the DOM, therefore it was 
not possible to scrape it via guzzle.