<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Special Functions PHP</title>
</head>
<body>
	<style type="text/css">
		body {
			display: flex;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
			margin: 0;
		}
		div {
			text-align: center;
		}
		#number{
			font-size: 100px;
		}
		#text,a{
			font-size: 30px;
		}
	</style>

</body>
</html>
<?php
	$str_in = htmlentities($_GET['str_in']);
	$lang = $_GET['lang'];
	$str_out = "";
	$str_in = DeleteBadChar($str_in);
	$str_out .= ExpressNumToWord($str_in,$lang);
	$pos = [];
	for ($i = strlen($str_in) - 1; $i >= 0; $i--) {
		if ((strlen($str_in) - $i) % 3 == 0) {
			$pos[count($pos)] = $i;
		}
	}
	for ($i=0; $i < count($pos); $i++) {
		$str_in = substr_replace($str_in, " ", $pos[$i],0);
	}
	echo "<div><span id='number'>$str_in</span><br><span id='text'>$str_out</span>";
	if ($lang == 'en') {
		echo "<br><a href='index.html'>Home</a></div>";
	}
	else{
		echo "<br><a href='index.html'>На главную</a></div>";
	}
	function DeleteBadChar($str){
		$minus = "";
		if (substr($str,0,1) == '-'){
			$minus = "-";
			$str = substr($str,1);
		}
		$str = preg_replace('[\D]','',$str);
		while (substr($str,0,1) == '0' && strlen($str) > 1) {
			$str=substr($str,1);
		}
		return $minus.$str;
	}
	function ExpressNumToWord($str, $lang)
	{
		$digits = [
			"ru" => [
				["","один","два","три","четыре","пять","шесть","семь","восемь","девять","десять","одиннадцать","двенадцать","тринадцать","четырнадцать","пятнадцать","шестнадцать","семнадцать","восемнадцать","девятнадцать"],
				["","десять","двадцать","тридцать","сорок","пятьдесят","шестьдесят","семьдесят","восемдесят","девяноста"],
				["","сто","двести","триста","четыреста","пятьсот","шестьсот","семьсот","восемьсот","девятьсот"]
			],
			"en" => [
				["zero","one","two","three","four","five","six","seven","eight","nine","ten","eleven","twelve","thirteen","fourteen","fifteen","sixteen","seventeen","eighteen","nineteen"],
				["","ten","twenty","thirty","forty","fifty","sixty","seventy","eighty","ninety"]
			]
		];
		$words = [
			"ru" => [
				["","тысяча","миллион","миллиард","триллион","квадриллион","квинтиллион"],
				["","тысячи","миллиона","миллиарда","триллиона","квадриллиона","квинтиллиона"],
				["","тысяч","миллионов","миллиардов","триллионов","квадриллионов","квинтиллионов"]
			],
			"en" => [
				["","thousand","million","billion","trillion","quadrillion","quintillion"]
			]
		];
		$word = "";
		$str_out = "";
		if (substr($str, 0, 1) == '-'){
			if ($lang == 'ru') {
				$str_out .= "минус ";
			}
			else{
				$str_out .= "minus ";
			}
			$str = substr($str, 1);
		}
		if (substr($str, 0, 1) == '0' && strlen($str) == 1){
			if ($lang == 'ru') {
				return 'ноль';
			}
			elseif ($lang == 'en') {
				return 'zero';
			}
		}
		$len = strlen($str);
		$pos = $len;
		for ($i = 1; $i <= $len; $i++) {
			$digit = 1 * substr($str, $i-1, 1);
			$place = $len - $i;
			if ($digit == 1 && $place % 3 == 1) {
				$i++;
				$digit = 1 * substr($str, $i-1, 1);
				$digit += 10;
				$place = $len - $i;
			}
			if ($pos > 0 && $place % 3 == 0){
				if ($digit == 1 || $lang == "en") {
					$case = 0;
				}
				elseif ($digit > 1 && $digit < 5) {
					$case = 1;
				}
				elseif ($digit >= 5) {
					$case = 2;
				}
				if (intdiv($pos - 1, 3)) {
					$word = $words[$lang][$case][intdiv($pos - 1, 3)];
					if ($lang == 'ru') {
						if ($case == 0){
							$digits['ru'][0][1] = 'одна';
						}
						elseif ($case == 1){
							$digits['ru'][0][2] = 'две';
						}
					}
				}
				$pos = $place;
			}
			if ($lang == 'en') {
				if ($place % 3 == 2) {
					$word = "hundred";
				}
				if($place % 3 == 1){
					$str_out .= $digits[$lang][1][$digit]." ".$word." ";
				}
				else
				{
					$str_out .= $digits[$lang][0][$digit]." ".$word." ";
				}
			}
			else {
				$str_out .= $digits[$lang][$place % 3][$digit]." ".$word." ";
			}
			$digits['ru'][0][1] = 'один';
			$digits['ru'][0][2] = 'два';
			$word = "";
		}
		return $str_out;
	}