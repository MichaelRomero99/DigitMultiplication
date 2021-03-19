<?php
echo <<<_END

<html><head><title>TXT Form Upload</title></head><body>
	<form action="midterm.php" method="post" enctype="multipart/form-data">
	Please Upload a TXT file that contains 400 Digits.
	<br>
  	Select TXT to upload:
  	<input type="file" name="filename" size="10" >
  	<input type="submit" value="Upload TXT">
	</form>

_END;	

//Are we supposed to sanitize the file whem we check if its empty?
if ($_FILES) {

	switch(htmlentities($_FILES['filename']['type'])){
		case 'text/plain' : $ext ='txt';break;
		default : $ext ='';break;
	}
	if($ext){
	//Sanitize and get File
		$tmpname = htmlentities($_FILES['filename']['tmp_name']);
		$data = file_get_contents($tmpname);
	
		$array = getArray($data);
		if($array != -1){
			//ANS
			echo "Iterating to the Right: " . LeftRight($array);
			echo "<br>";
			echo "Iterating to the Down: " . upDown($array);
			echo "<br>";
			echo "Iterating left Diagonal: " . leftDiagonal($array);
			echo "<br>";
			echo "Iterating right Diagonal: " . rightDiagonal($array);
		}else{
			echo "The file format is wrong";
		}
	}else{
		echo "File Not Accepted";
	}
	
		
	
}else{
	echo "No files Uploaded";
}

echo "</body> </html>";

//Convert File into a 2D Array and Verify if the file is valid
function getArray($info){
	$singleArray = str_split($info);
	$i = 0;
	$j = -1;
	$size = 0;
	$array = array();
	foreach ($singleArray as $char) {
		if(is_numeric($char)){
			if($i %20 == 0){
				$j += 1;
				$array[$j] = array();
				$i = 0;
			}
			$array[$j][$i] = (int) $char;
		}elseif( ctype_alpha($char) or preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $char) ){
			return -1;
		}else{
			continue;
		}
		$i+=1;
		$size += 1;		
	}
	if($size != 400){
		return -1;
	}
	return $array;
}

function upDown($array){
	//Columns
	$larg = 1;
	$start = 0;
	$end = 0;
	$numbers = array();
	for($x = 0; $x < 20; $x+=1){
		//Rows
			for($y = 0; $y < 20; $y+=1){
				if($end - $start <= 3){
					array_push($numbers,$array[$y][$x]);
				}else{
					$mult = 1;
					//multiply all 4 elements
					for($z = 0; $z < 4; $z+=1){
						$mult = $mult * $numbers[$z];
					}
					//update largest if needed
					if($mult > $larg){
						$larg = $mult;
					}
					//Remove the first element from the array
					$numbers = array_slice($numbers,1);	
					
					$start+=1;//increment index
					array_push($numbers,$array[$y][$x]);//add a new one to the end
				}
				$end += 1;	
			}
		}
		return $larg;
	
}

function leftRight($array){
	//Rows
	$larg = 1;
	$start = 0;
	$end = 0;
	$numbers = array();
	for($x = 0; $x < 20; $x+=1){
		//Columns
			for($y = 0; $y < 20; $y+=1){
				if($end - $start <= 3){
					array_push($numbers,$array[$x][$y]);
				}else{
					$mult = 1;
					//multiply all 4 elements
					for($z = 0; $z < 4; $z+=1){
						$mult = $mult * $numbers[$z];
					}
					//update largest if needed
					if($mult > $larg){
						$larg = $mult;
					}
					//Remove the first element from the array
					$numbers = array_slice($numbers,1);	
					
					$start+=1;//increment index
					array_push($numbers,$array[$x][$y]);//add a new one to the end
				}
				$end += 1;	
			}
		}
		return $larg;
}

function rightDiagonal($array){
	$max = 1;
	for($x = 0; $x <= 16; $x += 1){
		for($y = 0; $y < 20;$y+=1){
			if($y < 17){
				$num = $array[$x][$y] * $array[$x+1][$y+1] * $array[$x+2][$y+2] * $array[$x+3][$y+3];
				if($num > $max){
					$max = $num;
				}
			}else if($y == 17){
				$num = $array[$x][$y] * $array[$x+1][$y+1] * $array[$x+2][$y+2] * $array[$x+3][0];
				if($num > $max){
					$max = $num;
				}
			}else if($y == 18){
				$num = $array[$x][$y] * $array[$x+1][$y+1] * $array[$x+2][0] * $array[$x+3][1];
				if($num > $max){
					$max = $num;
				}
			}else if($y == 19){
				$num = $array[$x][$y] * $array[$x+1][0] * $array[$x+2][1] * $array[$x+3][2];
				if($num > $max){
					$max = $num;
				}
			}
		}
	}
	return $max;
}

function leftDiagonal($array){
	$max = 1;
	for($x = 0; $x <= 16; $x += 1){
		for($y = 19; $y >= 0;$y-=1){
			if($y >= 3){
				$num = $array[$x][$y] * $array[$x+1][$y-1] * $array[$x+2][$y-2] * $array[$x+3][$y-3];
				if($num > $max){
					$max = $num;
				}
			}else if($y == 2){
				$num = $array[$x][$y] * $array[$x+1][$y-1] * $array[$x+2][$y-2] * $array[$x+3][19];
				if($num > $max){
					$max = $num;
				}
			}else if($y == 1){
				$num = $array[$x][$y] * $array[$x+1][$y-1] * $array[$x+2][19] * $array[$x+3][18];
				if($num > $max){
					$max = $num;
				}
			}else if($y == 0){
				$num = $array[$x][$y] * $array[$x+1][19] * $array[$x+2][18] * $array[$x+3][17];
				if($num > $max){
					$max = $num;
				}
			}
		}
	}
	return $max;
}

//A tester function, used for debugging
function test(){
	$input = "";
	//Input 399 ones's and 1 random two.
	for($x = 0; $x < 400; $x+=1){
		if($x == 250){
			$input .= "2";
		}else{
			$input .= "1";
		}
	}
	$array = getArray($input);
	// echo upDown($array); // 2
	// echo "<br>";
	// echo leftRight($array);//2
	// echo "<br>";
	// echo rightDiagonal($array);//2
	// echo "<br>";
	// echo leftDiagonal($array);//2

	$input2 = "2a";
	$array2 = getArray($input2);//array2 will echo "The file format is wrong"

}
//Help with debugging
function print2DArray($array){
	for($x = 0; $x < 20; $x+=1){
		for($y = 0; $y < 20; $y+=1){
			echo $array[$x][$y] . " ";
		}
		echo "<br>";
	}
}





?>