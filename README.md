# Json Extractor

## Abstract

Small class, which will try to extract JSON strings from a string. 
There are two functions which will achive this.

## Use

```php
<?php
 
// $html contains a HTML full page string
$jsonExtractor = new JsonExtractor($html);
 
$json = $jsonExtractor->extractVariable('foobar');
```
 
 This will expect a JavaScript variable with that name and a following 
 JSON definition which must be an array or object.
 
```php
<?php
 
// $html contains a HTML full page string
$jsonExtractor = new JsonExtractor($html);
 
$vars = $jsonExtractor->extractAllJsonData();
```

This will look for any JSON objects (not arrays) in the string and 
returns them as an array or JSON data. Variable names are ignored. You
may have to check the list of JSON data for data you was looking for.
 
## Limitations
 
Only objects and arrays are supported. Because of no further dependencies 
only JSON valid to the json_decode() function is supported. That means JSON
with single quotes and JSON which is a true object notation to JavaScript 
may not be supported.

## Motivation

One site had data which was put via JavaScript into the DOM, therefore it was 
not possible to scrape it via guzzle.