<?php
	header("Content-type:image/gif");
	session_start();
	
	$_SESSION['mask'] = $mask = generate();
	
	$im = imagecreate(150,30);
	imagecolorallocate($im, 255, 255, 255);
	
	$font = 'msyh.ttf';
	$c = strlen($mask);
	for($i = 0 ; $i < $c; ++$i)
	{
		$color = imagecolorallocate($im, mt_rand(0,100), mt_rand(0, 200), mt_rand(0, 200));
		//imagechar($im, mt_rand(4,5), 12 * $i + mt_rand(0,0), mt_rand() % 5, $mask[$i], $color);
		imagettftext($im, 18, mt_rand(0,50) - 25, 20 * $i + mt_rand(5,15), 22 + mt_rand() % 5, $color, $font, $mask[$i]);
	}
	
	
	$c = mt_rand(3,5);
	for($i = 0; $i < $c; ++$i)
	{
		$color = imagecolorallocate($im, mt_rand(0,100), mt_rand(0, 255), mt_rand(0, 200));
		$x1 = mt_rand(0, 150); $x2 = mt_rand(0, 150);
		$y1 = mt_rand(0, 30); $y2 = mt_rand(0, 30);
		imageline($im, $x1, $y1, $x2, $y2, $color);
	}
	
	$c = 250;
	for($i = 0; $i < $c; ++$i)
	{
		$color = imagecolorallocate($im, mt_rand(0,100), mt_rand(0, 255), mt_rand(0, 200));
		$x1 = mt_rand(0, 150);  
		$y1 = mt_rand(0, 30);  
		imagesetpixel($im, $x1, $y1, $color);
	}
	
	
	
	imagegif($im);
	imagedestroy($im);
	
	exit();
	///////////////////////
	echo generate();
	
	function generate(){
		$res = '';
		
		$c = mt_rand(4, 6);
		for($i = 0; $i < $c; ++$i){
			$a = mt_rand() % 36;
			if ($a > 9){
				if (mt_rand() % 2){
					$a = chr($a - 10 + ord('a'));
				}else {
					$a = chr($a - 10 + ord('A'));
				}
			}
			$res .= $a;
		}
		
		return $res;
	}

?>
