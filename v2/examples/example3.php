<?php
// This is a simple example that loads a css files and makes it a bit more
// effective

// Include the parser
include('../cssparser.php');
// Create an instance of the parser
$css = new CSSParser();
// Read the css
$data = file_get_contents('./css3.css');
// Add the css data to the parser
$cssIndex = $css->ParseCSS($data);
// Print out the more effective css
echo "<pre>";
echo $css->GetCSS($cssIndex);
echo "</pre>";