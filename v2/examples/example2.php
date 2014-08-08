<?php
// This is a simple example that loads two css files and joins them into
// one combined css

// Include the parser
include('../cssparser.php');
// Create an instance of the parser
$css = new CSSParser();
// Read the first css
$data1 = file_get_contents('./css1.css');
// Add the css data to the parser
$cssIndex = $css->ParseCSS($data1);
// Print out the current css
echo "<pre>";
echo $css->GetCSS($cssIndex);
echo "</pre>";
// Read the second css
$data2 = file_get_contents('./css2.css');
// Add the css to the parser
$css->ParseCSSAppend($cssIndex, $data2);
// Print out the combined css
echo "<pre>";
echo $css->GetCSS($cssIndex);
echo "</pre>";
