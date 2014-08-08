# CSSParser

## Version 1 vs Version 2
First of all. The two versions are **NOT** compatible.

The first version has moved into the `v1`folder and the second version can be found in the `v2` folder.

### Version 1
Version 1 was written back in 2003 and was designed for PHP4. I did a major rewrite of the code that never got published.

### Version 2
When I sat down and did a major rewrite of my CMS earlier this year I also did an update on the css parser. This is the result of this rewrite.

Please note that this code is a slimmed down version of the one used in my CMS since this have some special features that I've left out in this code. This also means that this code CAN have bugs and flaws not found in my other code base.

#### Uncommented code
The code of the CSSParser isn't well documented. It's actually rather poorly documented. This will change. I chosed to prioritize the update rather than to fix the comments.

## class CSSParser
*This documentation is very crude and a work in progress. It will get better in time.*

### Public properties
The class CSSParser doesn't have any public properties exposed.

### Public functions

#### public function ParseCSS($css)
Parse the given text as css.

This function returns an numeric index that can be used to address this code in the other functions. This also means that it is possible to parse different css data independently.

#### public function ParseCSSAppend($index, $css)
This function parses and appends the the given css data to the specified index.

#### public function ParseCSSMediaAppend($index, $css, $media)
Almost the same as ParseCSSAppend but here you can specify which type of media this code should be parsed as.

#### public function AddProperty($index, $media, $section, $property, $value)
Adds a property and value to a given section and specified media for the index.

Confusing? :-)
Let me show a simple example.
```css
@media screen {
  body {
    background: #ffffff;
  }
}
```
Is the same as writing this code
```php
// Assume that we have an index in the variable $index
$cssparser->AddProperty($index, 'screen', 'body', 'background', '#ffffff');
```
And it will be interpreted as the following array with its var_dump
```php
$css = array('screen'=>array('body'=>array('background'=>'#ffffff')));
var_dump($css);
// array(1) {
//   ["screen"]=>
//   array(1) {
//     ["body"]=>
//     array(1) {
//       ["background"]=>
//       string(7) "#ffffff"
//     }
//   }
// }
```

#### public function GetMediaList($index)
Get all defined media types parsed for the given index.
By default there is three media types defined. `screen, print and all`.

#### public function ExportKeyValues($index, $media, $keys)
Export all values for the given keys (or key, if only a string is supplied) in the given media for the given index.
This function only return an array with the values.

#### public function ExportMedia($index, $media, $block = false)
Gets the css data for a given index and media.
If $block is assigned a string then this is used as an regexp value where all matching values are excluded.

#### public function ExportStyle($index, $block = false)
Gets the css data for a given index.
If $block is assigned a string then this is used as an regexp value where all matching values are excluded.

#### public function GetSections($index, $media = 'screen')
Get all sections for the given index and media.

*Sections is equal to selector.*

#### public function GetSectionsFiltered($index, $matchKey, $matchValue, $media = 'screen')
Get alls sections which have a property/value pair that matches $matchKey and $matchValue.

If a property/value pair if found then the entire section (selector) is added to the output.

The result is given as an array.

#### public function GetEditorCSS($index)
Get the css code for the given index.
This is the same as calling:
```php
$cssparser->GetCSS($index, 'screen', array('^filter$'));
```
#### public function GetCSS($index, $media = 'screen', $forbiddenKeys = array())
Returns the css code for the given index and media. Any keys that matches the regmatch keys specified in $forbiddenKeys is removed.

#### public function GetCSSFiltered($index, $matchKey, $matchValue, $media = 'screen')
This is a wrapper for calling GetSectionsFiltered and then create css code from the result.

#### public function GetCSSArray($index, $media = 'screen')
Get the raw array for the given index and media.

#### public static function ConvertWildcards($text)
A supporting function that converts filter text to be used in RegExp.
- . converts to \.
- \* converts to .\*
- ? converts to .

## Features
Since the parser is built upon arrays there is a problem with multiple properties with the same name. This is commonly used when assigning a background in css.
```css
  background: #1e5799;
  background: -moz-linear-gradient(top, #1e5799 0%, #2989d8 50%, #207cca 51%, #7db9e8 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#1e5799), color-stop(50%,#2989d8), color-stop(51%,#207cca), color-stop(100%,#7db9e8));
  background: -webkit-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
  background: -o-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
  background: -ms-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
  background: linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
```
becomes
```css
  background: linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
```
This since the parser interprets the property by itself and two properties with the same name will overwrite eachother.

To allow this there is a special feature that uses a suffix with a square brackets and a numeric index. This suffix is removed when the css output is created.
```css
  background[0]: #1e5799;
  background[1]: -moz-linear-gradient(top, #1e5799 0%, #2989d8 50%, #207cca 51%, #7db9e8 100%);
  background[2]: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#1e5799), color-stop(50%,#2989d8), color-stop(51%,#207cca), color-stop(100%,#7db9e8));
  background[3]: -webkit-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
  background[4]: -o-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
  background[5]: -ms-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
  background[6]: linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
```
becomes
```css
  background: #1e5799;
  background: -moz-linear-gradient(top, #1e5799 0%, #2989d8 50%, #207cca 51%, #7db9e8 100%);
  background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#1e5799), color-stop(50%,#2989d8), color-stop(51%,#207cca), color-stop(100%,#7db9e8));
  background: -webkit-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
  background: -o-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
  background: -ms-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
  background: linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%);
```
A minor note is that the numeric index may be omitted. They are mearly there for readability.

*Please note that two indexes with the same number will not overwrite eachother.*

## Usage
```php
include('cssparser.php');
$cssparser = new CSSParser();
$css = file_get_contents('./data.css');
$index = $cssparser->ParseCSS($css);
$output = $cssparser->GetCSS($index);
header('Content-Type: text/css');
echo $output;
```
A simple example to create an instance of the parser, feed it with some css data, get the parsed css and ship it to the browser.
