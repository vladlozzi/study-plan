<?php
if (!defined("IN_ADMIN")) { echo "<p style=\"text-align: center; color: red; font-weight: bold;\">".
                      "Помилка входу в модуль chekers.php</p>"; require "footer.php"; exit(); 
}

function paramCheker($name, $checked, $prompt,  $options)
{
	$str = "<input type=\"checkbox\" name=\"".$name."\" $options";
	if ($checked) {
		$str .= " checked />".$prompt."<br>";
	} else {
		$str .= "/>".$prompt."<br>";
	}
	return $str;
}

function paramChekerInline($name, $checked, $prompt,  $options)
{
	$str = "<input type=\"checkbox\" name=\"".$name."\" $options";
	if ($checked) {
		$str .= " checked />".$prompt;
	} else {
		$str .= "/>".$prompt;
	}
	return $str;
}

function paramChekerRedInline($name, $checked, $prompt, $options)
{
	$str = "<label>
						<input type=\"checkbox\" class=\"checkbox\" id=\"".$name."\" 
							name=\"".$name."\" $options";	if ($checked) $str .= " checked"; 
	$str .= "/><span class=\"checkbox-custom\"></span> ".$prompt."</label>";
	return $str;
}

function selectCommonSelect
         ($prompttext, $name, $conn, $query, $value, $previosValue, $view, $options)
{ 
$str=$prompttext
 ."<select name=\"".$name."\" $options >";
$str.="<option></option>";
$select_result=mysqli_query($conn, $query);
while($select_row = mysqli_fetch_array($select_result))
{
       	if(stripslashes($select_row[$value])==$previosValue)
	{
		$str.="<option selected value=\"";
	}
	else
	{
        	$str.="<option value=\"";
	}
	$str.=stripslashes($select_row[$value]);
	$str.="\">";
	$str.=stripslashes($select_row[$view]);
	$str.="</option>";
}
$str
.="</select>";
return $str;
}

function selectCommonSelectAutoSubmit
         ($prompttext, $name, $conn, $query, $value, $previosValue, $view, $options)
{
$str=$prompttext
 ."<select name=\"$name\" $options onchange=\"submit()\" style=\"font-weight: bold; font-size: 110%\">";
$str.="<option></option>";
$select_result = mysqli_query($conn, $query);
while ($select_row = mysqli_fetch_array($select_result)) {
       	if (stripslashes($select_row[$value])==$previosValue) {
		$str.="<option selected value=\"";
	}
	else {
        	$str.="<option value=\"";
	}
	$str.=stripslashes($select_row[$value]);
	$str.="\">";
	$str.=stripslashes($select_row[$view]);
	$str.="</option>";
}
$str
.="</select>";
return $str;
}

function selectCommonCheckerNoAutoSubmit
         ($prompttext, $name, $conn, $query, $value, $previosValue, $view)
{
$str=$prompttext
 ."<select name=\"".$name."\">";
$str.="<option></option>";
$select_result=mysqli_query($conn, $query);
while($select_row = mysqli_fetch_array($select_result))
{
       	if(stripslashes($select_row[$value])==$previosValue)
	{
		$str.="<option selected value=\"";
	}
	else
	{
        	$str.="<option value=\"";
	}
	$str.=stripslashes($select_row[$value]);
	$str.="\">";
	$str.=stripslashes($select_row[$view]);
	$str.="</option>";
}
$str
.="</select>";
return $str;
}

?>