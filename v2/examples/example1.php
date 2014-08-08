<?php
// This is a simple example that loads two css files and keeps them
// separate

// Include the parser
include('../cssparser.php');
// Create an instance of the parser
$css = new CSSParser();
// Read the first css
$data1 = file_get_contents('./css1.css');
// Add the css data to the parser
$cssIndex1 = $css->ParseCSS($data1);
// Print out the first css
echo "<pre>";
echo $css->GetCSS($cssIndex1);
echo "</pre>";
// Read the second css
$data2 = file_get_contents('./css2.css');
// Add the css to the parser
$cssIndex2 = $css->ParseCSS($data2);
// Print out the second css
echo "<pre>";
echo $css->GetCSS($cssIndex2);
echo "</pre>";
