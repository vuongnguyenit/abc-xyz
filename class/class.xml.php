<?php
/** 
 * A Simple XML Make Class(SXMC) 
 * Version:0.3 Beta 
 * Author :Lazy 
 * E-mail :o0lazy0o at gmail dot com 
 * Welcome To http://www.52radio.net/ 
 * Copyright:None. 
 */ 
class XML{ 
    var $Content=""; 
    var $RootNode=""; 
    var $ParentNode=""; 
    var $CRLF="\r\n"; 
    var $End=""; 
     
    function XML($Version="1.0",$Encoding="utf-8"){ 
        $this->Content.="<?xml version=\"{$Version}\" encoding=\"{$Encoding}\"?>{$this->CRLF}"; 
    } 

    function CreateNode($NodeName="root",$Attribute=""){ 
        $NodeName=$this->Filter($NodeName); 
        $this->RootNode=$NodeName; 
        $Attribute=$this->ParseAttribute($Attribute); 
        return $this->Content.="<{$NodeName}{$Attribute}>{$this->CRLF}"; 
    }
	
	function CloseNode($NodeName){
		$NodeName=$this->Filter($NodeName); 
		return $this->Content.="</{$NodeName}>{$this->CRLF}"; 
	}

    function AppendNode($NodeName,$Attribute,$Data="",$CDate=true){ 
        $NodeName=$this->Filter($NodeName); 
        if(empty($Data)){ 
            $Attribute=$this->ParseAttribute($Attribute); 
            return $this->Content.="<{$NodeName}{$Attribute}>{$this->CRLF}"; 
        }else{ 
            $Attribute=$this->ParseAttribute($Attribute);
			return $this->Content.="<{$NodeName}{$Attribute}>{$this->CRLF}<![CDATA[{$Data}]]>{$this->CRLF}</{$NodeName}>{$this->CRLF}"; 
        } 
    } 
     
    function End(){
		return $this->Content;
    } 

    function Display(){ 
        ob_start(); 
        header("Content-type: text/xml"); 
        echo $this->End(); 
        ob_end_flush(); 
    } 

    function Save($Filename){ 
        if(!$Handle=fopen($Filename,'wb+')){ 
            $this->Error("Couldn't Write File,Make Sure Your Access"); 
        } 
        flock($Handle,LOCK_EX); 
        fwrite($Handle,$this->End()); 
        return fclose($Handle); 
    } 

    function Error($ErrorStr='',$ErrorNo='',$Stop=true){ 
        exit($ErrorStr); 
    } 

    function ParseAttribute($Argv){ 
        $Attribute=''; 
        if(is_array($Argv)){ 
            foreach($Argv as $Key=>$Value){ 
                $Value=$this->Filter($Value); 
                $Attribute.=" $Key=\"$Value\""; 
            } 
        } 
        return $Attribute; 
    } 
     
    function Filter($Argv){ 
        $Argv=trim($Argv); 
        $Search=array("<",">","\""); 
        $Replace=array("","","'"); 
        return str_replace($Search,$Replace,$Argv); 
    } 
}
?>