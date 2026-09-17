<?php 
	if (!isset($typegroup) ) $typegroup=0;
	//جهت تعیین گروه کتابخانه به صورت ساختار درختی 
	if ($typegroup==3) {
		$qry="SELECT * FROM `$banknamegroup` order by parent,idsort ";
		//echo "qry  = $qry";
		 $result=mysql_query($qry);
		 $arrayCategories = array();
		 $oklist=0;
		 while($row = mysql_fetch_assoc($result)) { 	$oklist=1;
			$arrayCategories[$row['id_main']] = array("ID" => $row['id_main'],"PARENT" => $row['parent'], "NAME" =>  $row['name']);     
		}
		if(!function_exists("createTree3")) {		
			function createTree3($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
				global $groupid, $groupname, $parentid;
				foreach ($array as $categoryId => $category) {
					if ($currentParent == $category['PARENT']) {
						if ($currLevel > $prevLevel) echo " <ul>  \r\n"; 
						if ($currLevel == $prevLevel) echo " </li>  \r\n";
	//								echo "<li id='$category[ID]'>$category[ID] - ".$category['NAME'] ." \r\n";
						echo "<li id='$category[ID]'>".$category['NAME'] ." \r\n";
						if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
						$currLevel++; 
						createTree3($array, $categoryId, $currLevel, $prevLevel);
						$currLevel--;               
					}
				}
				if ($currLevel == $prevLevel) echo " </li>  </ul> \r\n";

			}   
		}
		echo "<div  id=\"jstree\">";
		if($oklist!=0) {				createTree3($arrayCategories, 0); 			}
		echo "</div>";

		return;
	}
		
	//مخصوص انتخاب بیش از یک مورد گروه ها فعلا قابل استفاده در بخش افزودن تصاویر
	if ($typegroup==2) {
		$qry="SELECT * FROM `$banknamegroup` order by parent,idsort ";
		 $result=mysql_query($qry);
		 $arrayCategories = array();
		 $oklist=0;
		 while($row = mysql_fetch_assoc($result)) { 	$oklist=1;
			$arrayCategories[$row['id_main']] = array("ID" => $row['id_main'],"PARENT" => $row['parent'], "NAME" =>  $row['name']);     
		}
		if(!function_exists("createTree2")) {		
			function createTree2($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
				global $groupid, $groupname, $parentid;
				foreach ($array as $categoryId => $category) {
					if ($currentParent == $category['PARENT']) {
						$tetrparent = '  ' . str_repeat('—',($currLevel)*5) . '» ' .$category[NAME];
						$sel='';
						$checkselect = ','.$category['ID'].',';
						
						if (strpos($groupid, $checkselect) !== false) { $sel='selected'; }
						echo "<option value='$category[ID]' $sel > $tetrparent </option>";
						if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
						$currLevel++; 
						createTree2($array, $categoryId, $currLevel, $prevLevel);
						$currLevel--;               
					}
				}
			}   
		}
		echo "<select id='groupid' multiple=\"multiple\" size=10 name='groupid[ ]' dir=rtl  class=\"h4\" style='font-family:tahoma; font-size:12px; width: 400px; ' onchange='change_group();' tabindex='1'>";
		if($oklist!=0) {				createTree2($arrayCategories, 0); 			}
		echo "</select>";

		return;
	}
	
	if (!isset($tetr1group) ) $tetr1group='ریشه';

	$qry="SELECT * FROM `$banknamegroup` order by parent,idsort ";
	 $result=mysql_query($qry);
	 $arrayCategories = array();
	 $oklist=0;
	 while($row = mysql_fetch_assoc($result)) { 	$oklist=1;
		$arrayCategories[$row['id_main']] = array("ID" => $row['id_main'],"PARENT" => $row['parent'], "NAME" =>  $row['name']);     
	}
	if(!function_exists("createTree1")) {		
		function createTree1($array, $currentParent, $currLevel = 0, $prevLevel = -1) {
			global $groupid, $groupname, $parentid;
			foreach ($array as $categoryId => $category) {
				if ($currentParent == $category['PARENT']) {
					$tetrparent = '  ' . str_repeat('—',($currLevel)*5) . '» ' .$category[NAME];
					$sel='';
					switch ($typegroup) {
						//--فعلا فقط جهت افزودن گروه در بخش گروه ها استفاده می شود
						case 1:  if ($parentid==$category['ID']) {$sel='selected'; } 
								break;
						default: 
							if ($groupid==$category['ID']) {$sel='selected';  $groupname = $category[NAME];}
					}
					echo "<option value='$category[ID]' $sel > $tetrparent </option>";
					if ($currLevel > $prevLevel) { $prevLevel = $currLevel; }
					$currLevel++; 
					createTree1($array, $categoryId, $currLevel, $prevLevel);
					$currLevel--;               
				}
			}
		}   
	}
	switch ($typegroup) {
		case 1:  			
			echo "<select size=10 name='parentid' dir=rtl  class=\"h4\" style='font-family:tahoma;			font-size:12px; width: 300px; '>";
			break;
		default: 
			echo "<select size=1 name='groupid' dir=rtl  class=\"h4\" style='font-family:tahoma;font-size:12px; width: 200px;' onchange='change_group(this.value);'>";
	}
	echo "<option value='0'> $tetr1group</option>";
	if($oklist!=0) {				createTree1($arrayCategories, 0); 			}
	echo "</select>";

?>
			
			
			