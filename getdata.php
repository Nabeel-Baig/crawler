<?php

$html = file_get_contents('https://mikrotik.com/products/group/ethernet-routers');
libxml_use_internal_errors(true);
$doc = new DOMDocument;
$doc->loadHTML($html);
$xpath = new DOMXpath($doc);

// Image
$image = $xpath->query('//div[@id="productlist17"]/div[@class="product"]/div[@class="product-img"]/a/img[@class="lazyload"]');
// print_r($image->length);die;
$content = [];
if ($image->length > 0) {
	foreach ($image as $key => $entry) {
		$newImage = $image->item($key)->getAttribute('data-src');
		// Now download the image
		// Use basename() function to return the base name of file  
		$file_name = basename($newImage);
		// use file_get_contents() function to get the file from url and use file_put_contents() to save the file by using basename
		// file_put_contents($file_name,file_get_contents($newImage));
		$content['image'][] = $file_name;
	}
}

// Name
$name = $xpath->query('//div[@id="productlist17"]/div[@class="product"]/div[@class="product-description"]/h2/a');
if ($name->length > 0) {
	foreach ($name as $key => $value) {
		$content['name'][] = $value->nodeValue;
	}
}

// description
$description = $xpath->query('//div[@id="productlist17"]/div[@class="product"]/div[@class="product-description"]/p');
if ($description->length > 0) {
	foreach ($description as $key => $value) {
		$content['description'][] = $value->nodeValue;
	}
}

/* echo "<pre>";
print_r($content);die; */
// // Price
// $price = $xpath->query('//div[@class="caption"]/p[@class="price"]');
// if ($price->length > 0) {
// 	foreach ($price as $key => $value) {
// 		$content['price'][] = $value->nodeValue;
// 	}
// }
?>

 <?php
/* $output = '<table">
 	<tr>
 		<th>
 			product_image
 		</th>
 		<th>
		 product_name
 		</th>
 		<th>
		 product_short_desc
 		</th>
 	</tr><tbody>';
for ($i = 0; $i < count($content['name']); $i++) {

	$output .= '<tr><td>' . $content['image'][$i] . '</td>' .
		'<td>' . $content['name'][$i] . '</td>' .

		'<td>' . $content['description'][$i] . '</td></tr>';
}

$output .= '</tbody></table>'; */
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="sample.csv"');
// $output[0] = ['product_name','product_image','product_short_desc'];
for ($i=0; $i < count($content['name']); $i++) { 
	$output[] = [$content['name'][$i],$content['image'][$i],$content['description'][$i]];
} 


$fp = fopen('php://output', 'wb');
foreach ( $output as $line ) {
    // $val = explode(",", $line);
    fputcsv($fp, $line,',');
}
fclose($fp);
/* header("Content-Type: application/csv");
header("Content-Disposition: attachment; filename=download.csv"); */
// echo $output;
?>