<?php
        require_once("../class/class.dbf.php");
        class BusinessLogic extends DBFunction
        {
		
		
		function buildSQL() {
				$sql="";
				if(!empty ($_GET["web"]))
						$sql.="web='".$this->escapeStr($_GET["web"])."'";
				if(!empty ($_GET["status"])) {
						if(!empty ($sql))
								$sql.=" and ";
						$sql.="status='".$this->escapeStr($_GET["status"])."'";
				}
				if(!empty ($_GET["reporter"])) {
						if(!empty ($sql))
								$sql.=" and ";
						$sql.="reporter='".$this->escapeStr($_GET["reporter"])."'";
				}
				if(!empty ($_GET["place"])) {
						if(!empty ($sql))
								$sql.=" and ";
						$sql.="order_place='".$this->escapeStr($_GET["place"])."'";
				}
				if(!empty ($_GET["from"])&&!empty ($_GET["to"])) {
						if(!empty ($sql))
								$sql.=" and ";
						$sql.="(created between '".($_GET["from"])."' and '".($_GET["to"])."')";
				}
				else
						if(!empty ($_GET["from"])) {
								if(!empty ($sql))
										$sql.=" and ";
								$sql.="created >= '" . ($_GET["from"]) . "'";
						}
						else
								if(!empty ($_GET["to"])) {
										if(!empty ($sql))
												$sql.=" and ";
										$sql.="created <= '" . ($_GET["to"]) . "'";
								}
								else{
										if(!empty ($sql))
												$sql.=" and ";
										$sql.="created >= '" . date("Y-m-d",time()-24*60*60*30) . "'";
				}
				return $sql;
		}
		function toArrayQueryString($url) {
				$param=array();
				$url=preg_replace("/^&/","",$url);
				$url=preg_replace("/^=/","",$url);
				$url=preg_replace("/={2,100}/","=",$url);
				$url=preg_replace("/&{2,100}/","&",$url);
				$array=explode("&",$url);
				foreach($array as $value) {
						if(!empty ($value)) {
								$explode=explode("=",$value);
								if(count($explode)>0)
										$param[$explode[0]]=$explode[1];
						}
				}
				return $param;
		}
		/*
		Paging on one table
		*******************************************************************/
		function selectPage($tablename,$where,$orderby,$url,$PageNo,$PageSize,$Pagenumber,$ModePaging,$arrayMode="") {
				if($PageNo=="") {
						$StartRow=0;
						$PageNo=1;
				}
				else
						$StartRow=($PageNo-1)*$PageSize;
				if($PageSize<1||$PageSize>50)
						$PageSize=15;
				if($PageNo%$Pagenumber==0)
						$CounterStart=$PageNo-($Pagenumber-1);
				else
						$CounterStart=$PageNo-($PageNo%$Pagenumber)+1;
				$CounterEnd=$CounterStart+$Pagenumber;
				$TRecord=$this->getArray($tablename,$where,$orderby,$arrayMode);
				$RecordCount=count($TRecord);
				$result=$this->getArray($tablename,$where,$orderby." LIMIT ".$StartRow.",".$PageSize,$arrayMode);
				if($RecordCount%$PageSize==0)
						$MaxPage=$RecordCount/$PageSize;
				else
						$MaxPage=ceil($RecordCount/$PageSize);
				$gotopage="";
				switch($ModePaging) {
						case "Full":
								$gotopage='<div class="paging_meneame">';
								if($MaxPage>1) {
										if($PageNo!=1) {
												$PrevStart=$PageNo-1;
												$gotopage.=' <a href="'.$url.'&PageNo=1" tile="First page"> &laquo; </a>';
												$gotopage.=' <a href="'.$url.'&PageNo='.$PrevStart.'" title="Previous page"> &lsaquo; </a>';
										}
										else{
												$gotopage.=' <span class="paging_disabled"> &laquo; </span>';
												$gotopage.=' <span class="paging_disabled"> &lsaquo; </span>';
										}
										$c=0;
										for($c=$CounterStart; $c<$CounterEnd;++$c) {
												if($c<=$MaxPage)
														if($c==$PageNo)
																$gotopage.='<span class="paging_current"> '.$c.' </span>';
														else
																$gotopage.=' <a href="'.$url.'&PageNo='.$c.'" title="Page '.$c.'"> '.$c.' </a>';
										}
										if($PageNo<$MaxPage) {
												$NextPage=$PageNo+1;
												$gotopage.=' <a href="'.$url.'&PageNo='.$NextPage.'" title="Next page"> &rsaquo; </a>';
										}
										else{
												$gotopage.=' <span class="paging_disabled"> &rsaquo; </span>';
										}
										if($PageNo<$MaxPage)
												$gotopage.=' <a href="'.$url.'&PageNo='.$MaxPage.'" title="Last page"> &raquo; </a>';
										else
												$gotopage.=' <span class="paging_disabled"> &raquo; </span>';
								}
								$gotopage.=' </div>';
								break;
				}
				$arr[0]=$result;
				$arr[1]=$gotopage;
				return $arr;
		}		
		
		
		

				function displayColor($colorid,$chkid,$hiddenValue)
				{
					$mangmau=array();
					$tmp=explode(";",$hiddenValue);
					array_pop($tmp);
					foreach($tmp as $key) $mangmau["$key"]="checked";

					echo "<div style='width:420px;border:0px solid;float:left;'>";
					$rstcolor=$this->getDynamic("color","status=1","position asc");
					while($rowcolor=$this->nextData($rstcolor))
					{
						$code=$rowcolor["code"];
						echo "<div style='width:52px;float:left;margin-bottom:1px;margin-right:0px;'>";
							echo "<div style='width:20px;float:left;height:14px;'>";
							echo $this->checkbox("$chkid",$code,"",array("onclick"=>"docheckone2('".$colorid."','".$chkid."');","checked"=>strtr($code,$mangmau)));
							echo "</div>";
							echo "<div style='width:29px;float:left;height:14px;margin-top:2px;font-size:3px;background-color:".$rowcolor["code"]."'></div>";
						echo "</div>";
					}
				  echo "<input type='hidden' class='nd1' value='$hiddenValue' name='$colorid' id='$colorid' />";
				  echo "</div>";
				}

				
				//2018.07.27
				function displayProductSize($pzid,$chkid,$hiddenValue)
				{
					$mangPdS=array();
					$tmp=explode(";",$hiddenValue);
					array_pop($tmp);
					foreach($tmp as $key) $mangPdS["$key"]="checked";

					echo "<div style='width:420px;border:0px solid;float:left;'>";
					$rstpds=$this->getDynamic("product_size","status=1","position asc");
					while($rowpds=$this->nextData($rstpds))
					{
						$code=$rowpds["code"];
						echo "<div style='width:52px;float:left;margin-bottom:1px;margin-right:0px;'>";
							echo "<div style='width:20px;float:left;height:14px;'>";
							echo $this->checkbox("$chkid",$code,"",array("onclick"=>"docheckone2('".$pzid."','".$chkid."');","checked"=>strtr($code,$mangPdS)));
							echo "</div>";
							echo "<div style='width:29px;float:left;height:14px;margin-top:2px;font-size:3px;background-color:".$rowpds["code"]."'></div>";
						echo "</div>";
					}
				  echo "<input type='hidden' class='nd1' value='$hiddenValue' name='$pzid' id='$pzid' />";
				  echo "</div>";
				}

				//
				function displayTarget($arrayTarget,$target,$idName,$arrayOption=null)
				{
					$str="<select name='$idName' id='$idName' class='cbo'>
					<option value='0' >-- ".$arrayOption["firstText"]." --</option>";

							foreach($arrayTarget as $key=>$value)
							{
							$str.="<option value='".$key."' ".(($target==$key)?"selected":"").">".$value."</option>";
							}
					$str.="</select>";
					return $str;
				}
				//
				function displaySelect($arrayTarget,$selected,$idName,$arrayOption=null)
				{

					$str="
				<div id='panelAction' class='panelAction2'>
                    <div style='float:left;height:17px;padding-top:5px;'></div>
					<div class='panelActionContent2'>
					<table id='panelTable' cellpadding='0' cellspacing='0'>
						<tr>
							<td class='cellAction2'>".$arrayOption["text"].": ";
							$str.="<select name='$idName' id='$idName' class='cbo' onchange=\"".$arrayOption["onchange"]."\"><option value='0' >-- ".$arrayOption["firstText"]." --</option>";

							foreach($arrayTarget as $key=>$value)
							{
						$str.="<option value='".$key."' ".(($selected==$key)?"selected='selected'":"").">".$value."</option>";
							}
					$str.="</select>";
							$str.="</td>
						</tr>
					</table>
					</div>
				</div>";
				return $str;
					return $str;
				}
				//
				function displayConfig($arrayType,$type,$idName,$arrayOption=null)
				{
					$str="<select name='$idName' id='$idName' class='cbo'><option value='0' >-- ".$arrayOption["firstText"]." --</option>";
					foreach($arrayType as $key=>$value)
					{
						$str.="<option value='".$key."' ".(($type==$key)?"selected":"").">".$value."</option>";
					}
					$str.="</select>";
					return $str;
				}
				//
				function viewRecursive($col,$tablename,$page,$iscatURL,$statusAction,$urlstring="",$msg="")
				{
				echo "<table id='mainTable' cellpadding='0' cellspacing='0'>";
				if(!empty($msg))
				{
					echo "<tr>";
					echo "<td class=\"txtdo\" align=\"center\" colspan=\"" . count($col) . "\">" . $msg . "</td>";
					echo "</tr>";	
				}
				echo "<tr>";
				$i=1;
				foreach($col as $key=>$value)
				{
					if($i==1)
					echo "<td class='titleBottom' style='width:7%'>".$this->checkbox("chkall","1","&radic;",array("onclick"=>"docheck(this.checked,0);","class"=>"checkAll"))."</td>";
					else
					{
							if(in_array($key,array("position","pos","iscat","status","is_active","is_cat","display","home","parentid","parent")))
							echo "<td class='titleBottom' style='width:8.5%;text-align:center'>$value</td>";
							else echo "<td class='titleBottom'>$value</td>";
					}
					$i++;
				}
				echo "</tr>";

						$i=0;
						if($iscatURL>0)
							$grid=$this->recursives($tablename,"ordering, id",$iscatURL,$col);
						else
							$grid=$this->recursives($tablename,"ordering, id",0,$col);

						foreach($grid as $k=>$rowview)
						{
							$k=array_keys($col);
							$id=$rowview[$k[0]];

							$class=($i%2==0)?"cell2":"cell1";
							echo "<tr ".ALPHA8." >";
							$j=1;
							foreach($col as $key=>$value)
							{
									if($j==1)
										echo "<td class='$class'>".$this->checkbox("chk","$rowview[$key]","",array("onclick"=>"docheckone();","class"=>"checkDelete"))."&nbsp;<a href='$page?edit=$id'>$id.</a></td>";
									else
									{
										if(in_array($key,array("picture_vn","picture_en","picture")))
										{
											$tmp=explode(";",$rowview[$key]);
											$img="<img src='resize.php?from=".pathPicture.$tmp[0]."&h=20' border='0' >";
										}elseif(in_array($key,array("logo")))
										{
											$tmp=explode(";",$rowview[$key]);
										$img="<img src='resize.php?from=".pathPicture."/products/logo_salon/".$tmp[0]."&h=20' border='0' >";
										}
										$date=date("d-m-Y G:i:s",(int)$rowview[$key]);
										if(in_array($key,array("pos","position","discount","viewed")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecellPos' ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".$rowview[$key].(($key=="discount")?"%":"")."</a></div></td>";
										elseif(in_array($key,array("parent","parentid")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecellPos' ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".(($rowview[$key]=="")?"--- Root ---":$rowview[$key])."</a></div></td>";
										else if(in_array($key,array("price","price_vn","price_en","startPrice","endPrice")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell' ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".number_format($rowview[$key],0,",",".")."$ </a></div></td>";
										else if(in_array($key,array("picture_vn","picture_en","picture","picture_jp","picture_ch","logo","picture_dichvu")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div  class='inlinecell' ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".$img."</a></div></td>";
										else if(in_array($key,array("status","is_active","is_new","iscat","display","home","is_cat","man","woman","stock")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div  class='inlinecell' ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".strtr($rowview[$key],$statusAction)."</a></div></td>";
										elseif(in_array($key,array("datecreated","dateupdated","datemodified","date")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell' ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".$date."</a></div></td>";
										else
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell' ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".stripslashes($rowview[$key])."</a></div></td>";
									}
									$j++;
							}
							echo "</tr>";
							$i++;
						}

				echo "</table>";
				}
				//
				function viewRecursiveJoin($col,$tablename,$page,$iscatURL,$statusAction,$tablename2,$arrayNewName,$typejoin="inner join",$on,$urlstring="")
				{
				echo "<table id='mainTable' cellpadding='0' cellspacing='0'><tr>";
				$i=1;
				foreach($col as $key=>$value)
				{
					if($i==1)
					echo "<td class='titleBottom' style='width:7%'>".$this->checkbox("chkall","1","&radic;",array("onclick"=>"docheck(this.checked,0);","class"=>"checkAll"))."</td>";
					else
					{
							if(in_array($key,array("position","pos","iscat","status","is_active","is_cat","display","home","parentid","parent")))
							echo "<td class='titleBottom' style='width:8.5%;text-align:center'>$value</td>";
							else echo "<td class='titleBottom'>$value</td>";
					}
					$i++;
				}
				echo "</tr>";

						$i=0;
						if($iscatURL>0)
							$grid=$this->recursiveJoin($tablename,$tablename2,$arrayNewName,$typejoin,$on,"t1.parentid asc",$iscatURL,$col);
						else
							$grid=$this->recursiveJoin($tablename,$tablename2,$arrayNewName,$typejoin,$on,"t1.parentid asc",0,$col);

						foreach($grid as $k=>$rowview)
						{
							$k=array_keys($col);
							$id=$rowview[$k[0]];

							$class=($i%2==0)?"cell2":"cell1";
							echo "<tr ".ALPHA8." >";
							$j=1;
							foreach($col as $key=>$value)
							{
									if($j==1)
										echo "<td class='$class'>".$this->checkbox("chk","$rowview[$key]","",array("onclick"=>"docheckone();","class"=>"checkDelete"))."&nbsp;<a href='$page?edit=$id'>$id.</a></td>";
									else
									{
										if(in_array($key,array("picture_vn","picture_en","picture")))
										{
											$tmp=explode(";",$rowview[$key]);
											$img="<img src='resize.php?from=".pathPicture.$tmp[0]."&h=20' border='0' >";
										}elseif(in_array($key,array("logo")))
										{
											$tmp=explode(";",$rowview[$key]);
										$img="<img src='resize.php?from=".pathPicture."/products/logo_salon/".$tmp[0]."&h=20' border='0' >";
										}
										$date=date("d-m-Y",(int)$rowview[$key]);
										if(in_array($key,array("pos","position","discount","viewed")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div  class='inlinecellPos'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".$rowview[$key].(($key=="discount")?"%":"")."</a></div></td>";
										elseif(in_array($key,array("parent","parentid")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div  class='inlinecellPos'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".(($rowview[$key]=="")?"--- Root ---":$rowview[$key])."</a></div></td>";
										else if(in_array($key,array("price","price_vn","price_en","startPrice","endPrice")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div  class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".number_format($rowview[$key],0,",",".")."$ </a></div></td>";
										else if(in_array($key,array("picture_vn","picture_en","picture","picture_jp","picture_ch","logo","picture_dichvu")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div  class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".$img."</a></div></td>";
										else if(in_array($key,array("status","is_active","is_new","iscat","display","home","is_cat","man","woman","stock")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div  class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".strtr($rowview[$key],$statusAction)."</a></div></td>";
										elseif(in_array($key,array("datecreated","dateupdated","datemodified","date")))
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div  class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".$date."</a></div></td>";
										else
										echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div  class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".stripslashes($rowview[$key])."</a></div></td>";
									}
									$j++;
							}
							echo "</tr>";
							$i++;
						}

				echo "</table>";
				}
				//
				function normalView($col,$page,$mang,$statusAction,$urlstring="",$msg="",$_array="")
				{
					echo "<table id='mainTable' cellpadding='0' cellspacing='0'>";
					if(!empty($msg))
					{
						echo "<tr>";
						echo "<td class=\"txtdo\" align=\"center\" colspan=\"" . count($col) . "\">" . $msg . "</td>";
						echo "</tr>";	
					}
					$i=1;
					foreach($col as $key=>$value)
					{
						if($i==1)
						echo "<td class='titleBottom' style='width:7%'>".$this->checkbox("chkall","1","&radic;",array("onclick"=>"docheck(this.checked,0);","class"=>"checkAll"))."</td>";
						else
						{
							if(in_array($key,array("position","pos","iscat","status","is_active","is_cat","display","home","parentid","parent")))
							echo "<td class='titleBottom' style='width:8.5%;text-align:center'>$value</td>";
							else echo "<td class='titleBottom'>$value</td>";
						}
						$i++;
					}
					echo "</tr>";

							$view=$mang[0];
							$i=0;
							while($rowview=$this->nextData($view))
							{
								$k=array_keys($col);
								$id=$rowview[$k[0]];
								$class=($i%2==0)?"cell2":"cell1";
								echo "<tr ".ALPHA8." >";
								$j=1;
								foreach($col as $key=>$value)
								{
										if($j==1)
											echo "<td class='$class'>".$this->checkbox("chk","$id","",array("onclick"=>"docheckone();","class"=>"checkDelete"))."&nbsp;<a href='$page?edit=$id'>$id.</a></td>";
										else
										{
											if(in_array($key,array("bannerurl","picture_vn","picture_en","picture")))
											{
												$tmp=explode(";",$rowview[$key]);
												$img="<img src='resize.php?from=".pathPicture.$tmp[0]."&h=20' border='0' >";
											}elseif(in_array($key,array("logo")))
											{
												$tmp=explode(";",$rowview[$key]);
											$img="<img src='resize.php?from=".pathPicture."/products/logo_salon/".$tmp[0]."&h=20' border='0' >";
											}
											$date = !empty($rowview[$key]) ? date("d-m-Y G:i:s",(int)$rowview[$key]) : '';
											if(in_array($key,array("parent","parentid","discount","viewed")))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecellPos'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".$rowview[$key].(($key=="discount")?"%":"")."</a></div></td>";
											else if(in_array($key,array("gia_vnd","price","price_vn","startPrice","endPrice")))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".number_format($rowview[$key],0,",",".")." VNĐ </a></div></td>";
											else if(in_array($key,array("gia_usd","price","price_usd","startPrice","endPrice")))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".number_format($rowview[$key],0,",",".")." USD </a></div></td>";
											else if(in_array($key,array("sale","vat")))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".$rowview[$key]."%</a></div></td>";
											else if(in_array($key,array("bannerurl","picture_vn","picture_en","picture","picture_jp","picture_ch","logo","picture_dichvu")))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".$img."</a></div></td>";
											else if(in_array($key,array("status","approve","is_active","is_new","iscat","home","is_cat","man","woman","stock")))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".strtr($rowview[$key],$statusAction)."</a></div></td>";
											elseif(in_array($key,array("datecreated","dateupdated","datemodified","date","added","updated","approved")))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".$date."</a></div></td>";
											elseif(in_array($key,array("ordered")))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>" . date("d-m-Y H:i:s",(int)$rowview[$key]) . "</a></div></td>";
											elseif(in_array($key,array("cost")))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>" . number_format((int) $rowview[$key], 0, '.', '.') . " VND</a></div></td>";
											elseif(in_array($key,array("parent_name")))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>" . (isset($key) && !empty($rowview[$key]) ? stripslashes($rowview[$key]) : '-- ROOT --') . "</a></div></td>";
											elseif(in_array($key,array("display")) && isset($_array) && is_array($_array))
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>" . $_array[$rowview[$key]] . "</a></div></td>";
											else
											echo "<td class='$class' ondblclick=\"redirect('$page?edit=$id');\"><div class='inlinecell'  ".mouse." ><a id='itemText' href='$page?edit=$id&$urlstring'>".stripslashes($rowview[$key])."</a></div></td>";
										}
										$j++;

								}
								echo "</tr>";
								$i++;
							}

					echo "</table>";
				}
			//
			function selectFilter($idName,$datatextfield,$datavaluefield,$matchSelected,$text="",$tablename,$arrayOption,$level="0",$search="0")
			{
				$str="
				<div id='panelAction' class='panelAction2'>
                    <div style='float:left;'>" . ($search == 1 ? 'Tìm sản phẩm <input type="text" name="tu-khoa" id="tu-khoa" class="nd1" placeholder="Nhập tên sản phẩm" /> <input type="submit" name="btnSearch" id="btnSearch" value="Tìm" />' : '') . "</div>
					<div class='panelActionContent2'>
					<table id='panelTable' cellpadding='0' cellspacing='0'>
						<tr>
							<td class='cellAction2'>$text ".($this->generateRecursiveSelect($idName,$datatextfield,$datavaluefield,$matchSelected,$tablename,$level,$arrayOption))."</td>
						</tr>
					</table>
					</div>
				</div>";
				return $str;
			}
			//
			function selectFilter2($idName,$datatextfield,$datavaluefield,$matchSelected,$text="",$tablename,$arrayOption,$level="0")
			{
				$str="
				<div id='panelAction' class='panelAction2'>
                    <div style='float:left;height:17px;padding-top:5px;'></div>
					<div class='panelActionContent2'>
					<table id='panelTable' cellpadding='0' cellspacing='0'>
						<tr>
							<td class='cellAction2'>$text ".($this->generateRecursiveSelect2($idName,$datatextfield,$datavaluefield,$matchSelected,$tablename,$level,$arrayOption,$iscat))."</td>
						</tr>
					</table>
					</div>
				</div>";
				return $str;
			}
			//
			function selectFilter3($idName,$datatextfield,$datavaluefield,$matchSelected,$text="",$where="",$orderby="",$tablename,$arrayOption)
			{
				$str="
				<div id='panelAction' class='panelAction2'>
                    <div style='float:left;height:17px;padding-top:5px;'></div>
					<div class='panelActionContent2'>
					<table id='panelTable' cellpadding='0' cellspacing='0'>
						<tr>
							<td class='cellAction2'>$text ".($this->generateNoRecursiveSelect($idName,$datatextfield,$datavaluefield,$matchSelected,$tablename,$where,$orderby,$arrayOption))."</td>
						</tr>
					</table>
					</div>
				</div>";
				return $str;
			}
			//
			function generateNoRecursiveSelect($idName,$datatextfield,$datavaluefield,$matchSelected,$tablename,$where="",$orderby="",$arrayOption=null)
			{
	$str="<select onchange=\"".$arrayOption["onchange"]."\" name='".$idName."' id='".$idName."' class='cbo'>";

	if($arrayOption["firstText"]!="")
	$str.="<option value='0' >-- ".$arrayOption["firstText"]." --</option>";

	$col[$datatextfield]=$datatextfield;
	$col[$datavaluefield]=$datavaluefield;
	//echo print_r($col);
	$list=$this->getDynamic($tablename,$where,$orderby);
	while($rs=$this->nextData($list))
	$str .= "<option value='".$rs[$datavaluefield]."' ".(($rs[$datavaluefield]==$matchSelected)?"  selected='selected'":" ").'>&nbsp;&nbsp;&nbsp;&raquo;&nbsp;'.$rs[$datatextfield]."</option>";

				 $str.="</select>";
				 return $str;
			}
			//
	function generateRecursiveSelect($idName,$datatextfield,$datavaluefield,$matchSelected,$tablename,$level="0",$arrayOption=null)
	{
		$str = '<select' . (isset($arrayOption['onchange']) && !empty($arrayOption['onchange']) ? ' onchange="' . $arrayOption['onchange'] . '"' : '') . ' name="' . $idName . '" id="' . $idName . '" class="cbo">';

	if(isset($arrayOption["firstText"]) && $arrayOption["firstText"] != "")
		$str.="<option value=\"\" >-- ".$arrayOption["firstText"]." --</option>";
	else $str.="<option value='0' >-- Root --</option>";

	$col[$datatextfield]=$datatextfield;
	$col[$datavaluefield]=$datavaluefield;
	//echo print_r($col);
	$list=$this->recursive($tablename,"ordering, id",$level,$col);
	foreach($list as $k=>$rs) $str .= "<option value='".$rs[$datavaluefield]."' ".(($rs[$datavaluefield]==$matchSelected)?"  selected='selected'":" ").'>'.$rs[$datatextfield]."</option>";

				 $str.="</select>";
				 return $str;
			}
		//
		function generateRecursiveSelect2($idName,$datatextfield,$datavaluefield,$matchSelected,$tablename,$level="0",$arrayOption=null)
		{
$str="<select onchange=\"".$arrayOption["onchange"]."\" name='".$idName."' id='".$idName."' class='cbo'>";

if($arrayOption["firstText"]!="")
$str.="<option value='0' >-- ".$arrayOption["firstText"]." --</option>";
else $str.="<option value='0' >-- Root --</option>";

$col[$datatextfield]=$datatextfield;
$col[$datavaluefield]=$datavaluefield;
//echo print_r($col);
$list=$this->recursive2($tablename,"parentid asc",$level,$col);
foreach($list as $k=>$rs) $str .= "<option value='".$rs[$datavaluefield]."' ".(($rs[$datavaluefield]==$matchSelected)?"  selected='selected'":" ").'>'.$rs[$datatextfield]."</option>";

			 $str.="</select>";
			 return $str;
		}
		
	function generateRecursiveSelect3($idName,$datatextfield,$datavaluefield,$matchSelected,$tablename,$condition='1=1',$level="0",$arrayOption=null)
	{
	  $str="<select onchange=\"".$arrayOption["onchange"]."\" name='".$idName."' id='".$idName."' class='cbo'>";	  
	  if($arrayOption["firstText"]!="")
	  $str.="<option value='0' >-- ".$arrayOption["firstText"]." --</option>";
	  else $str.="<option value='0' >-- Root --</option>";	  
	  $col[$datatextfield]=$datatextfield;
	  $col[$datavaluefield]=$datavaluefield;
	  $list=$this->recursive3($tablename,$condition,"ordering, id",$level,$col);
	  foreach($list as $k=>$rs) $str .= "<option value='".$rs[$datavaluefield]."' ".(($rs[$datavaluefield]==$matchSelected)?"  selected='selected'":" ").'>'.$rs[$datatextfield]."</option>";	  
	  $str.="</select>";
	  return $str;
	}

	function generateChoiceFile($idFile, $idHidden, $file = '')
	{
		if(empty($file) || $file == '') $file = 'No file.';
		echo '<table border="0" cellpadding="0" cellspacing="0">
		<tr><td>' . $file . '</td></tr>';
		echo '<tr>
		<td valign="bottom" style="padding-left:5px;">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td class="btnleft">&nbsp;</td>
					<td><input name="btn1" class="btncenter" type="button" onClick="modelessDialogShow(\'/_pnsdotvn/assetmanager/assetmanager.php?ffilter=image&object1=' . $idHidden . '&object2=' . $idFile . '&type=1&callback=\',1000,550);" id="btn1" value="Browse..." /></td>
					<td class="btnright">&nbsp;</td>
				</tr>
			</table>
		</td>
		</tr>
		<tr>
			<td style="padding-top:5px;">
				<input type="checkbox" onClick="showPath(this.checked,\'' . $idHidden . '\');" value="1" />Hiện đường dẫn <br>
				<input name="' . $idHidden . '" id="' . $idHidden . '" style="width:140px;display:none" type="text" class="nd1" value="' . $file . '" />
			</td>
		</tr>
		</table>';
	} 
				
				function generateChoicePicture($idPicture,$idHidden,$picture="")
				{
					if($picture=="") $picture="/media/images/others/imagehere.png";
				echo "<table border='0' width='150' cellpadding='0' cellspacing='0'>
						  <tr>
							<td></td>
							<td width='20%'>";

								  $mangExt= pathinfo($picture);
								  $ext=strtolower($mangExt['extension']);
								  echo "<div id='pro_photo_swf'>";
								  if(in_array($ext, array('swf', 'doc', 'docx', 'xls', 'xlsx', 'pdf')))
								  echo "<img style='border:1px solid #333' name='".$idPicture."' id='".$idPicture."' src='resize.php?from=" . ((!empty($picture) && $picture<>'/media/images/others/download_icon.png') ? 'assetmanager/images/' . $ext . '_icon.png' : '/media/images/others/download_icon.png') . "&w=50&h=50'  border='0'>";
								  else
								  echo "<img style='border:1px solid #333' name='".$idPicture."' id='".$idPicture."' src='resize.php?from=".pathPicture.$picture."&w=50&h=50' border='0' />";
								  echo "</div>";
				echo "
				</td>
				<td  valign='bottom' style='padding-left:5px;'>
				<table border='0' cellpadding='0' cellspacing='0'>
					<tr>
					  <td class='btnleft'></td>
					  <td><input name='btn1' class='btncenter' type='button' onClick=\"modelessDialogShow('/_pnsdotvn/assetmanager/assetmanager.php?ffilter=image&object1=$idHidden&object2=$idPicture&type=1&callback=',1000,550);\" id='btn1' value='Browse...' /></td>
					  <td class='btnright'></td>
					</tr>
				  </table>
				</td>
				</tr>
				<tr>
				  <td></td>
				  <td colspan='2' style='padding-top:5px;'>
				  <input type='checkbox' onClick=\"showPath(this.checked,'".$idHidden."');\" value='1' />Hiện đường dẫn <br>
				<input name='".$idHidden."' id='".$idHidden."' style='width:140px;display:none' type='text' ".$this->focusText()." class='nd1' value='".$picture."' />
				  </td>
				</tr>
				</table>";
				}

				//
               function recursive($tablename,$orderby,$parentid = 0,$col=null,$space = '&nbsp;&nbsp;', $trees = NULL)
              	{
              	    if(!$trees) $trees = array();
                    $rst=$this->getDynamic($tablename,"parentid=$parentid",$orderby);
              		while($rs =$this->nextData($rst))
              		{
						if($col==null) //Truong hop nay chi lam khi minh truyen vao cac cot muon lay
						{
              			$trees[] = array('id'=>$rs['id'],'name'=>$space.$rs['name'],'picture'=>$rs['picture'],'parentid'=>$rs['parentid'],'iscat'=>$rs['iscat'],'position'=>$rs['position'],'status'=>$rs['status'],'display'=>$rs['display']);			}
						else
						{
							foreach($col as $key=>$value)
							{
								if(in_array($key,array("name","title","title_vn","name_vn")))
								$tmp[$key]=$space.$rs[$key];
								else $tmp[$key]=$rs[$key];
							}

							$trees[]=$tmp;
						}
              		$trees = $this->recursive($tablename,$orderby,$rs['id'],$col,$space.'&nbsp;=>&nbsp;',$trees);

              		}
              		return $trees;
              	}
				function recursive3($tablename,$condition='',$orderby,$parentid = 0,$col=null,$space = '&nbsp;&nbsp;', $trees = NULL)
              	{
              	    if(!$trees) $trees = array();
					$where = (!empty($condition)) ? $condition . ' and ' : '';
                    $rst=$this->getDynamic($tablename,$where . "parentid=$parentid",$orderby);
              		while($rs =$this->nextData($rst))
              		{
						if($col==null) //Truong hop nay chi lam khi minh truyen vao cac cot muon lay
						{
              			$trees[] = array('id'=>$rs['id'],'name'=>$space.$rs['name'],'picture'=>$rs['picture'],'parentid'=>$rs['parentid'],'iscat'=>$rs['iscat'],'position'=>$rs['position'],'status'=>$rs['status'],'display'=>$rs['display']);			}
						else
						{
							foreach($col as $key=>$value)
							{
								if(in_array($key,array("name","title","title_vn","name_vn")))
								$tmp[$key]=$space.$rs[$key];
								else $tmp[$key]=$rs[$key];
							}

							$trees[]=$tmp;
						}
              		$trees = $this->recursive3($tablename,$condition,$orderby,$rs['id'],$col,$space.'&nbsp;=>&nbsp;',$trees);

              		}
              		return $trees;
              	}
				function recursives($tablename,$orderby,$parentid = 0,$col=null,$space = '&nbsp;&nbsp;', $trees = NULL)
              	{
              	    if(!$trees) $trees = array();
                    $rst=$this->getDynamic($tablename,"parentid=$parentid",$orderby);
              		while($rs =$this->nextData($rst))
              		{
						if($col==null) //Truong hop nay chi lam khi minh truyen vao cac cot muon lay
						{
              			$trees[] = array('id'=>$rs['id'],'name'=>$space.$rs['name'],'picture'=>$rs['picture'],'parentid'=>$rs['parentid'],'iscat'=>$rs['iscat'],'position'=>$rs['position'],'status'=>$rs['status'],'display'=>$rs['display']);			}
						else
						{
							foreach($col as $key=>$value)
							{
								if(in_array($key,array("name","title","title_vn","name_vn")))
								$tmp[$key]=$space.$rs[$key];
								else $tmp[$key]=$rs[$key];
							}

							$trees[]=$tmp;
						}
					if($rs['parentid']!=0)
              		$trees = $this->recursive($tablename,$orderby,$rs['id'],$col,$space.'&nbsp;=>&nbsp;',$trees);

              		}
              		return $trees;
              	}
				function recursiveJoin($tablename1,$tablename2,$arrayNewName,$typeJoin="inner join",$on,$orderby,$parentid = 0,$col=null,$space = '&nbsp;&nbsp;', $trees = NULL)
              	{
              	    if(!$trees) $trees = array();
                    $rst=$this->getDynamicJoin($tablename1,$tablename2,$arrayNewName,$typeJoin,"t1.parentid=$parentid",$orderby,$on);
              		while($rs =$this->nextData($rst))
              		{
						if($col==null) //Truong hop nay chi lam khi minh truyen vao cac cot muon lay
						{
              			$trees[] = array('id'=>$rs['id'],'name'=>$space.$rs['name'],'picture'=>$rs['picture'],'parentid'=>$rs['parentid'],'iscat'=>$rs['iscat'],'position'=>$rs['position'],'status'=>$rs['status'],'display'=>$rs['display']);			}
						else
						{
							foreach($col as $key=>$value)
							{
								if(in_array($key,array("name","title","title_vn","name_vn")))
								$tmp[$key]=$space.$rs[$key];
								else $tmp[$key]=$rs[$key];
							}

							$trees[]=$tmp;
						}
					if($rs['parentid']!=0)
              		$trees = $this->recursiveJoin($tablename1,$tablename2,$arrayNewName,$typeJoin,$on,$orderby,$rs['id'],$col,$space.'&nbsp;=>&nbsp;',$trees);

              		}
              		return $trees;
              	}
				//
				function recursive2($tablename,$orderby,$parentid = 0,$col=null,$space = '&nbsp;&nbsp;', $trees = NULL)
              	{
              	    if(!$trees) $trees = array();
                    $rst=$this->getDynamic($tablename,"parentid=$parentid and iscat=1",$orderby);
              		while($rs =$this->nextData($rst))
              		{
						if($col==null) //Truong hop nay chi lam khi minh truyen vao cac cot muon lay
						{
              			$trees[] = array('id'=>$rs['id'],'name'=>$space.$rs['name'],'picture'=>$rs['picture'],'parentid'=>$rs['parentid'],'iscat'=>$rs['iscat'],'position'=>$rs['position'],'status'=>$rs['status'],'display'=>$rs['display']);			}
						else
						{
							foreach($col as $key=>$value)
							{
								if(in_array($key,array("name","title","title_vn","name_vn")))
								$tmp[$key]=$space.$rs[$key];
								else $tmp[$key]=$rs[$key];
							}

							$trees[]=$tmp;
						}

              		$trees = $this->recursive2($tablename,$orderby,$rs['id'],$col,$space.'&nbsp;=>&nbsp;',$trees);
					//echo print_r($trees)."<br><br>";
              		}
              		return $trees;
              	}
				//
            function showIndex()
            {
              $index=$this->getDynamic(prefixTable."menu_admin","status=1 and parentid=0 and display=1","parentid asc,position asc");
              while($rowindex=$this->nextData($index))
              {
              $parentid=$rowindex[0];
              echo "<div id='box' ".ALPHA8." onclick=\"window.location='$rowindex[2]';\" style=\"\">
              <div id='boxTop'><div style='height:50px;margin-top:5px;padding-top:15px;opacity:0.80;filter:alpha(opacity=80);moz-opacity:0.80;background:url(".ADMIN_ZONE.$rowindex[4].") 13px 0px no-repeat;'>$rowindex[2]</div></div>";
              echo "<div id='boxMiddle'><div>";
              $indexsub=$this->getDynamic(prefixTable."menu_admin","parentid<>0 and status=1 and parentid=$parentid","parentid asc,position asc limit 0,3");
              while($rowindexsub=$this->nextData($indexsub))
              {
                  echo "<div id='item'><a id='item' href='".$rowindexsub[3]."'>".stripcslashes($rowindexsub[2])."</a></div>";
              }
              echo "</div><div id='clear'></div></div>";
              echo "<div id='boxBottom'></div><div id='clear'></div>
              </div>";
            }
            echo "<div id='clear'></div>";
            }
            //
			function panelAction($mang, $f1 = true, $f2 = true, $f3 = true, $f4 = true)
			{
				$str="
				<div id='panelAction' class='panelAction'>
                    <div style='width:475px;float:left;height:17px;padding-top:5px;border:0px solid;'>".$mang."</div>
					<div class='panelActionContent'>
					<table id='panelTable' cellpadding='0' cellspacing='0'>
						<tr>";
							$str .= $f1 == true ? "<td class='cellAction1'>".selectIMG."</td><td class='cellAction'>".selectText."</td>" : '';
							$str .= $f2 == true ? "<td class='cellAction1'>".insertIMG."</td><td class='cellAction'>".insertText."</td>" : '';
							$str .= $f3 == true ? "<td class='cellAction1'>".deleteIMG."</td><td class='cellAction'>".deleteText."</td>" : '';
							$str .= $f4 == true ? "<td class='cellAction1'>".viewIMG."</td><td class='cellAction11'>".viewText."</td>" : '';
				$str.=	"</tr>
					</table>
					</div>
				</div>";
				return $str;
			}
			//
			function panelActionView($mang)
			{
				$str="
				<div id='panelAction' class='panelAction'>
                    <div style='width:475px;float:left;height:17px;padding-top:5px;border:0px solid;'>".$mang."</div>
					<!--<div class='panelActionContent'>
					<table id='panelTable' cellpadding='0' cellspacing='0' width='200' align='right'>
						<tr>
							<td class='cellAction1'>".selectIMG."</td><td class='cellAction'>".selectText."</td>
							<td class='cellAction1'>".deleteIMG."</td><td class='cellAction'>".deleteText."</td><td class='cellAction1'>".viewIMG."</td><td class='cellAction11'>".viewText."</td>
						</tr>
					</table>
					</div>-->
				</div>";
				return $str;
			}
			//
			function panelActionNoneAdd($mang)
			{
				$str="
				<div id='panelAction' class='panelAction'>
                    <div style='width:475px;float:left;height:17px;padding-top:5px;border:0px solid;'>".$mang."</div>
					<div class='panelActionContent'>
					<table id='panelTable' cellpadding='0' cellspacing='0'>
						<tr>
							<td class='cellAction1'>".selectIMG."</td><td class='cellAction'>".selectText."</td>			
							<td class='cellAction1'>".deleteIMG."</td><td class='cellAction'>".deleteText."</td>
							<td class='cellAction1'>".viewIMG."</td><td class='cellAction11'>".viewText."</td>
						</tr>
					</table>
					</div>
				</div>";
				return $str;
			}
			//
            function generateHeader($page)
            {
              $str=
              "<table width='95%' align='center'  border='0' style='background-repeat:no-repeat;border-bottom:3px double #3f5f7f;' cellpadding='0' cellspacing='0'>
			  	<tr><td id='topcenter'>
					<div id='topleft'></div>
					<div id='topright'></div>
				</td></tr>
                  <tr>
                    <td  valign='top' class='header' >
                    <table  width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>
                      <tr>
                  	  <td width='100%' class='titleAdmin'><div>Welcome [ ".$_SESSION['user'].", <a id='welcome' href='#' onclick=\"window.location='logout.php';\">Sign Out</a> ] - Quyền truy cập [ ".(($_SESSION['admission']=="Admin")?"Administrator":"User")." ]&nbsp;-&nbsp;<a id='welcome' href='./'>Trang chủ</a></div></td>
                      </tr>
                    </table>
                   </td>
                  </tr>
                  <tr><td colspan='3' class='menu'>";
                  $str.=$this->generateMenu($page);
                  $str.="</td></tr></table>";

              return $str;
            }
            function generateMenu($page)
            {
				  $strmenu = '';
                  $img="<img class='bullet' src='images/bullet_white.jpg' border='0'>";
                  $menu=$this->getDynamic(prefixTable.'menu_admin','parentid=0 and status=1','position asc');
                  $i=1;
                  while($rowmenu=$this->nextData($menu))
                  {
				  	 $pageurl=$rowmenu['pageurl'];
				  	 $select=($pageurl==$page)?"top_menu_select":"top_menu";
                      $strmenu .="<div id='cellmenu$i' class='cellmenu'>";
                      $strmenu .=$this->link($rowmenu['name'],$pageurl,array("id"=>"admin".$rowmenu['id'],"class"=>$select));
      			      $strmenu .="</div>";
                      $mang[$i]=$rowmenu['id'];
                      $i++;
                  }
                  $strmenu .= "<script language='javascript' type='text/javascript'> \n ";
                  $strmenu .= "if(TransMenu.isSupported()) \n";
				  $strmenu .="if(navigator.appVersion.toString().indexOf('Chrome')!=-1 || navigator.appVersion.toString().indexOf('Safari')!=-1)";
                  $strmenu .=" var ms = new TransMenuSet(TransMenu.direction.down, 0,10, TransMenu.reference.bottomLeft); \n ";
				  $strmenu .=" else ";
				  $strmenu .=" var ms = new TransMenuSet(TransMenu.direction.down, 0,0, TransMenu.reference.bottomLeft); \n ";
                  //
            		$i--;
            		while($i>0)
            		{


            			$submenu=$this->getDynamic(prefixTable."menu_admin","status=1 and parentid<>0 and parentid=".$mang[$i],"parentid asc,position asc");
						if($this->totalRows($submenu)>0)
						$strmenu .= " var cellmenu".$i." = ms.addMenu(document.getElementById('cellmenu".$i."')); \n ";
            			while($rowsubmenu=$this->nextData($submenu))
            			{
            				$strmenu.="cellmenu$i.addItem(\"".$img."<font class=font_title>".$rowsubmenu[2]."</font>\", \"".$rowsubmenu[3]."\"); \n";
            			}
            			$i--;
            		}

          		$strmenu .=" TransMenu.renderAll(); \n";
                $strmenu .=" </script> \n";

            return $strmenu;
            }
		function returnTitleMenuTable($str)
            {
                $str="<table align='center' width='95%' cellpadding='0' cellspacing='0'><tr><td colspan='5' class='boxRedInside'><div class='boxRedInside'>".$str."</div></td></tr></table>";
                return $str;
            }

            function returnTitleMenu($str)
            {
                $str="<tr><td colspan='5' class='boxRed'><div class='boxRed'>".$str."</div></td></tr>";
                return $str;
            }
            function focusText()
            {
                $str=" onFocus='fo(this);' onBlur='lo(this);' ";
                return $str;
            }
            function stateInsert()
            {
                $str="
                <table border='0' cellpadding='0' cellspacing='2' style='padding-bottom:8px;padding-top:5px;'>
				<tr><td>
					<table border='0' cellpadding='0' cellspacing='0'>
    					<tr><td class='btnleft'></td><td>
    					<input name='subinsert' id='subinsert' type='submit' class='btncenter'  value='Thêm'/></td>
    					<td class='btnright'></td>
    					</tr>
    					</table>
    					</td>
    					<td>
    					<table border='0' cellpadding='0' cellspacing='0'>
    					<tr>
    					<td class='btnleft'></td>
    					<td><input name='reset' type='reset' class='btncenter' value='Nhập lại'/></td>
    					<td class='btnright'></td>
    					</tr>
				    </table>
                </td>
				<td>
					<table border='0' cellpadding='0' cellspacing='0'>
					<tr>
					<td class='btnleft'></td>
					<td> <input name='btnhuy' id='btnhuy' type='button' onClick='huy();' class='btncenter'  value='Xem'/></td>
					<td class='btnright'></td>
					</tr>
				</table></td>
				</tr>
				</table>";
                return $str;
            }
            function stateUpdate()
            {
                $str="
                <table border='0' cellpadding='0' cellspacing='2' style='padding-bottom:8px;padding-top:5px;'>
				<tr><td>
					<table border='0' cellpadding='0' cellspacing='0'>
					<tr>
					<td class='btnleft'></td>
					<td>
					 <input name='subupdate' id='subupdate' type='submit' class='btncenter'  value='Cập nhật'/></td>
					<td class='btnright'></td>
					</tr>
					</table>
					</td>
					<td>
					<table border='0' cellpadding='0' cellspacing='0'>
					<tr>
					<td class='btnleft'></td>
					<td> <input name='btnhuy' id='btnhuy'  type='button' onClick='huy();' class='btncenter'  value='Hủy'/></td>
					<td class='btnright'></td>
					</tr>
				</table></td></tr>
				</table>";
                return $str;
            }
            //
    //************ Hm phn trang **********
    function paging($tablename,$where,$orderby,$url,$PageNo,$PageSize,$Pagenumber,$ModePaging)
    {
    // ModePaging 1: Full  ---------->  << | < | > | >>
    // ModePaging 2: Next  ---------->  <
    // ModePaging 3: Previous ------->  >
    if($PageNo=="")
    	{ 	$StartRow = 0; 	$PageNo = 1; 	}
    else
    	$StartRow = ($PageNo - 1) * $PageSize;

    //Set the counter start
    if($PageNo % $Pagenumber == 0)
    	$CounterStart = $PageNo - ($Pagenumber - 1);
    else
    	$CounterStart = $PageNo - ($PageNo % $Pagenumber) + 1;

    $CounterEnd = $CounterStart + $Pagenumber;
    //$TRecord = mysql_query($sql) or die(mysql_error());
    $TRecord=$this->getDynamic($tablename,$where,$orderby);
    //$sql.= " LIMIT $StartRow, $PageSize";
    //$result = mysql_query($sql) or die(mysql_error());
    $result=$this->getDynamic($tablename,$where,$orderby." LIMIT ".$StartRow.",". $PageSize);

    $RecordCount = mysql_num_rows($TRecord);
    if($RecordCount % $PageSize == 0)
    	$MaxPage = $RecordCount / $PageSize;
    else
    	$MaxPage = ceil($RecordCount / $PageSize);
    $gotopage="";

    switch ($ModePaging) {
    case "Full":
    	$gotopage = '<div class="paging_meneame">';

    	if($MaxPage>1)	{

    		// Print First page, Prev page
    		if($PageNo != 1) {
    			$PrevStart = $PageNo - 1;
    			$gotopage.=' <a href="'.$url.'&PageNo=1" tile="First page"> &laquo; </a>';
    			$gotopage.=' <a href="'.$url.'&PageNo='.$PrevStart.'" title="Previous page"> &lsaquo; </a>';
    		}
    		else {
    			$gotopage.=' <span class="paging_disabled"> &laquo; </span>';
    			$gotopage.=' <span class="paging_disabled"> &lsaquo; </span>';
    		}
    		$c = 0;

    		//Print Page No. 1, 2, 3, ...
    		for($c=$CounterStart;$c<$CounterEnd;$c++){
    			if($c <= $MaxPage)
    				if($c == $PageNo)
    					$gotopage.= '<span class="paging_current"> '.$c.' </span>';
    				else
    					$gotopage.= ' <a href="'.$url.'&PageNo='.$c.'" title="Page '.$c.'"> '.$c.' </a>';
    		}

    		//Print Next page
    		if($PageNo < $MaxPage){
    			$NextPage = $PageNo + 1;
    			$gotopage.= ' <a href="'.$url.'&PageNo='.$NextPage.'" title="Next page"> &rsaquo; </a>';
    		}
    		else {
    			$gotopage.= ' <span class="paging_disabled"> &rsaquo; </span>';
    		}

    		//Print Last page
    		if($PageNo < $MaxPage)
    			$gotopage.= ' <a href="'.$url.'&PageNo='.$MaxPage.'" title="Last page"> &raquo; </a>';
    		else
    			$gotopage.= ' <span class="paging_disabled"> &raquo; </span>';
    	}

    	$gotopage.= ' </div>';

    	break;
    case "<":
    	// Print First page, Prev page
    	if(($MaxPage>1)&($PageNo != 1)) {
    		$PrevStart = $PageNo - 1;

    		$gotopage.="<A id='paging' title=\"Back\" href=\"$url&PageNo=$PrevStart";
    		$gotopage.="\" ";
    		$gotopage.="\"onMouseOut=\"MM_swapImgRestore()\" ";
    		$gotopage.="onMouseOver=\"MM_swapImage('back','','images/back_2.gif',1)\" >";
    		$gotopage.="<img src=\"images/back_1.gif\" alt=\"Back\" name=\"back\" ";
    		$gotopage.="width=\"14\" height=\"130\" border=\"0\"> ";
    		$gotopage.="</A>";
    	}
    	else {
    		$gotopage.="<img src=\"images/back_1.gif\" alt=\"Back\" name=\"back\" ";
    		$gotopage.="width=\"14\" height=\"130\" border=\"0\"> ";
    	}
    	break;

    case ">":
    	if(($MaxPage>1)&($PageNo < $MaxPage)) {
    		$NextPage = $PageNo + 1;

    		$gotopage.="<A id='paging' title=\"Next\" href=\"$url&PageNo=$NextPage";
    		$gotopage.="\" ";
    		$gotopage.="\"onMouseOut=\"MM_swapImgRestore()\" ";
    		$gotopage.="onMouseOver=\"MM_swapImage('next','','images/next_2.gif',1)\" >";

    		$gotopage.="<img src=\"images/next_1.gif\" alt=\"Next\" name=\"next\" ";
    		$gotopage.="width=\"14\" height=\"130\" border=\"0\"> ";
    		$gotopage.="</A>";
    	}
    	else {
    		$gotopage.="<img src=\"images/next_1.gif\" alt=\"Next\" name=\"next\" ";
    		$gotopage.="width=\"14\" height=\"130\" border=\"0\"> ";
    	}
    	break;

    } //END OF SWITCH
    $arr[0]=$result;
    $arr[1]=$gotopage;
    return $arr;
    } //END OF PAGING

	//Paging join

   function pagingJoin($tablename1,$tablename2,$arrayNewName,$typejoin="inner join",$where,$orderby,$url,$PageNo,$PageSize,$Pagenumber,$ModePaging,$on)
    {
    // ModePaging 1: Full  ---------->  << | < | > | >>
    // ModePaging 2: Next  ---------->  <
    // ModePaging 3: Previous ------->  >
    if($PageNo=="")
    	{ 	$StartRow = 0; 	$PageNo = 1; 	}
    else
    	$StartRow = ($PageNo - 1) * $PageSize;

    //Set the counter start
	if($PageSize<=0) $PageSize=1;

    if($PageNo % $Pagenumber == 0)
    	$CounterStart = $PageNo - ($Pagenumber - 1);
    else
    	$CounterStart = $PageNo - ($PageNo % $Pagenumber) + 1;

    $CounterEnd = $CounterStart + $Pagenumber;
    //$TRecord = mysql_query($sql) or die(mysql_error());
    @$TRecord=$this->getDynamicJoin($tablename1,$tablename2,$arrayNewName,$typejoin,$where,$orderby,$on);
    //$sql.= " LIMIT $StartRow, $PageSize";
    //$result = mysql_query($sql) or die(mysql_error());
    @$result=$this->getDynamicJoin($tablename1,$tablename2,$arrayNewName,$typejoin,$where,$orderby." LIMIT ".$StartRow.",". $PageSize,$on);

    @$RecordCount = mysql_num_rows($TRecord);
    if($RecordCount % $PageSize == 0)
    	$MaxPage = $RecordCount / $PageSize;
    else
    	$MaxPage = ceil($RecordCount / $PageSize);
    $gotopage="";

    switch ($ModePaging) {
    case "Full":
    	$gotopage = '<div class="paging_meneame">';

    	if($MaxPage>1)	{

    		// Print First page, Prev page
    		if($PageNo != 1) {
    			$PrevStart = $PageNo - 1;
    			$gotopage.=' <a href="'.$url.'&PageNo=1" tile="First page"> &laquo; </a>';
    			$gotopage.=' <a href="'.$url.'&PageNo='.$PrevStart.'" title="Previous page"> &lsaquo; </a>';
    		}
    		else {
    			$gotopage.=' <span class="paging_disabled"> &laquo; </span>';
    			$gotopage.=' <span class="paging_disabled"> &lsaquo; </span>';
    		}
    		$c = 0;

    		//Print Page No. 1, 2, 3, ...
    		for($c=$CounterStart;$c<$CounterEnd;$c++){
    			if($c <= $MaxPage)
    				if($c == $PageNo)
    					$gotopage.= '<span class="paging_current"> '.$c.' </span>';
    				else
    					$gotopage.= ' <a href="'.$url.'&PageNo='.$c.'" title="Page '.$c.'"> '.$c.' </a>';
    		}

    		//Print Next page
    		if($PageNo < $MaxPage){
    			$NextPage = $PageNo + 1;
    			$gotopage.= ' <a href="'.$url.'&PageNo='.$NextPage.'" title="Next page"> &rsaquo; </a>';
    		}
    		else {
    			$gotopage.= ' <span class="paging_disabled"> &rsaquo; </span>';
    		}

    		//Print Last page
    		if($PageNo < $MaxPage)
    			$gotopage.= ' <a href="'.$url.'&PageNo='.$MaxPage.'" title="Last page"> &raquo; </a>';
    		else
    			$gotopage.= ' <span class="paging_disabled"> &raquo; </span>';
    	}

    	$gotopage.= ' </div>';

    	break;
    case "<":
    	// Print First page, Prev page
    	if(($MaxPage>1)&($PageNo != 1)) {
    		$PrevStart = $PageNo - 1;

    		$gotopage.="<A id='paging' title=\"Back\" href=\"$url&PageNo=$PrevStart";
    		$gotopage.="\" ";
    		$gotopage.="\"onMouseOut=\"MM_swapImgRestore()\" ";
    		$gotopage.="onMouseOver=\"MM_swapImage('back','','images/back_2.gif',1)\" >";
    		$gotopage.="<img src=\"images/back_1.gif\" alt=\"Back\" name=\"back\" ";
    		$gotopage.="width=\"14\" height=\"130\" border=\"0\"> ";
    		$gotopage.="</A>";
    	}
    	else {
    		$gotopage.="<img src=\"images/back_1.gif\" alt=\"Back\" name=\"back\" ";
    		$gotopage.="width=\"14\" height=\"130\" border=\"0\"> ";
    	}
    	break;

    case ">":
    	if(($MaxPage>1)&($PageNo < $MaxPage)) {
    		$NextPage = $PageNo + 1;

    		$gotopage.="<A id='paging' title=\"Next\" href=\"$url&PageNo=$NextPage";
    		$gotopage.="\" ";
    		$gotopage.="\"onMouseOut=\"MM_swapImgRestore()\" ";
    		$gotopage.="onMouseOver=\"MM_swapImage('next','','images/next_2.gif',1)\" >";

    		$gotopage.="<img src=\"images/next_1.gif\" alt=\"Next\" name=\"next\" ";
    		$gotopage.="width=\"14\" height=\"130\" border=\"0\"> ";
    		$gotopage.="</A>";
    	}
    	else {
    		$gotopage.="<img src=\"images/next_1.gif\" alt=\"Next\" name=\"next\" ";
    		$gotopage.="width=\"14\" height=\"130\" border=\"0\"> ";
    	}
    	break;

    } //END OF SWITCH
    $arr[0]=$result;
    $arr[1]=$gotopage;
    return $arr;
    } //END OF PAGING


    //PAGING AJAX
    function pagingAjaxloadFinal($tablename,$where,$orderby,$url,$PageNo,$PageSize,$Pagenumber,$ModePaging)
    {
    // ModePaging 1: Full  ---------->  << | < | > | >>
    if($PageNo=="")
    	{ 	$StartRow = 0; 	$PageNo = 1; 	}
    else
    	$StartRow = ($PageNo - 1) * $PageSize;

    //Set the counter start
    if($PageNo % $Pagenumber == 0)
    	$CounterStart = $PageNo - ($Pagenumber - 1);
    else
    	$CounterStart = $PageNo - ($PageNo % $Pagenumber) + 1;

    $CounterEnd = $CounterStart + $Pagenumber;
    $TRecord=$this->getDynamic($tablename,$where,$orderby);
    //$sql.= " LIMIT $StartRow, $PageSize";
    //$result = mysql_query($sql) or die(mysql_error());
    $result=$this->getDynamic($tablename,$where,$orderby." LIMIT ".$StartRow.",". $PageSize);

    $RecordCount = mysql_num_rows($TRecord);
    if($RecordCount % $PageSize == 0)
    	$MaxPage = $RecordCount / $PageSize;
    else
    	$MaxPage = ceil($RecordCount / $PageSize);
    $gotopage="";

    switch ($ModePaging) {
    case "Full":
    	if($MaxPage>1)	{
    		// Print First page, Prev page
    		if($PageNo != 1)
    		{
    		$PrevStart = $PageNo - 1;
    		//$url&PageNo=1
    		$gotopage.=" <a id='paging' href='#page' onclick=\"loadPaging('$url&PageNo=1');\" tile=\"First\">". "&laquo;&laquo;" ."</a> | ";
    		//$url&PageNo=$PrevStart
    		$gotopage.=" <a id='paging' href='#page' onclick=\"loadPaging('$url&PageNo=$PrevStart');\" title=\"Previous\">". "&laquo;" ."</a> | ";
    		}
    		else $gotopage.=" <span style='color:#333333'> &laquo;&laquo; | &laquo; | </span>";
    		$c = 0;
    		//Print Page No. 1, 2, 3, ...
    		for($c=$CounterStart;$c<$CounterEnd;$c++){
    			if($c <= $MaxPage)
    				if($c == $PageNo)
    					$gotopage.= "<span style='font-size:11px;color:#f15a22'>$c</span> | ";
    				else
    					$gotopage.=" <a id='paging' onclick=\"loadPaging('$url&PageNo=$c');\" style=\"text-decoration: none\" href='#page' title=\"Page $c\">$c</a> | ";//$url&PageNo=$c
    		}
    		//Print Next page
    		if($PageNo < $MaxPage){
    			$NextPage = $PageNo + 1;
    			$gotopage.=" <a id='paging' onclick=\"loadPaging('$url&PageNo=$NextPage');\" style=\"text-decoration: none\" href='#page' title=\"Next\">". "&raquo;" ."</a> | ";
    		}
    		else
    			$gotopage.="<span style='font-size:11px;color:#333333'>&raquo; | </span>";
    		//Print Last page
    		if($PageNo < $MaxPage)
    			$gotopage.= " <a id='paging' onclick=\"loadPaging('$url&PageNo=$MaxPage');\" style=\"text-decoration: none\" href='#page' title=\"Last\">". "&raquo;&raquo;" ."</a> ";//$url&PageNo=$MaxPage
    		else
    			$gotopage.="<span style='font-size:11px;color:#333333'>&raquo;&raquo;</span>";
    	}
    	break;

      } //END OF SWITCH

      $arr[0]=$result;
      $arr[1]=$gotopage;
      return $arr;
      }///
	  
	function getAliasArray($id, & $alias=""){
		$rst=$this->getDynamic(prefixTable."category","status=1 and id='{$id}'","");
		if($this->totalRows($rst)){
		  $row=$this->nextObject($rst);
		  $alias[][$row->name]=$row->alias;
		  $this->getAliasArray($row->parentid,$alias);
		}
		krsort($alias);
		if(is_array($alias) && count($alias) > 0) {
		  foreach($alias as $item) {
			foreach($item as $key => $value) {
			  $new[$key] = $value;	
			}
		  }
		}
		return $new;
	}
	
	function getMenuAliasArray($id, $lang = 'vi-VN', & $alias = '')
	{
		$rst = $this->getDynamicJoin(prefixTable . 'menu_desc', prefixTable . 'menu', array('parentid' => 'parentid'), 'inner join', 't2.status = 1 and t2.id = ' . $id . ' and t1.lang = "' . $lang . '"', '', 't2.id = t1.id');
		if($this->totalRows($rst))
		{
		  	$row = $this->nextObject($rst);
		  	$alias[][$row->name] = $row->rewrite;
		  	$this->getMenuAliasArray($row->parentid, $lang, $alias);
		}
		krsort($alias);
		if(is_array($alias) && count($alias) > 0)
		{
		  	foreach($alias as $item)
			{
				foreach($item as $key => $value)
				{
			  		$new[$key] = $value;	
				}
		  	}
		}
		return $new;
	}
	
	function chkChild($id_array) {
	  $flag = false;
	  if(is_array($id_array) && count($id_array) > 0) {
		foreach($id_array as $id) {
		  $rst = $this->getDynamic(prefixTable . "category","status = 1 and parentid = '{$id}'","");	
		  if($this->totalRows($rst) == 0)
		  {
		    $product = $this->getArray(prefixTable . "product","status = 1 and cid = '{$id}'","","stdObject");
			if(count($product) == 0) $value[] = 1;
			else $value[] = 0;
		  }
		}
		if(!in_array(0,$value)) $flag = true;
	  }
	  return $flag;	  
	}
	
	function chkCMSMenuChild($id_array) {
	  $flag = false;
	  if(is_array($id_array) && count($id_array) > 0) {
		foreach($id_array as $id) {
		  $rst = $this->getDynamic(prefixTable . "cms_menu","status=1 and parentid='" . $id . "'","");	
		  if($this->totalRows($rst) == 0)
		  {
		    $cms = $this->getArray(prefixTable . "cms","status=1 and mid='" . $id . "'","","stdObject");
			if(count($cms) == 0) $value[] = 1;
			else $value[] = 0;
		  }
		}
		if(!in_array(0,$value)) $flag = true;
	  }
	  return $flag;	  
	}		
	
	function selectFilterCMSMenu($idName,$datatextfield,$datavaluefield,$matchSelected,$text="",$tablename,$condition,$orderby,$arrayOption,$level="0")
	{
		$str="
		<div id='panelAction' class='panelAction2'>
			<div style='float:left;height:17px;padding-top:5px;'></div>
			<div class='panelActionContent2'>
			<table id='panelTable' cellpadding='0' cellspacing='0'>
				<tr>
					<td class='cellAction2'>$text ".($this->buildDropdownCMSMenu($idName,$datatextfield,$datavaluefield,$matchSelected,$tablename,$condition,$orderby,$level,$arrayOption))."</td>
				</tr>
			</table>
			</div>
		</div>";
		return $str;
	}
				  
	function buildDropdownCMSMenu($idName,$datatextfield,$datavaluefield,$matchSelected,$tablename,$condition,$orderby,$level="0",$arrayOption=null)
	{
		$onchange = (isset($arrayOption["onchange"]) AND !empty($arrayOption["onchange"])) ? ' onchange="' . $arrayOption["onchange"] . '"' : '';
		$str = "<select" . $onchange . " name=\"".$idName."\" id=\"".$idName."\" class=\"cbo\">";
		if(isset($arrayOption["firstText"]) AND !empty($arrayOption["firstText"])) $str .= "<option value=\"\">-- " . $arrayOption["firstText"] . " --</option>";
		else $str .= "<option value=\"\">-- Root --</option>";
		$col[$datatextfield]=$datatextfield;
		$col[$datavaluefield]=$datavaluefield;
		$list = $this->recursiveCMSMenu($tablename,$condition,$orderby,$level,$col);
		foreach($list as $k=>$rs)
			$str .= "<option value=\"".$rs[$datavaluefield]."\" ".(($rs[$datavaluefield]==$matchSelected)?"  selected=\"selected\"":" ") . ">" . $rs[$datatextfield] . "</option>";
		$str.="</select>";
		return $str;
	}
	
	function recursiveCMSMenu($tablename,$condition,$orderby,$parentid = 0,$col=null,$space = '&nbsp;&nbsp;', $trees = NULL)
	{
		if(!$trees) $trees = array();
		$rst=$this->getDynamic($tablename,$condition . " and parentid=" . $parentid,$orderby);
		while($rs =$this->nextData($rst))
		{
			if($col==null)
			{
			$trees[] = array('id'=>$rs['id'],'name'=>$space.$rs['name'],'picture'=>$rs['picture'],'parentid'=>$rs['parentid'],'iscat'=>$rs['iscat'],'position'=>$rs['position'],'status'=>$rs['status'],'display'=>$rs['display']);
			}
			else
			{
				foreach($col as $key=>$value)
				{
					if(in_array($key,array("name","title","title_vn","name_vn")))
					$tmp[$key]=$space.$rs[$key];
					else $tmp[$key]=$rs[$key];
				}
				$trees[]=$tmp;
			}
		$trees = $this->recursiveCMSMenu($tablename,$condition,$orderby,$rs['id'],$col,$space.'&nbsp;=>&nbsp;',$trees);
	
		}
		return $trees;
	}
	
	function chkMenuChild($array = null)
	{
	  	$flg = false;
	  	if(is_array($array) && count($array) > 0)
		{
			foreach($array as $id)
			{
		  		$rst = $this->getDynamic(prefixTable . 'menu', 'parentid = ' . $id, '');	
		  		if($this->totalRows($rst) == 0)
		  		{
		    		$cms = $this->getArray(prefixTable . 'cms', 'mid = ' . $id, '', '');
					if(count($cms) == 0)
					{
						$val[] = 1;
					} else
					{
						$val[] = 0;
					}
		  		} else
				{
					$val[] = 0;
				}
			}
			if(!in_array(0, $val)) $flg = true;
	  	}
	  	return $flg;	  
	}

	function buildXMLSitemap($id = 0, & $alias = '', $lang = 'vi-VN', & $xml = '')
	{
		$rst = $this->getDynamicJoin(prefixTable . 'menu', prefixTable . 'menu_desc', array('rewrite' => 'rewrite'), 'inner join', 't1.status = 1 and t1.parentid = ' . $id . ' and t2.lang = "' . $lang . '"', 'id', 't2.id = t1.id');
		if($this->totalRows($rst) > 0)
		{
			while($row = $this->nextObject($rst))
			{
				$row->alias = ($row->parentid == 0) ? $row->rewrite : $alias . '/' . $row->rewrite;
				$href = HOST . '/' . substr($lang, 0, -3) . '/' . $this->addIdtoAlias($row->rewrite, $row->id, $row->parentid) . EXT;
				$href = $href <> (HOST . '/' . substr($lang, 0, -3) . '/trang-chu' . EXT) ? $href : HOST;
				$xml[] = array("loc" => $href,
		  				  	   "lastmod" => date('Y-m-dTH:i:sP', strtotime($row->modified)),
						  	   "changefreq" => "monthly",
						  	   "priority" => "0.90");
				if($row->rewrite == 'san-pham') $this->buildXMLCategory($lang, $xml);
				if(in_array($row->display, array('articles', 'news'))) $this->buildXMLCMS($row->id, $row->rewrite, $lang, $xml);		
				$this->buildXMLSitemap($row->id, $row->alias, $lang, $xml);
			}				
		}
		return $xml;
	}
	
	function buildXMLCategory($lang = 'vi-VN', & $xml = '')
	{		
		$rst = $this->getDynamicJoin(prefixTable . 'category', prefixTable . 'category_desc', array('rewrite' => 'rewrite'), 'inner join', 't1.status = 1 and t2.lang = "' . $lang . '"', 'id', 't2.id = t1.id');		
		if($this->totalRows($rst) > 0)
		{
		  	while($row = $this->nextObject($rst))
			{
				$href = HOST .  '/' . substr($lang, 0, -3) . '/' . $row->id . '-' . $row->rewrite . EXT; 
				$xml[] = array("loc" => $href,
		  				  	   "lastmod" => date('Y-m-dTH:i:sP', strtotime($row->modified)),
						  	   "changefreq" => "weekly",
						  	   "priority" => "1.00");
				$this->buildXMLProduct($row->id, $lang, $xml);
		  	}
		}
		return $xml;
	}
	
	function buildXMLProduct($id, $lang = 'vi-VN', & $xml = '')
	{		
		$rst = $this->getDynamicJoin(prefixTable . 'product', prefixTable . 'product_desc', array('rewrite' => 'rewrite'), 'inner join', 't1.status = 1 and t1.cid = ' . $id . ' and t2.lang = "' . $lang . '"', 'id', 't2.id = t1.id');		
		if($this->totalRows($rst) > 0)
		{
		  	while($row = $this->nextObject($rst))
			{
				$href = HOST .  '/' . substr($lang, 0, -3) . '/san-pham/' . $row->id . '-' . $row->rewrite . EXT; 
				$xml[] = array("loc" => $href,
		  				  	   "lastmod" => date('Y-m-dTH:i:sP', strtotime($row->modified)),
						  	   "changefreq" => "weekly",
						  	   "priority" => "1.00");				
		  	}
		}
		return $xml;
	}
	
	function buildXMLCMS($id, $alias, $lang = 'vi-VN', & $xml = '')
	{		
		$rst = $this->getDynamicJoin(prefixTable . 'cms', prefixTable . 'cms_desc', array('rewrite' => 'rewrite'), 'inner join', 't1.status = 1 and t1.mid = ' . $id . ' and t2.lang = "' . $lang . '"', 'id', 't2.id = t1.id');		
		if($this->totalRows($rst) > 0)
		{
		  	while($row = $this->nextObject($rst))
			{
				$m_array = $this->getMenuAliasArray($row->mid, $lang);
				$href = HOST .  '/' . substr($lang, 0, -3) . '/' . implode('/', $m_array) . '/' . $row->id . '-' . $row->rewrite . EXT; 
				$xml[] = array("loc" => $href,
		  				  	   "lastmod" => date('Y-m-dTH:i:sP', strtotime($row->modified)),
						  	   "changefreq" => "weekly",
						  	   "priority" => "1.00");				
		  	}
		}
		return $xml;
	}
	
	function addIdtoAlias($alias, $id, $pid = 0)
	{
		$array = explode('/', $alias);
		if(is_array($array) && count($array) > 0)
		{
			$cnt = count($array);
			$array[$cnt-1] = ($pid > 0 ? $id . '-' : '') . $array[$cnt-1];
			$alias = implode('/', $array);
		}
		return $alias;
	}	  
	
	public function compressHtml($html)
	{
		$html = preg_replace('~>\s+<~', '><', $html);
		$html = preg_replace('/\s\s+/', ' ', $html);
		$i = 0;
		while ($i < 5) 
		{
			$html = str_replace('  ', ' ', $html);
			$i++;    
		}
		return $html;
	}

/* --- NEW UPLOAD --- */

	function pnsdotvn_get_dir_upload($type = 1, $thumb = false) 
	{	
		$dir = '';
		switch ($type)
		{
			case 1 : 
				$dir = date('Y-m');
				$dir_thumbs = $dir . '/thumbs';		
				$path_dir = MOD_ROOT_URL . $dir;
				$path_thumb_dir = MOD_ROOT_URL . $dir_thumbs;		
				if (!file_exists($path_dir))
				{
					@mkdir($path_dir, 0707);
					#exec("chmod 777 {$path_dir}");
					@chmod($path_dir, 0707);
					if ($thumb == true && !file_exists($path_thumb_dir))
					{
						@mkdir($path_thumb_dir, 0707);
						#exec("chmod 777 {$path_thumb_dir}");
						@chmod($path_thumb_dir, 0707);
					}
				}		
				break ;
				
			case 2 : 
				$dir = date('Y-m-d');
				$dir_thumbs = $dir . '/thumbs';				
				$path_dir = MOD_ROOT_URL . $dir;
				$path_thumb_dir = MOD_ROOT_URL . $dir_thumbs;				
				if (!file_exists($path_dir))
				{
					@mkdir($path_dir,0707);
					#exec("chmod 777 {$path_dir}");
					@chmod($path_dir, 0707);
					if ($thumb == true && !file_exists($path_thumb_dir))
					{
						@mkdir($path_thumb_dir,0707);
						#exec("chmod 777 {$path_thumb_dir}");
						@chmod($path_thumb_dir, 0707);
					}
				}
				break ;
				
			case 3 : 	
			  $dir_month = date('Y-m');
			  $path_month = MOD_ROOT_URL . $dir_month;
			  $dir_day = date('d') ;
			  $path_day = $path_month . '/' . $dir_day;
			  $dir_thumbs = $dir_day . '/thumbs';
			  $path_thumb = MOD_ROOT_URL . $dir_thumbs;			  
			  $dir = $dir_month . '/' . $dir_day;
			  if (!file_exists($path_month))
			  {
				  @mkdir($path_month, 0707);
				  #exec("chmod 777 {$path_month}");
				  @chmod($path_month, 0707);
			  }
			  if (!file_exists($path_day))
			  {
				  @mkdir($path_day, 0707);
				  #exec("chmod 777 {$path_day}");
				  @chmod($path_day, 0707);
				  if ($thumb == true && !file_exists($path_thumb))
				  {
					  @mkdir($path_thumb, 0707);
					  #exec("chmod 777 {$path_thumb}");
					  @chmod($path_thumb, 0707);
				  }
			  }
			  break ;
		}
		return $dir;
	}

	function pnsdotvn_upload_file($data, $handle, $utls)
	{	  	
	  	$path = $data['path'] . $data['dir'];
	  	if ($data['type'] == 'image') 
		{
			if ($handle->uploaded) 
			{
		  		$handle->allowed = array('image/*' , 'application/x-shockwave-flash');
		  		if ($handle->file_src_name_ext == 'jpg' || $handle->file_src_name_ext == 'gif' || $handle->file_src_name_ext == 'png') 
				{
					switch ($handle->file_src_name_ext) 
					{
						case 'jpg':
							$image_src = @imagecreatefromjpeg($handle->file_src_pathname);
							break;
						case 'gif':
						  	$image_src = @imagecreatefromgif($handle->file_src_pathname);
							break;
						case 'png':
						  	$image_src = @imagecreatefrompng($handle->file_src_pathname);
							break;
						default:
						  	$image_src = @imagecreatefromjpeg($handle->file_src_pathname);
							break;
					}
					$image_src_x = imagesx($image_src);
					$image_src_y = imagesy($image_src);
					if ($image_src_x > $data['w']) 
					{
			  			$handle->image_resize = true;
					} else {
			  			$handle->image_resize = false;
					}
		  		}
		  		$handle->image_ratio_y = true;
		  		$handle->image_x = $data['w'];
		  		if (isset($data['change_name']) && $data['change_name'] == true) 
				{
					$handle->file_src_name_body = strtolower($utls->generate_url_from_text($handle->file_src_name_body));
					$handle->file_dst_name = PREFIX_IMAGE_NAME . '-' . $handle->file_src_name_body . '.' . $handle->file_src_name_ext;
					$handle->file_src_name_body = PREFIX_IMAGE_NAME . '-' . $handle->file_src_name_body;
		  		}
				$handle->process($path);
				if ($handle->processed) 
				{
				  	$re['link'] = $handle->file_dst_name;
				  	$re['size'] = $handle->file_src_size;
				  	$re['type'] = $handle->file_dst_name_ext;
				} else {
				  	$err = 'file_invalid';
				}
		  		//thumb
		  		if (isset($data['thum']) && $data['thum'] == true && $re['link']) 
				{
					$path_thumb = $path . '/thumbs';
					$handle->image_resize = true;
			
					if(isset($data['resize_crop']) && $data['resize_crop'] == true)
					{
						$handle->image_ratio_crop = true;
						$handle->image_y = $data['w_thum'];
						$handle->image_x = $data['w_thum'];
					} else 
					{
						if ($image_src_y > $image_src_x) 
						{
							$handle->image_ratio_x = true;
							$handle->image_y = $data['w_thum'];
						} else {
							$handle->image_ratio_y = true;
							$handle->image_x = $data['w_thum'];
						}
					}					  
					if (isset($data['change_name_thum']) && $data['change_name_thum'] == true) 
					{
			  			$handle->file_dst_name = (isset($data['new_name_thum']) ? $data['new_name_thum'] : PREFIX_IMAGE_NAME . '-' . $handle->file_src_name_body) . '.' . $handle->file_src_name_ext;
			  			$handle->file_src_name_body = (isset($data['new_name_thum']) ? $data['new_name_thum'] : PREFIX_IMAGE_NAME . '-' . $handle->file_src_name_body);
					}
					$handle->process($path_thumb);
		  		}
			} else 
			{
		  		$err = '  Error: ' . $handle->error . '';
			}
			$err = $handle->error;
	  	} else 
		{
			$handle->process($path);
			if ($handle->processed) 
			{
		  		$re['link'] = $handle->file_dst_name;
		  		$re['size'] = $handle->file_src_size;
		  		$re['type'] = $handle->file_dst_name_ext;
			}
	  	}
	  	#echo($handle->log);
	  	$re['err'] = $err;
	  	return $re;
	}
	
	function pnsdotvn_muilti_upload_file($data, $handle, $utls)
  	{
    	$path = $data['path'] . $data['dir'];
    	$err = '';
    	$files = array();
    	foreach ($data['file'] as $k => $l) 
		{
      		foreach ($l as $i => $v) 
			{
        		if (! array_key_exists($i, $files))
          			$files[$i] = array();
        		$files[$i][$k] = $v;
      		}
    	}
    	$n = 0;
    	foreach ($files as $file) 
		{
      		if ($file['name'] != '') 
			{
        		#$handle = new upload($file);
        		if ($data['type'] == 'image') 
				{
          			if ($handle->uploaded) 
					{
            			$handle->allowed = array(
              				'image/gif' , 
             				'image/jpeg' , 
              				'image/pjpeg' , 
              				'image/png' , 
              				'application/x-shockwave-flash');
            			if ($handle->file_src_name_ext == 'jpg' || $handle->file_src_name_ext == 'gif' || $handle->file_src_name_ext == 'png') 
						{
              				switch ($handle->file_src_name_ext) 
							{
                				case 'jpg':
                  					$image_src = @imagecreatefromjpeg($handle->file_src_pathname);
                					break;
								case 'gif':
								  	$image_src = @imagecreatefromgif($handle->file_src_pathname);
									break;
								case 'png':
								  	$image_src = @imagecreatefrompng($handle->file_src_pathname);
									break;
								default:
								  	$image_src = @imagecreatefromjpeg($handle->file_src_pathname);
									break;
							}
						  	$image_src_x = @imagesx($image_src);
						  	$image_src_y = @imagesy($image_src);
						  	if ($image_src_x > $data['w']) 
							{
								$handle->image_resize = true;
						  	} else {
								$handle->image_resize = false;
						  	}
            			}
						$handle->image_ratio_y = true;
						$handle->image_x = $data['w'];
						$handle->process($path);
						if ($handle->processed) 
						{
						  	$re['link'][$n] = $handle->file_dst_name;
						  	$re['size'][$n] = $handle->file_src_size;
						  	$re['type'][$n] = $handle->file_dst_name_ext;
						} else {
						  $err = 'file_invalid';
						}
            			//thumb
            			if (isset($data['thum']) && $data['thum'] == true && $re['link'][$n]) 
						{
              				$path_thumb = $path . '/thumbs';
              				$handle->image_resize = true;
              				if($data['resize_crop'] == true)
							{
								$handle->image_ratio_crop = true;
								$handle->image_y = $data['w_thum'];
								$handle->image_x = $data['w_thum'];
							} else
							{
								if ($image_src_y > $image_src_x) 
								{
									$handle->image_ratio_x = true;
									$handle->image_y = $data['w_thum'];
								} else 
								{
									$handle->image_ratio_y = true;
									$handle->image_x = $data['w_thum'];
								}
							}
              				if ($data['change_name_thum']) 
							{
                				$handle->file_dst_name = $data['w_thum'] . '_' . $handle->file_src_name_ext;
                				$handle->file_src_name_body = $data['w_thum'] . '_' . $handle->file_src_name_body;
              				}
              				$handle->process($path_thumb);
            			}
          			} else {
            			$err .= '  Error: ' . $handle->error . '';
          			}
          			$err .= $handle->error;
				} else 
				{
          			$handle->process($path);
          			if ($handle->processed) 
					{
            			$re['link'][$n] = $handle->file_dst_name;
            			$re['size'][$n] = $handle->file_src_size;
            			$re['type'][$n] = $handle->file_dst_name_ext;
          			}
        		}
      		}
      		$n++;
    	}
    	$re['err'] = $err;
    	return $re;
  	}	
	
	function showMenu($pid = 0, $level = 1, & $html = '')
	{
		$rst = $this->getDynamic(prefixTable . 'category', 'status = 1 and parentid = ' . $pid, 'ordering, name');
		if($this->totalRows($rst))
		{
			$html .= '<ul>';
			while($row = $this->nextObject($rst))
			{
				$space = $level > 1 ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : '';
				$html .= '<li><a id="' . $row->id . '" href="javascript:;">' . $space . ($pid > 0 ? '└&nbsp;' : '') . $row->name . '</a>';
				$this->showMenu($row->id, $level + 1, $html);
				$html .= '</li>';
			}
			$html .= '</ul>';
		}
		return $html;
	}
	
	function showRelated($related, $html = '')
	{
		if(!empty($related) && is_array($related) && count($related) > 0)
		{
			$ids = implode(',', $related);
			$rst = $this->getDynamic(prefixTable . 'product', 'id in (' . $ids . ')', '');
			if($this->totalRows($rst))
			{
				while($row = $this->nextObject($rst))
				{
					$html .= '<p id="selected_' . $row->id . '">' . stripslashes($row->name) . '</p>';
				}
			}
		}
		return $html;
	}
	
	function getChildCategory($cid, & $result = array())
	{	
		$rst = $this->getDynamic(prefixTable . 'category', 'status = 1 and parentid = ' . $cid, '');  	
		if($this->totalRows($rst) > 0)
		{
		  	while($row = $this->nextObject($rst))
		  	{
				$result[] = $row->id;
				$this->getChildCategory($row->id, $result);
		  	}		  
		}
		return $result;	
	}
	
	function showProductOption($option, $lang = 'vi-VN', $html = '')
	{
		$rst = $this->Query('SELECT t1.id, t1.name, t1.rewrite, t1.value, t2.type FROM dynw_product_option_desc t1 INNER JOIN dynw_product_option t2 ON t2.id = t1.id WHERE t1.lang = "' . $lang . '" AND t2.status = 1 ORDER BY t2.ordering, t1.name');
		if ($this->totalRows($rst))
		{
			while ($row = $this->nextObject($rst))
			{
				$html .=
				'<tr>' . 
                  '<td class="boxGrey">' . $row->name . '</td>' . 
                  '<td class="boxGrey2">' . $this->buildProductOptionData($option, $row->id, str_replace('-', '_', $row->rewrite), $row->value, $row->type, $lang) . '</td>' . 
                '</tr>';
			}
		}
		return $html;
	}
	
	function buildProductOptionData($option, $id, $name, $default = '', $type = 'text', $lang = 'vi-VN', $html = '')
	{
		switch ($type)
		{
			case 'text':
				$html = 
				'<input id="option_' . $name . '_' . $id . '" name="product_option_data[]" type="text" class="nd1" value="' . (isset($option[$id]['id']) ? $option[$id]['id'] : $default) . '" />' .
				'<input id="option_' . $name . '_' . $id . '_hidden" name="product_option_id[]" type="hidden" value="' . $id . '" />';
				break;
				
			case 'select':
				$rst = $this->Query('SELECT t1.* FROM dynw_product_option_data_desc t1 INNER JOIN dynw_product_option_data t2 ON t2.id = t1.id WHERE t1.lang = "' . $lang . '" AND t2.status = 1 AND t2.oid = ' . $id . ' ORDER BY t2.ordering, t1.name');
				if ($this->totalRows($rst))
				{
					$html = 
					'<select name="product_option_data[]" id="' . $name . '_' . $id . '" class="cbo">' .
					'<option value="">-- Vui lòng chọn --</option>';
					while ($row = $this->nextObject($rst))
					{
						$html .= '<option value="' . $row->id . '"' . (isset($option[$id]['id']) && $option[$id]['id'] == $row->id ? ' selected="selected"' : '') . '>&nbsp;' . $row->name .  '&nbsp;</option>';
					}
					$html .= 
					'</select>'.
					'<input id="option_' . $name . '_' . $id . '_hidden" name="product_option_id[]" type="hidden" value="' . $id . '" />';
				}
				break;
			
		}
		return $html;
	}
	
	function showNotifyUser($user, $html = '')
	{
		if(!empty($user))
		{
			$rst = $this->getDynamic(prefixTable . 'customer', 'id in (' . $user . ')', '');
			if($this->totalRows($rst))
			{
				while($row = $this->nextObject($rst))
				{
					$html .= '<p id="selected_' . $row->id . '">' . stripslashes($row->name) . ' (ID: ' . $row->id . ')' . '</p>';
				}
			}
		}
		return $html;
	}
	
	function showNewProductDateDropdown($date, $lang = 'vi-VN', $html = '')
	{
		$rst = $this->Query('SELECT content FROM dynw_setting_desc t1 INNER JOIN dynw_setting t2 ON t2.id = t1.id WHERE display = 1 AND status = 1 AND type = "date1" AND lang = "' . $lang . '" LIMIT 1');
		if ($this->totalRows($rst))
		{
			$row = $this->nextObject($rst);
			$new_date = explode(';', $row->content);
			if (!empty($new_date) && count($new_date) > 0 && is_array($new_date))
			{
				#print_r($new_date);
				$tmp = '';
				foreach ($new_date as $item)
				{
					if (preg_match('/(0[1-9]|[12][0-9]|3[01])[- \/.](0[1-9]|1[012])[- \/.](19|20)\d\d/', $item)) 
					{
						$tmp .= '<option value="' . $item . '"' . ($date == $item ? ' selected="selected"' : '') . '>' . $item . '</option>';
					}
				}
				if (!empty($tmp))
				{
					$html =
					'<select id="new_date" name="new_date">' .
					  '<option value="">Vui lòng chọn ngày</option>' .
					  $tmp .
					'</select>';
				}
			}
		}
		echo $html;
	}
	
	function showAlsoBuy($alsobuy, $html = '')
	{
		if(!empty($alsobuy) && is_array($alsobuy) && count($alsobuy) > 0)
		{
			$ids = implode(',', $alsobuy);
			$rst = $this->getDynamic(prefixTable . 'product', 'id in (' . $ids . ')', '');
			if($this->totalRows($rst))
			{
				while($row = $this->nextObject($rst))
				{
					$html .= '<p id="selected2_' . $row->id . '">' . stripslashes($row->name) . '</p>';
				}
			}
		}
		return $html;
	}
	
}

$dbf = new BusinessLogic();