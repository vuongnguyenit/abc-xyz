<?php
require_once('class.dbf.php');

class BusinessLogic extends DBFunction {

    function upcaseFirst($word) {
        return ucfirst($word);
    }

    function focusText() {
        $str = " onFocus='fo(this);' onBlur='lo(this);' ";
        return $str;
    }

    function myEncodeHTML($sHTML) {
        $sHTML = stripcslashes($sHTML);
        $sHTML = preg_replace("/<DIV>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/DIV>/i", "", $sHTML);
        $sHTML = preg_replace("/<div>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/div>/i", "", $sHTML);

        $sHTML = preg_replace("/<P>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/P>/i", "", $sHTML);
        $sHTML = preg_replace("/<p>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/p>/i", "", $sHTML);

        $sHTML = preg_replace("/<UL>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/UL>/i", "", $sHTML);
        $sHTML = preg_replace("/<ul>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/ul>/i", "", $sHTML);

        $sHTML = preg_replace("/<LI>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/LI>/i", "", $sHTML);
        $sHTML = preg_replace("/<li>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/li>/i", "", $sHTML);

        $sHTML = preg_replace("/<BR>/i", "", $sHTML);
        $sHTML = preg_replace("/<BR\/>/i", "", $sHTML);
        $sHTML = preg_replace("/<br>/i", "", $sHTML);
        $sHTML = preg_replace("/<br \/>/i", "", $sHTML);
        $sHTML = preg_replace("/<br\/>/i", "", $sHTML);

        $sHTML = preg_replace("/<B>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/B>/i", "", $sHTML);
        $sHTML = preg_replace("/<b>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/b>/i", "", $sHTML);

        $sHTML = preg_replace("/<U>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/U>/i", "", $sHTML);
        $sHTML = preg_replace("/<u>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/u>/i", "", $sHTML);

        $sHTML = preg_replace("/<I>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/I>/i", "", $sHTML);
        $sHTML = preg_replace("/<i>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/i>/i", "", $sHTML);

        $sHTML = preg_replace("/<TABLE>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/TABLE>/i", "", $sHTML);
        $sHTML = preg_replace("/<table>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/table>/i", "", $sHTML);

        $sHTML = preg_replace("/<TR>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/TR>/i", "", $sHTML);
        $sHTML = preg_replace("/<tr>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/tr>/i", "", $sHTML);

        $sHTML = preg_replace("/<TD>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/TD>/i", "", $sHTML);
        $sHTML = preg_replace("/<td>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/td>/i", "", $sHTML);

        $sHTML = preg_replace("/<IMG>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/IMG>/i", "", $sHTML);
        $sHTML = preg_replace("/<img>/i", "", $sHTML);
        $sHTML = preg_replace("/<\/img>/i", "", $sHTML);

        $sHTML = preg_replace("/<IMG/i", "", $sHTML);
        $sHTML = preg_replace("/<\/IMG>/i", "", $sHTML);
        $sHTML = preg_replace("/<img/i", "", $sHTML);
        $sHTML = preg_replace("/<\/img>/i", "", $sHTML);

        return $sHTML;
    }

    function takeShortText($longText, $numWords) {
        $ret = "";
        if ($longText != "") {
            $longText = trim($longText);
            $longText = $this->myEncodeHTML($longText);
            $longText = strip_tags($longText);
            if (str_word_count($longText) > $numWords) {
                $arrayText = preg_split('/ /', $longText, $numWords);
                for ($i = 0; $i < $numWords - 1; $i++) {
                    if (isset($arrayText[$i])) {
                        $ret .= $arrayText[$i] . " ";
                    }
                }
                $ret = trim($ret) . "..";
                return $ret;
            }
            else {
                return $longText;
            }
        }
    }

    function deleteHashBlank($longText) {
        $longText  = trim($longText);
        $arrayText = preg_split('/ /', $longText);
        $ret       = "";
        for ($i = 0; $i < count($arrayText); $i++) {
            $ret .= " " . $arrayText[$i];
        }
        return trim($ret);
    }

    function encodeHTML($sHTML) {
        $sHTML = stripcslashes($sHTML);
        $sHTML = preg_replace("/&/i", "&amp;", $sHTML);
        $sHTML = preg_replace("/</i", "&lt;", $sHTML);
        $sHTML = preg_replace("/>/i", "&gt;", $sHTML);
        return $sHTML;
    }

    function takeShortTextByChar($longText, $numChars) {
        if ($longText != "") {
            $longText = trim($longText);
            $longText = strip_tags($longText);
            $longText = $this->deleteHashBlank($this->encodeHTML($longText));
            if (strlen($longText) <= $numChars) {
                return $longText;
            }
            else {
                return substr($longText, 0, $numChars);
            }
        }
    }

    function pricevnd($number, $currency = 'đ') {
        $str = number_format($number, 0, '.', '.');
        return $str . ' ' . $currency;
    }

    function getChildCategory($cid, & $result = []) {
        $rst = $this->getDynamic(prefixTable . 'category', 'status = 1 and parentid = ' . $cid, '');
        if ($this->totalRows($rst) > 0) {
            while ($row = $this->nextObject($rst)) {
                $result[] = $row->id;
                $this->getChildCategory($row->id, $result);
            }
        }
        return $result;
    }

    function getChildMenu($id, & $result = []) {
        $rst = $this->getDynamic(prefixTable . 'menu', 'status = 1 and parentid = ' . $id, '');
        if ($this->totalRows($rst) > 0) {
            while ($row = $this->nextObject($rst)) {
                $result[] = $row->id;
                $this->getChildMenu($row->id, $result);
            }
        }
        return $result;
    }

    function getIdCatArray($ida, & $result = []) {
        if (is_array($ida) && count($ida) > 0) {
            foreach ($ida as $id) {
                $rst = $this->getDynamic(prefixTable . 'category', 'status = 1 and parentid = ' . $id, 'ordering, id');
                if ($this->totalRows($rst) > 0) {
                    while ($row = $this->nextObject($rst)) {
                        $result[] = $row->id;
                    }
                    $this->getIdCatArray($result);
                }
            }
        }
        return $result;
    }

    function getMenuIdArray($ida, & $result = []) {
        if (is_array($ida) && count($ida) > 0) {
            foreach ($ida as $id) {
                $rst = $this->getDynamic(prefixTable . 'menu', 'status = 1 and parentid = ' . $id, 'ordering, id');
                if ($this->totalRows($rst) > 0) {
                    while ($row = $this->nextObject($rst)) {
                        $result[] = $row->id;
                    }
                    $this->getMenuIdArray($result);
                }
            }
        }
        return $result;
    }

    function getAliasArray($lang = 'vi-VN', $id, $mode = 1, & $alias = '') {
        $rst = $this->getDynamicJoin(prefixTable . 'category', prefixTable . 'category_desc', [
            'name'    => 'name',
            'rewrite' => 'rewrite',
        ], 'inner join', 't1.status = 1 and t1.id = ' . $id . ' and t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
        if ($this->totalRows($rst)) {
            $row                 = $this->nextObject($rst);
            $alias[][$row->name] = ($mode == 1 ? $row->id . '|' : '') . $row->rewrite;
            $this->getAliasArray($lang, $row->parentid, $mode, $alias);
        }
        krsort($alias);
        if (is_array($alias) && count($alias) > 0) {
            foreach ($alias as $item) {
                foreach ($item as $key => $value) {
                    $new[$key] = $value;
                }
            }
        }
        return $new;
    }

    function getMenuAliasArray($lang = 'vi-VN', $id, & $alias = '') {
        $rst = $this->getDynamicJoin(prefixTable . 'menu_desc', prefixTable . 'menu', ['parentid' => 'parentid'], 'inner join', 't2.status = 1 and t2.id = ' . $id . ' and t1.lang = "' . $lang . '"', '', 't2.id = t1.id');
        if ($this->totalRows($rst)) {
            $row                 = $this->nextObject($rst);
            $alias[][$row->name] = $row->rewrite;
            $this->getMenuAliasArray($lang, $row->parentid, $alias);
        }
        krsort($alias);
        if (is_array($alias) && count($alias) > 0) {
            foreach ($alias as $item) {
                foreach ($item as $key => $value) {
                    $new[$key] = $value;
                }
            }
        }
        return $new;
    }

    function buildAliasHtml($lang = 'vi-VN', $array = [], $mode = 1, $html = '') {
        if (is_array($array) && ($cnt = count($array)) > 0) {
            switch ($mode) {
                case 1:
                    $i = 1;
                    foreach ($array as $name => $rewrite) {
                        if ($i !== $cnt) {
                            $name = stripslashes($name);
                            $href = '/' . $rewrite . EXT;
                            $html .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . strtolower($href) . '" itemprop="url" title="' . $name . '"><span itemprop="title">' . $name . '</span></a></li>';
                        }
                        else {
                            $html .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="last"><span itemprop="title">' . $name . '</span></li>';
                        }
                        $i++;
                    }
                    break;

                case 2:
                    foreach ($array as $name => $rewrite) {
                        $_rewrite = explode('|', $rewrite);
                        $href     = (MULTI_LANG ? DS . substr($lang, 0, -3) : '') . DS . (count($_rewrite) == 1 ? $rewrite : $_rewrite[1] . '-' . $_rewrite[0]) . EXT;
                        $html     .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . strtolower($href) . '" itemprop="url" title="' . $name . '"><span itemprop="title">' . stripslashes($name) . '</span></a></li>';
                    }
                    break;

                case 3:
                    foreach ($array as $name => $rewrite) {
                        $_rewrite[] = $rewrite;
                        $href       = (MULTI_LANG ? DS . substr($lang, 0, -3) : '') . DS . implode('/', $_rewrite) . EXT;
                        $html       .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a href="' . strtolower($href) . '" itemprop="url" title="' . $name . '"><span itemprop="title">' . stripslashes($name) . '</span></a></li>';
                    }
                    break;
            }
        }
        return $html;
    }

    function buildURL($array = [], $url = '') {
        if (is_array($array) && ($cnt = count($array)) > 0) {
            foreach ($array as $name => $rewrite) {
                $_rewrite = explode('|', $rewrite);
                $alias[]  = isset($_rewrite[1]) ? $_rewrite[1] : $rewrite;
                $_alias   = implode('/', $alias);
                $url      = HOST . '/' . (count($_rewrite) == 2 ? $this->addIdtoAlias($_alias, $_rewrite[0]) : $rewrite) . EXT;
            }
        }
        return $url;
    }

    function getLanguage($lang = 'vi-VN', $data = '') {
        $rst = $this->getDynamic(prefixTable . 'language', 'status = 1 and code = "' . $lang . '"', '');
        if ($this->totalRows($rst) == 1) {
            $row           = $this->nextObject($rst);
            $data['code']  = $row->code;
            $data['code2'] = $row->code2;
            $data['code3'] = $row->code3;
            $data['code4'] = $row->code4;
        }
        return $data;
    }

    function getConfig($lang = 'vi-VN') {
        $info   = $this->getLanguage($lang);
        $_route = (isset($_GET['route']) && !empty($_GET['route'])) ? strtolower($this->removeSQLInjection($_GET['route'])) : '';
        $rst    = $this->getDynamicJoin(prefixTable . 'setting', prefixTable . 'setting_desc', ['content' => 'content'], 'inner join', 't1.status = 1 and t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
        while ($row = $this->nextObject($rst)) {
            $info[$row->type] = $row->status == 1 ? stripslashes($row->content) : '';
            if ($row->status == 1 && $row->type = 'latlng' && strpos($row->content, '|') !== FALSE) {
                $_latlng           = explode('|', $row->content);
                $info['latitude']  = $this->checkValues($_latlng[0]);
                $info['longitude'] = $this->checkValues($_latlng[1]);
            }
        }
        $this->freeResult($rst);
        $info['position']       = $info['latitude'] . ';' . $info['longitude'];
        $info['icbm']           = $info['latitude'] . ', ' . $info['longitude'];
        $info['author']         = SHOP_NAME;
        $info['site_name']      = SITE_NAME;
        $info['image']          = '';
        $info['published_time'] = '';
        $info['modified_time']  = '';
        $info['url']            = DS . $_route;
        if (empty($_route) || $_route == 'trang-chu' . EXT || $_route == 'home' . EXT) {
            $info['route']['name']     = 'home';
            $info['route']['id']       = 1;
            $info['route']['type']     = '';
            $info['route']['position'] = $info['route']['name'];
        }
        $lng    = substr($lang, 0, 2);
        $_route = !empty($_route) && substr($_route, 0, 3) <> $lng . DS ? $lng . DS . $_route : $_route;
        if (!empty($_route) && $_route <> 'trang-chu' . EXT && $_route <> 'home' . EXT) {
            $rst = $this->getDynamic(prefixTable . 'url_alias', 'url_alias = "' . $_route . '"', '');
            if ($this->totalRows($rst) == 1) {
                $row = $this->nextObject($rst);
                switch ($row->route) {
                    case 'cms':
                        $tblname = $row->type == 'detail' ? 'cms' : 'menu';
                        break;
                    case 'brand':
                        $tblname = $row->type == 'detail' ? 'brand' : 'menu';
                        break;
                    case 'supplier':
                        $tblname = $row->type == 'detail' ? 'supplier' : 'menu';
                        break;
                    case 'product':
                        $tblname = 'product';
                        break;
                    case 'category':
                        $tblname = 'category';
                        break;
                    default :
                        $tblname = 'menu';
                }
                #$_rst = $this->getDynamicJoin(prefixTable . $tblname . '_desc', prefixTable . $tblname, array('created' => 'created', 'modified' => 'modified'), 'inner join', 't2.status = 1 and t1.lang = "' . $lang . '" and t2.id = "' . $row->related_id . '"', '', 't2.id = t1.id', true);
                $_rst = $this->Query('SELECT t1.*, t2.* FROM ' . prefixTable . $tblname . ' t1 INNER JOIN ' . prefixTable . $tblname . '_desc t2 ON t2.id = t1.id WHERE t1.status = 1 AND t1.id = ' . $row->related_id . ' AND t2.lang = "' . $lang . '" LIMIT 1');
                if ($this->totalRows($_rst) == 1) {
                    $_row = $this->nextObject($_rst);
                    if (isset($_row->picture) && !empty($_row->picture)) {
                        $info['image'] = HOST . $_row->picture;
                    }
                    $info['published_time'] = date_format(date_create($_row->created), 'Y-m-d');
                    $info['modified_time']  = date_format(date_create($_row->modified), 'Y-m-d');
                    $info['name']           = $_row->name;
                    $info['rewrite']        = $_row->rewrite;
                    $info['html']           = (isset($_row->html) ? $_row->html : '');
                    $info['title']          = isset($_row->metatitle) && !empty($_row->metatitle) ? $_row->metatitle : $_row->name;
                    if (isset($_row->metakey) && !empty($_row->metakey)) {
                        $info['keywords'] = $_row->metakey;
                    }
                    if (isset($_row->metadesc) && !empty($_row->metadesc)) {
                        $info['description'] = $_row->metadesc;
                    }
                    if (isset($_row->description) && !empty($_row->description)) {
                        $info['desc'] = $_row->description;
                    }
                    if ($row->route == 'cms') {
                        $info['mid']     = isset($_row->mid) ? $_row->mid : 0;
                        $info['display'] = isset($_row->display) ? $_row->display : ($row->type == 'detail' ? 'DETAIL' : '');
                    }
                }
                $this->freeResult($_rst);
                $info['route']['name']     = $row->route;
                $info['route']['id']       = $row->related_id;
                $info['route']['type']     = $row->type;
                $info['route']['position'] = $info['route']['name'];
            }
            else {
                $_url_alias = str_replace(EXT, '', substr($_route, 3));
                $_item      = explode('/', $_url_alias);
                if (isset($_item[0]) && !empty($_item[0]) && isset($_item[1]) && !empty($_item[1])) {
                    switch ($_item[0]) {
                        case 'tag':
                            $info['title']             = str_replace('-', ' ', $_item[1]) . ', thông tin hình ảnh video về ' . str_replace('-', ' ', $_item[1]);
                            $info['route']['name']     = 'tag';
                            $info['route']['id']       = $_item[1];
                            $info['route']['type']     = 'tag';
                            $info['route']['position'] = $info['route']['name'];
                            $info['display']           = 'LIST';
                            break;
                    }
                }
                else {
                    /*if(!TEST_MODE)
					{
				  		header('Location: /errors/400.shtml');
				  		exit;
					}*/
                    $info['title']         = 'Lỗi 404';
                    $info['route']['name'] = '404';
                    $info['route']['id']   = 404;
                    $info['route']['type'] = 404;
                }

            }
            $this->freeResult($rst);
        }
        return $info;
    }

    function checkOldLink($uri, $d = '') {
        if (!empty($uri)) {
            $rst = $this->Query('SELECT url_alias FROM ' . prefixTable . 'url_alias WHERE uri = "' . $uri . '" LIMIT 1');
            if ($this->totalRows($rst)) {
                $row      = $this->nextObject($rst);
                $d['uri'] = $row->url_alias;
                $d['f']   = TRUE;
            }
        }
        return $d;
    }

    //************ Hm phn trang **********
    function paging($tablename, $where, $orderby = "", $url, $PageNo, $PageSize, $Pagenumber, $ModePaging) {
        // ModePaging 1: Full  ---------->  << | < | > | >>
        // ModePaging 2: Next  ---------->  <
        // ModePaging 3: Previous ------->  >
        if ($PageNo == "") {
            $StartRow = 0;
            $PageNo   = 1;
        }
        else {
            $StartRow = ($PageNo - 1) * $PageSize;
        }

        //Set the counter start
        if ($PageSize <= 0) {
            $PageSize = 1;
        }
        if ($PageSize >= 100) {
            $PageSize = 100;
        }

        if ($PageNo % $Pagenumber == 0) {
            $CounterStart = $PageNo - ($Pagenumber - 1);
        }
        else {
            $CounterStart = $PageNo - ($PageNo % $Pagenumber) + 1;
        }

        $CounterEnd = $CounterStart + $Pagenumber;
        //$TRecord = mysql_query($sql) or die(mysql_error());
        $TRecord = $this->getDynamic($tablename, $where, $orderby);
        //$sql.= " LIMIT $StartRow, $PageSize";
        //$result = mysql_query($sql) or die(mysql_error());
        $result = $this->getDynamic($tablename, $where, $orderby . " LIMIT " . $StartRow . "," . $PageSize);

        $RecordCount = mysql_num_rows($TRecord);
        if ($RecordCount % $PageSize == 0) {
            $MaxPage = $RecordCount / $PageSize;
        }
        else {
            $MaxPage = ceil($RecordCount / $PageSize);
        }
        $gotopage = "";

        switch ($ModePaging) {
            case "Full":

                if ($MaxPage > 1) {
                    $gotopage = '<div class="paging_meneame">';

                    // Print First page, Prev page
                    if ($PageNo != 1) {
                        $PrevStart = $PageNo - 1;
                        $gotopage  .= ' <a href="' . $url . '?page=1" tile="First page"> Đầu </a>';
                        $gotopage  .= ' <a href="' . $url . '?page=' . $PrevStart . '" title="Previous page"> Trước </a>';
                    }
                    else {
                        $gotopage .= ' <span class="disabled_pagination"> Đầu </span>';
                        $gotopage .= ' <span class="disabled_pagination"> Trước </span>';
                    }
                    $c = 0;

                    //Print Page No. 1, 2, 3, ...
                    for ($c = $CounterStart; $c < $CounterEnd; $c++) {
                        if ($c <= $MaxPage) {
                            if ($c == $PageNo) {
                                $gotopage .= '<span class="active_link"> ' . $c . ' </span>';
                            }
                            else {
                                $gotopage .= ' <a href="' . $url . '?page=' . $c . '" title="Page ' . $c . '"> ' . $c . ' </a>';
                            }
                        }
                    }

                    //Print Next page
                    if ($PageNo < $MaxPage) {
                        $NextPage = $PageNo + 1;
                        $gotopage .= ' <a href="' . $url . '?page=' . $NextPage . '" title="Next page"> Sau </a>';
                    }
                    else {
                        $gotopage .= ' <span class="disabled_pagination"> Sau </span>';
                    }

                    //Print Last page
                    if ($PageNo < $MaxPage) {
                        $gotopage .= ' <a href="' . $url . '?page=' . $MaxPage . '" title="Last page"> Cuối </a>';
                    }
                    else {
                        $gotopage .= ' <span class="disabled_pagination"> Cuối </span>';
                    }

                    $gotopage .= ' </div>';
                }

                break;
            case "<":
                // Print First page, Prev page
                if (($MaxPage > 1) & ($PageNo != 1)) {
                    $PrevStart = $PageNo - 1;

                    $gotopage .= "<A id='paging' title=\"Back\" href=\"$url&PageNo=$PrevStart";
                    $gotopage .= "\" ";
                    $gotopage .= "\"onMouseOut=\"MM_swapImgRestore()\" ";
                    $gotopage .= "onMouseOver=\"MM_swapImage('back','','images/back_2.gif',1)\" >";
                    $gotopage .= "<img src=\"images/back_1.gif\" alt=\"Back\" name=\"back\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                    $gotopage .= "</A>";
                }
                else {
                    $gotopage .= "<img src=\"images/back_1.gif\" alt=\"Back\" name=\"back\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                }
                break;

            case ">":
                if (($MaxPage > 1) & ($PageNo < $MaxPage)) {
                    $NextPage = $PageNo + 1;

                    $gotopage .= "<A id='paging' title=\"Next\" href=\"$url&PageNo=$NextPage";
                    $gotopage .= "\" ";
                    $gotopage .= "\"onMouseOut=\"MM_swapImgRestore()\" ";
                    $gotopage .= "onMouseOver=\"MM_swapImage('next','','images/next_2.gif',1)\" >";

                    $gotopage .= "<img src=\"images/next_1.gif\" alt=\"Next\" name=\"next\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                    $gotopage .= "</A>";
                }
                else {
                    $gotopage .= "<img src=\"images/next_1.gif\" alt=\"Next\" name=\"next\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                }
                break;

        } //END OF SWITCH
        $arr[0] = $result;
        $arr[1] = $gotopage;
        return $arr;
    } //END OF PAGING

    //Paging join

    function pagingJoin($tablename1, $tablename2, $arrayNewName, $typejoin = "inner join", $where, $orderby, $url, $PageNo, $PageSize, $Pagenumber, $ModePaging, $on) {
        // ModePaging 1: Full  ---------->  << | < | > | >>
        // ModePaging 2: Next  ---------->  <
        // ModePaging 3: Previous ------->  >
        if ($PageNo == "") {
            $StartRow = 0;
            $PageNo   = 1;
        }
        else {
            $StartRow = ($PageNo - 1) * $PageSize;
        }

        //Set the counter start
        if ($PageSize <= 0) {
            $PageSize = 1;
        }
        if ($PageSize >= 100) {
            $PageSize = 100;
        }

        if ($PageNo % $Pagenumber == 0) {
            $CounterStart = $PageNo - ($Pagenumber - 1);
        }
        else {
            $CounterStart = $PageNo - ($PageNo % $Pagenumber) + 1;
        }

        $CounterEnd = $CounterStart + $Pagenumber;
        //$TRecord = mysql_query($sql) or die(mysql_error());
        $TRecord = $this->getDynamicJoin($tablename1, $tablename2, $arrayNewName, $typejoin, $where, $orderby, $on);
        //$sql.= " LIMIT $StartRow, $PageSize";
        //$result = mysql_query($sql) or die(mysql_error());
        $result = $this->getDynamicJoin($tablename1, $tablename2, $arrayNewName, $typejoin, $where, $orderby . " LIMIT " . $StartRow . "," . $PageSize, $on);

        $RecordCount = mysql_num_rows($TRecord);
        if ($RecordCount % $PageSize == 0) {
            $MaxPage = $RecordCount / $PageSize;
        }
        else {
            $MaxPage = ceil($RecordCount / $PageSize);
        }
        $gotopage = "";

        $pos = (strpos($url, "?") === FALSE) ? "?" : "&";

        switch ($ModePaging) {
            case "Full":

                if ($MaxPage > 1) {
                    $gotopage = '<div class="paging_meneame">';

                    // Print First page, Prev page
                    if ($PageNo != 1) {
                        $PrevStart = $PageNo - 1;
                        $gotopage  .= ' <a href="' . $url . $pos . 'page=1" tile="First page"> &laquo; </a>';
                        $gotopage  .= ' <a href="' . $url . $pos . 'page=' . $PrevStart . '" title="Previous page"> &lsaquo; </a>';
                    }
                    else {
                        $gotopage .= ' <span class="disabled_pagination"> &laquo; </span>';
                        $gotopage .= ' <span class="disabled_pagination"> &lsaquo; </span>';
                    }
                    $c = 0;

                    //Print Page No. 1, 2, 3, ...
                    for ($c = $CounterStart; $c < $CounterEnd; $c++) {
                        if ($c <= $MaxPage) {
                            if ($c == $PageNo) {
                                $gotopage .= '<span class="active_link"> ' . $c . ' </span>';
                            }
                            else {
                                $gotopage .= ' <a href="' . $url . $pos . 'page=' . $c . '" title="Page ' . $c . '"> ' . $c . ' </a>';
                            }
                        }
                    }

                    //Print Next page
                    if ($PageNo < $MaxPage) {
                        $NextPage = $PageNo + 1;
                        $gotopage .= ' <a href="' . $url . $pos . 'page=' . $NextPage . '" title="Next page"> &rsaquo; </a>';
                    }
                    else {
                        $gotopage .= ' <span class="disabled_pagination"> &rsaquo; </span>';
                    }

                    //Print Last page
                    if ($PageNo < $MaxPage) {
                        $gotopage .= ' <a href="' . $url . $pos . 'page=' . $MaxPage . '" title="Last page"> &raquo; </a>';
                    }
                    else {
                        $gotopage .= ' <span class="disabled_pagination"> &raquo; </span>';
                    }

                    $gotopage .= ' </div>';
                }

                break;
            case "<":
                // Print First page, Prev page
                if (($MaxPage > 1) & ($PageNo != 1)) {
                    $PrevStart = $PageNo - 1;

                    $gotopage .= "<A id='paging' title=\"Back\" href=\"$url&PageNo=$PrevStart";
                    $gotopage .= "\" ";
                    $gotopage .= "\"onMouseOut=\"MM_swapImgRestore()\" ";
                    $gotopage .= "onMouseOver=\"MM_swapImage('back','','images/back_2.gif',1)\" >";
                    $gotopage .= "<img src=\"images/back_1.gif\" alt=\"Back\" name=\"back\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                    $gotopage .= "</A>";
                }
                else {
                    $gotopage .= "<img src=\"images/back_1.gif\" alt=\"Back\" name=\"back\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                }
                break;

            case ">":
                if (($MaxPage > 1) & ($PageNo < $MaxPage)) {
                    $NextPage = $PageNo + 1;

                    $gotopage .= "<A id='paging' title=\"Next\" href=\"$url&PageNo=$NextPage";
                    $gotopage .= "\" ";
                    $gotopage .= "\"onMouseOut=\"MM_swapImgRestore()\" ";
                    $gotopage .= "onMouseOver=\"MM_swapImage('next','','images/next_2.gif',1)\" >";

                    $gotopage .= "<img src=\"images/next_1.gif\" alt=\"Next\" name=\"next\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                    $gotopage .= "</A>";
                }
                else {
                    $gotopage .= "<img src=\"images/next_1.gif\" alt=\"Next\" name=\"next\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                }
                break;

        } //END OF SWITCH
        $arr[0] = $result;
        $arr[1] = $gotopage;
        return $arr;
    } //END OF PAGING

    function pagingQueryJoin($tbName, $Where = '', $Orderby = '', $Columns = '*', $Limit = '', $TableJoin = NULL, $TypeJoin = 'Inner Join', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging, $debug = FALSE) {
        // ModePaging 1: Full  ---------->  << | < | > | >>
        // ModePaging 2: Next  ---------->  <
        // ModePaging 3: Previous ------->  >
        if ($PageNo == "") {
            $StartRow = 0;
            $PageNo   = 1;
        }
        else {
            $StartRow = ($PageNo - 1) * $PageSize;
        }

        //Set the counter start
        if ($PageSize <= 0) {
            $PageSize = 1;
        }
        if ($PageSize >= 100) {
            $PageSize = 100;
        }

        if ($PageNo % $Pagenumber == 0) {
            $CounterStart = $PageNo - ($Pagenumber - 1);
        }
        else {
            $CounterStart = $PageNo - ($PageNo % $Pagenumber) + 1;
        }

        $CounterEnd = $CounterStart + $Pagenumber;
        //$TRecord = mysql_query($sql) or die(mysql_error());
        /*$TRecord=$this->getDynamicJoin($tablename1,$tablename2,$arrayNewName,$typejoin,$where,$orderby,$on);*/
        $TRecord = $this->queryJoin($tbName, $Where, $Orderby, $Columns, $Limit, $TableJoin, $TypeJoin, $debug);
        //$sql.= " LIMIT $StartRow, $PageSize";
        //$result = mysql_query($sql) or die(mysql_error());
        /*$result=$this->getDynamicJoin($tablename1,$tablename2,$arrayNewName,$typejoin,$where,$orderby." LIMIT ".$StartRow.",". $PageSize,$on);*/
        $result = $this->queryJoin($tbName, $Where, $Orderby, $Columns, $StartRow . "," . $PageSize, $TableJoin, $TypeJoin);

        $RecordCount = mysql_num_rows($TRecord);
        if ($RecordCount % $PageSize == 0) {
            $MaxPage = $RecordCount / $PageSize;
        }
        else {
            $MaxPage = ceil($RecordCount / $PageSize);
        }
        $gotopage = "";

        $pos = (strpos($url, "?") === FALSE) ? "?" : "&";

        switch ($ModePaging) {
            case 'Toolbar':
                if ($MaxPage > 1) {
                    $gotopage = '<ol>';

                    if ($PageNo != 1) {
                        $PrevStart = $PageNo - 1;
                        $gotopage  .= '<li><a class="previous" href="' . $url . $pos . 'page=' . $PrevStart . '" title="Trang trước"> &lsaquo; </a>';
                    }
                    else {
                        $gotopage .= '<li class="disable"> &lsaquo; </li>';
                    }

                    $c = 0;
                    for ($c = $CounterStart; $c < $CounterEnd; $c++) {
                        if ($c <= $MaxPage) {
                            if ($c == $PageNo) {
                                $gotopage .= '<li class="current">' . $c . '</li>';
                            }
                            else {
                                $gotopage .= '<li><a href="' . $url . $pos . 'page=' . $c . '" title="Trang ' . $c . '">' . $c . '</a></li>';
                            }
                        }
                    }

                    if ($PageNo < $MaxPage) {
                        $NextPage = $PageNo + 1;
                        $gotopage .= '<li><a class="next" href="' . $url . $pos . 'page=' . $NextPage . '" title="Trang tiếp theo"> &rsaquo; </a></li>';
                    }
                    else {
                        $gotopage .= '<li class="disable"> &rsaquo; </li>';
                    }

                    $gotopage .= '</ol>';
                }
                break;

            case "Full":

                if ($MaxPage > 1) {
                    $gotopage = '<div class="paging_meneame">';

                    // Print First page, Prev page
                    if ($PageNo != 1) {
                        $PrevStart = $PageNo - 1;
                        $gotopage  .= ' <a href="' . $url . $pos . 'page=1" tile="First page"> &laquo; </a>';
                        /*$gotopage.=' <a href="'.$url. $pos .'page='.$PrevStart.'" title="Previous page"> Trước </a>';*/
                    }
                    else {
                        $gotopage .= ' <span class="disabled_pagination"> &laquo; </span>';
                        /*$gotopage.=' <span class="disabled_pagination"> Trước </span>';*/
                    }
                    $c = 0;

                    //Print Page No. 1, 2, 3, ...
                    for ($c = $CounterStart; $c < $CounterEnd; $c++) {
                        if ($c <= $MaxPage) {
                            if ($c == $PageNo) {
                                $gotopage .= '<span class="active_link"> ' . $c . ' </span>';
                            }
                            else {
                                $gotopage .= ' <a href="' . $url . $pos . 'page=' . $c . '" title="Page ' . $c . '"> ' . $c . ' </a>';
                            }
                        }
                    }

                    //Print Next page
                    /*if($PageNo < $MaxPage){
					$NextPage = $PageNo + 1;
					$gotopage.= ' <a href="'.$url. $pos .'page='.$NextPage.'" title="Next page"> Sau </a>';
				}
				else {
					$gotopage.= ' <span class="disabled_pagination"> Sau </span>';
				}*/

                    //Print Last page
                    if ($PageNo < $MaxPage) {
                        $gotopage .= ' <a href="' . $url . $pos . 'page=' . $MaxPage . '" title="Last page"> &raquo; </a>';
                    }
                    else {
                        $gotopage .= ' <span class="disabled_pagination"> &raquo; </span>';
                    }

                    $gotopage .= ' </div>';
                }

                break;
            case "<":
                // Print First page, Prev page
                if (($MaxPage > 1) & ($PageNo != 1)) {
                    $PrevStart = $PageNo - 1;

                    $gotopage .= "<A id='paging' title=\"Back\" href=\"$url&PageNo=$PrevStart";
                    $gotopage .= "\" ";
                    $gotopage .= "\"onMouseOut=\"MM_swapImgRestore()\" ";
                    $gotopage .= "onMouseOver=\"MM_swapImage('back','','images/back_2.gif',1)\" >";
                    $gotopage .= "<img src=\"images/back_1.gif\" alt=\"Back\" name=\"back\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                    $gotopage .= "</A>";
                }
                else {
                    $gotopage .= "<img src=\"images/back_1.gif\" alt=\"Back\" name=\"back\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                }
                break;

            case ">":
                if (($MaxPage > 1) & ($PageNo < $MaxPage)) {
                    $NextPage = $PageNo + 1;

                    $gotopage .= "<A id='paging' title=\"Next\" href=\"$url&PageNo=$NextPage";
                    $gotopage .= "\" ";
                    $gotopage .= "\"onMouseOut=\"MM_swapImgRestore()\" ";
                    $gotopage .= "onMouseOver=\"MM_swapImage('next','','images/next_2.gif',1)\" >";

                    $gotopage .= "<img src=\"images/next_1.gif\" alt=\"Next\" name=\"next\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                    $gotopage .= "</A>";
                }
                else {
                    $gotopage .= "<img src=\"images/next_1.gif\" alt=\"Next\" name=\"next\" ";
                    $gotopage .= "width=\"14\" height=\"130\" border=\"0\"> ";
                }
                break;

        } //END OF SWITCH
        $arr[0] = $result;
        $arr[1] = $gotopage;
        $arr[2] = $RecordCount;
        return $arr;
    }

    function addIdtoAlias($alias, $id) {
        $array = explode('/', $alias);
        if (is_array($array) && count($array) > 0) {
            $cnt             = count($array);
            $array[$cnt - 1] = $id . '-' . $array[$cnt - 1];
            $alias           = implode('/', $array);
        }
        return $alias;
    }

    function stripSlashes($content) {
        return stripslashes($content);
    }

    function removeStripSlashes($content) {
        $content = $this->stripSlashes($content);
        $content = str_replace('"', "", $content);
        $content = str_replace("'", "", $content);
        return $content;
    }

    function getMenuAlias($id) {
        $rst = $this->getDynamic(prefixTable . "menu", "status=1 and parentid=0 and id='{$id}'", "");
        if ($this->totalRows($rst) == 1) {
            $row = $this->nextObject($rst);
            return $row->alias;
        }
    }

    function stripUnicode($str) {
        if (!$str) {
            return FALSE;
        }
        $str     = strip_tags($str);
        $unicode = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        ];
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }

    public function compressHtml($html) {
        $html = preg_replace('~>\s+<~', '><', $html);
        $html = preg_replace('/\s\s+/', ' ', $html);
        $i    = 0;
        while ($i < 5) {
            $html = str_replace('  ', ' ', $html);
            $i++;
        }
        return $html;
    }

    function SelectWithTable2($lang = 'vi-VN', $tablename, $where, $orderby, $idName, $datatextfield, $datavaluefield, $matchSelected, $arrayOption = NULL) {
        try {
            $att       = '';
            $firstText = '';
            $char      = '';
            if (!empty($arrayOption) && count($arrayOption) > 0) {
                foreach ($arrayOption as $name => $value) {
                    if ($name == 'firstText') {
                        $firstText = $value;
                    }
                    else if ($name == 'char') {
                        $char = $value;
                    }
                    else {
                        $att .= ' ' . $name . '="' . $value . '"';
                    }
                }
            }
            $str = '<select id="' . $idName . '" name="' . $idName . '"' . $att . '>';
            if (!empty($firstText)) {
                $str .= '<option value="">' . $firstText . '</option>';
            }
            $array = $this->getArray($tablename, $where, $orderby);
            if (is_array($array)) {
                foreach ($array as $rs) {
                    $str .= '<option value="' . $rs[$datavaluefield] . '"' . ($rs[$datavaluefield] == $matchSelected ? ' selected="selected"' : '') . '>' . stripslashes($char . ($lang == 'en-US' ? $this->stripUnicode($rs[$datatextfield]) : $rs[$datatextfield])) . '</option>';
                }
            }
            $str .= '</select>';
            unset($array, $rs);
            return $str;
        } catch (Exception $ex) {
            return '';
        }
    }

    function getStore($lang = 'vi-VN') {
        $info = '';
        $rst  = $this->getDynamicJoin(prefixTable . 'setting_desc', prefixTable . 'setting', ['type' => 'type'], 'INNER JOIN', 't1.lang = "' . $lang . '" AND t2.status = 1', '', 't2.id = t1.id');
        if ($rst) {
            while ($row = $this->nextObject($rst)) {
                if (in_array($row->type, [
                    'company',
                    'address',
                    'hotline1',
                    'mailcontact',
                    'website',
                ])) {
                    $info[$row->type] = stripslashes($row->content);
                }
            }
            $this->freeResult($rst);
        }
        return $info;
    }

    function getCountry($lang = 'vi-VN', $id) {
        $rst = $this->getDynamic(prefixTable . "country", "status=1 and id = " . $id, "");
        if ($this->totalRows($rst) == 1) {
            $row = $this->nextObject($rst);
            return stripslashes($lang == 'en-US' ? $this->stripUnicode($row->name) : $row->name);
        }
    }

    function getCity($lang = 'vi-VN', $id) {
        $rst = $this->getDynamic(prefixTable . "city", "status=1 and id = " . $id, "");
        if ($this->totalRows($rst) == 1) {
            $row = $this->nextObject($rst);
            return stripslashes($lang == 'en-US' ? $this->stripUnicode($row->name) : $row->name);
        }
    }

    function getDistrict($lang = 'vi-VN', $id) {
        $rst = $this->getDynamic(prefixTable . "district", "status=1 and id = " . $id, "");
        if ($this->totalRows($rst) == 1) {
            $row = $this->nextObject($rst);
            return stripslashes($lang == 'en-US' ? $this->stripUnicode($row->name) : $row->name);
        }
    }

    function showBankInfo($lang = 'vi-VN', $html = '') {
        $rst = $this->getDynamicJoin(prefixTable . 'setting', prefixTable . 'setting_desc', ['content' => 'content'], 'INNER JOIN', 't1.status = 1 and t1.type = "banking" and t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
        if ($this->totalRows($rst) == 1) {
            $row  = $this->nextObject($rst);
            $html = $this->compressHtml(stripslashes($row->content));
        }
        return $html;
    }

    function getCatIdByProId($pid, $cid = 0) {
        $rst = $this->getDynamicJoin(prefixTable . 'product', prefixTable . 'category', [], 'inner join', 't1.status = 1 and t2.status = 1 and t1.id = ' . $pid, '', 't2.id = t1.cid');
        if ($this->totalRows($rst) == 1) {
            $row = $this->nextObject($rst);
            $this->freeResult($rst);
            $cid = $row->cid;
        }
        return $cid;
    }

    function getDefaultColorCode($pid, $c = '') {
        $rst = $this->getDynamic(prefixTable . 'product', 'id = ' . $pid, '');
        if ($this->totalRows($rst) == 1) {
            $row = $this->nextObject($rst);
            $this->freeResult($rst);
            $i = unserialize($row->info);
            if (!empty($i['color']) && isset($i['color']) && is_array($i['color']) && count($i['color']) > 0) {
                $a = array_keys($i['color']);
                if (is_array($a) && count($a) > 0) {
                    $c = isset($i['color'][$a[0]]['code']) ? $i['color'][$a[0]]['code'] : '';
                }
            }
        }
        return $c;
    }

    function getDefaultSizeCode($pid, $s = '') {
        $rst = $this->getDynamic(prefixTable . 'product', 'id = ' . $pid, '');
        if ($this->totalRows($rst) == 1) {
            $row = $this->nextObject($rst);
            $this->freeResult($rst);
            $i = unserialize($row->info);
            if (!empty($i['size']) && isset($i['size']) && is_array($i['size']) && count($i['size']) > 0) {
                $a = array_keys($i['size']);
                if (is_array($a) && count($a) > 0) {
                    $s = isset($i['size'][$a[0]]['code']) ? $i['size'][$a[0]]['code'] : '';
                }
            }
        }
        return $s;
    }

    function getColorbyCode($code, $lang = 'vi-VN', $c = '') {
        $rst = $this->getDynamicJoin(prefixTable . 'color', prefixTable . 'color_desc', ['name' => 'name'], 'INNER JOIN', 't1.status = 1 AND t2.lang = "' . $lang . '" AND t1.code = "' . $code . '"', '', 't2.id = t1.id');
        if ($this->totalRows($rst) == 1) {
            $c = $this->nextObject($rst);
            $this->freeResult($rst);
        }
        return $c;
    }

    function getSizebyCode($code, $lang = 'vi-VN', $c = '') {
        $rst = $this->getDynamicJoin(prefixTable . 'product_size', prefixTable . 'psize_desc', ['name' => 'name'], 'INNER JOIN', 't1.status = 1 AND t2.lang = "' . $lang . '" AND t1.code = "' . $code . '"', '', 't2.id = t1.id');
        if ($this->totalRows($rst) == 1) {
            $c = $this->nextObject($rst);
            $this->freeResult($rst);
        }
        return $c;
    }

    function buildOrderMsg($oid, $def, $_LNG, $lang = 'vi-VN', $msg = '') {
        $rst = $this->getDynamic(prefixTable . 'order', 'id = ' . $oid, '');
        if ($this->totalRows($rst) == 1) {
            $row = $this->nextObject($rst);
            $this->freeResult($rst);
            $note_zone = !is_null($row->order_note) ? '<p>' . nl2br(stripslashes($row->order_note)) . '</p><p></p>' : '';
            $tax_zone  = (!empty($row->company) && !empty($row->company_address) && !empty($row->tax_code)) ? '<p>' . $_LNG->checkout->tax->company . ': ' . stripslashes($row->company) . '<br />' . $_LNG->checkout->tax->address . ': ' . stripslashes($row->company_address) . '<br />' . $_LNG->checkout->tax->code . ': ' . $row->tax_code . '</p><p></p>' : '';

            $odd_txt = '';
            $sub_txt = '';
            $tax_txt = '';

            $odd = $this->getArray(prefixTable . 'order_detail', 'order_id = "' . $oid . '"', '', 'stdObject');
            if (!empty($odd) && is_array($odd) && count($odd) > 0) {
                $n = 1;
                foreach ($odd as $i) {
                    $_id  = explode('|', $i->product_id);
                    $product = $this->getProductInfo($_id[0]);
                    if(!empty($_id[2])) {
                        $price = $this->getPrice($_id[0], $_id[2]);
                        $price = $price['price'];
                    } else {
                        $price = $i->price;
                        if(!empty($product['wholesale'])) {
                            $wholesale = unserialize($product['wholesale']);
                            $price = $this->getPriceByWholesale($wholesale, $i->quantity);
                        }
                    }
                    $odd_txt .= '<tr align="left" height="20">';
                    $odd_txt .= '<td align="center" style="padding:3px 9px">' . $n . '</td>';
                    $odd_txt .= '<td align="left" valign="top" style="padding:3px 9px"><a href="' . $i->link . '" target="_blank">' . stripslashes($i->name) . '</a>';
                    if (!empty($_id[2])) {
                        if(!empty($this->getColorbyCode($_id[2]))) {
                            $color = $this->getColorbyCode($_id[2]);
                            $odd_txt .= '<br><label>Màu sắc:</label> ' . $color->name;
                        }
                        if(!empty($this->getSizebyCode($_id[2]))) {
                            $size = $this->getSizebyCode($_id[2]);
                            $odd_txt .= '<br><label>Kích thước:</label> ' . $size->name;
                        }
                    }
                    $odd_txt .= '</td>';
                    $odd_txt .= '<td align="right" style="padding:3px 9px">' . ($price > 0 ? $this->pricevnd($price, $_LNG->product->currency) : $_LNG->contact->title) . '&nbsp;</td>';
                    $odd_txt .= '<td align="center" style="padding:3px 9px">' . $i->quantity . '</td>';
                    $amout   = $price * $i->quantity;
                    $odd_txt .= '<td align="right" style="padding:3px 9px">' . ($amout > 0 ? $this->pricevnd($amout, $_LNG->product->currency) : $_LNG->contact->title) . '&nbsp;</td>';
                    $odd_txt .= '</tr>';
                    $n++;
                }
            }

            if ($row->cost > $row->sub_total) {
                $sub_txt = '<tr align="left" height="20">';
                $sub_txt .= '<td align="right" colspan="4" style="padding:3px 9px">' . $_LNG->cart->subtotal . '&nbsp;</td>';
                $sub_txt .= '<td align="right" style="padding:3px 9px">' . ($row->sub_total > 0 ? $this->pricevnd($row->sub_total, $_LNG->product->currency) : $_LNG->contact->title) . '&nbsp;</td>';
                $sub_txt .= '</tr>';

                $tax_txt = '<tr align="left" height="20" style="padding:3px 9px">';
                $tax_txt .= '<td align="right" colspan="4">' . $_LNG->checkout->vat . ' (' . $row->tax_rate . '%)&nbsp;</td>';
                $tax_txt .= '<td align="right" style="padding:3px 9px">' . ($row->tax_amount > 0 ? $this->pricevnd($row->tax_amount, $_LNG->product->currency) : $_LNG->contact->title) . '&nbsp;</td>';
                $tax_txt .= '</tr>';
            }

            $msg          = new stdClass();
            $msg->title   = $_LNG->others->finished->mailtitle . $this->upcaseFirst(SITE_NAME);
            $msg->content = file_get_contents(PNSDOTVN_EMA . DS . 'order_' . $lang . EXT);
            $msg->content = str_replace([
                '{title}',
                '{HOST}',
                '{SITE_NAME}',
                '{billing_name}',
                '{billing_address}',
                '{billing_ward}',
                '{billing_district}',
                '{billing_city}',
                '{billing_country}',
                '{billing_phone}',
                '{billing_mobile}',
                '{shipping_name}',
                '{shipping_address}',
                '{shipping_ward}',
                '{shipping_district}',
                '{shipping_city}',
                '{shipping_country}',
                '{shipping_phone}',
                '{shipping_mobile}',
                '{payment_zone}',
                '{note_zone}',
                '{tax_zone}',
                '{order_code}',
                '{order_created}',
                '{odd_txt}',
                '{sub_txt}',
                '{tax_txt}',
                '{total}',
                '{mailcontact}',
                '{hotline1}',
            ], [
                $msg->title,
                HOST,
                SITE_NAME,
                stripslashes($row->billing_name),
                stripslashes($row->billing_address),
                stripslashes($row->billing_ward_name),
                stripslashes($row->billing_district_name),
                stripslashes($row->billing_city_name),
                stripslashes($row->billing_country_name),
                $row->billing_phone,
                $row->billing_mobile,
                stripslashes($row->shipping_name),
                stripslashes($row->shipping_address),
                stripslashes($row->shipping_ward_name),
                stripslashes($row->shipping_district_name),
                stripslashes($row->shipping_city_name),
                stripslashes($row->shipping_country_name),
                $row->shipping_phone,
                $row->shipping_mobile,
                stripslashes($row->payment_name),
                $note_zone,
                $tax_zone,
                $row->order_code,
                date('d-m-Y H:i:s', $row->ordered),
                $odd_txt,
                $sub_txt,
                $tax_txt,
                ($row->cost > 0 ? $this->pricevnd($row->cost, $_LNG->product->currency) : $_LNG->contact->title),
                $def->mailcontact,
                $def->hotline1,
            ], $msg->content);

            $m = $this->getArray(prefixTable . 'mail_template', 'status = 1 AND name = "order" AND lang = "' . $lang . '"', '', 'stdObject');
            if (count($m) == 1) {
                $msg->title   = str_replace('{site_name}', $this->upcaseFirst(SITE_NAME), $m[0]->title);
                $msg->content = str_replace([
                    '{title}',
                    '{HOST}',
                    '{SITE_NAME}',
                    '{billing_name}',
                    '{billing_address}',
                    '{billing_ward}',
                    '{billing_district}',
                    '{billing_city}',
                    '{billing_country}',
                    '{billing_phone}',
                    '{billing_mobile}',
                    '{shipping_name}',
                    '{shipping_address}',
                    '{shipping_ward}',
                    '{shipping_district}',
                    '{shipping_city}',
                    '{shipping_country}',
                    '{shipping_phone}',
                    '{shipping_mobile}',
                    '{payment_zone}',
                    '{note_zone}',
                    '{tax_zone}',
                    '{order_code}',
                    '{order_created}',
                    '{odd_txt}',
                    '{sub_txt}',
                    '{tax_txt}',
                    '{total}',
                    '{mailcontact}',
                    '{hotline1}',
                ], [
                    $msg->title,
                    HOST,
                    SITE_NAME,
                    stripslashes($row->billing_name),
                    stripslashes($row->billing_address),
                    stripslashes($row->billing_ward_name),
                    stripslashes($row->billing_district_name),
                    stripslashes($row->billing_city_name),
                    stripslashes($row->billing_country_name),
                    $row->billing_phone,
                    $row->billing_mobile,
                    stripslashes($row->shipping_name),
                    stripslashes($row->shipping_address),
                    stripslashes($row->shipping_ward_name),
                    stripslashes($row->shipping_district_name),
                    stripslashes($row->shipping_city_name),
                    stripslashes($row->shipping_country_name),
                    $row->shipping_phone,
                    $row->shipping_mobile,
                    stripslashes($row->payment_name),
                    $note_zone,
                    $tax_zone,
                    $row->order_code,
                    date('d-m-Y H:i:s', $row->ordered),
                    $odd_txt,
                    $sub_txt,
                    $tax_txt,
                    ($row->cost > 0 ? $this->pricevnd($row->cost, $_LNG->product->currency) : $_LNG->contact->title),
                    $def->mailcontact,
                    $def->hotline1,
                ], $m[0]->content);
            }

        }
        return $msg;
    }

    function buildMiniCart($route, $cart, $_LNG, $lng = 'vi', $lang = 'vi-VN', $html = '') {
        $html = '<li id="top-cart">';
        if ($route->name <> 'orderfinished' && isset($_SESSION['PNSDOTVN_CART']) && !empty($_SESSION['PNSDOTVN_CART']) && count($_SESSION['PNSDOTVN_CART'])) {
            $pns_cart = $_SESSION['PNSDOTVN_CART'];
            $i        = 0;
            #$html .= '<div class="cart" id="my-cart"><a class="cart-icon" href="' . (MULTI_LANG ? DS . $lng : '') . DS . $_LNG->cart->rewrite . EXT . '" title="' . $_LNG->cart->yourcart . '">' . $_LNG->cart->yourcart . ' (<span id="item-cart">' . ($route->name <> 'orderfinished' ? $cart->pcount() : 0) . '</span>)</a>';
            $html .= '<div class="cart" id="my-cart"><a href="' . (MULTI_LANG ? DS . $lng : '') . DS . $_LNG->cart->rewrite . EXT . '" title="' . $_LNG->cart->yourcart . '"><i class="fa fa-shopping-cart"></i></a> <span id="item-amount">' . ($route->name <> 'orderfinished' ? $this->pricevnd($cart->totalprice(), $_LNG->product->currency) : 0) . '</span><br /><span id="item-cart">' . ($route->name <> 'orderfinished' ? $cart->pcount() : 0) . ' sản phẩm</span>';
            $html .= '<div class="cart-content">';
            $html .= '<ul class="cart-detail">';
            foreach ($pns_cart as $id => $qty) {
                $_id     = explode('|', $id);
                $_itemid = $_id[0] . (isset($_id[1]) && !empty($_id[1]) ? '_' . $_id[2] : '_nocolor-0') . (isset($_id[3]) && !empty($_id[3]) ? '_' . $_id[4] : '_nosize-0');
                $rst     = $this->getDynamicJoin(prefixTable . 'product', prefixTable . 'product_desc', [
                    'name'    => 'name',
                    'rewrite' => 'rewrite',
                ], 'INNER JOIN', 't1.id = ' . $_id[0] . ' AND t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
                if ($this->totalRows($rst) == 1) {
                    $row = $this->nextObject($rst);
                    $this->freeResult($rst);
                    $pic   = explode(';', $row->picture);
                    $color = $this->getColorbyCode($_id[2], $lang);
                    $size  = $this->getSizebyCode($_id[4], $lang);
                    $tmp   = isset($color->name) || isset($size->name) ? ' (' . (!empty($color->name) ? stripslashes($color->name) : '') . (!empty($color->name) && !empty($size->name) ? ' - ' : '') . (!empty($size->name) ? stripslashes($size->name) : '') . ')' : '';
                    $title = str_replace('"', '', $row->name);
                    $html  .= '<li class="item' . ($i == 0 || $i % 2 == 0 ? 2 : 1) . '" id="pd_' . $_itemid . '"><a href="' . (MULTI_LANG ? DS . $lng : '') . DS . $_LNG->others->product->rewrite . DS . $row->rewrite . '-' . $row->id . EXT . '" title="' . $title . '"><img alt="' . $row->rewrite . '" src="/thumb/400x400/1:1' . $pic[0] . '" width="50" height="50" title="' . $title . '" /><p>' . stripslashes($row->name) . '</p><p class="price">' . ($row->price > 0 ? $this->pricevnd($row->price, $_LNG->product->currency) : $_LNG->contact->title) . '</p><p>x<span id="qty_' . $_itemid . '">' . $qty . '</span><span id="desc_' . $_itemid . '">' . $tmp . '</span></p></a></li>';
                    $i++;
                }
            }
            $html .= '</ul><div class="cart-view"><a href="' . (MULTI_LANG ? DS . $lng : '') . DS . $_LNG->cart->rewrite . EXT . '" title="' . $_LNG->cart->cartdetail . '">' . $_LNG->cart->cartdetail . '</a> | <a href="' . (MULTI_LANG ? DS . $lng : '') . DS . $_LNG->checkout->rewrite . EXT . '" title="' . $_LNG->checkout->title . '">' . $_LNG->checkout->title . '</a></div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        else #$html .= '<div class="cart" id="my-cart"><a class="cart-icon" href="' . (MULTI_LANG ? DS . $lng : '') . DS . $_LNG->cart->rewrite . EXT . '" title="' . $_LNG->cart->yourcart . '">' . $_LNG->cart->yourcart . ' (<span id="item-cart">0</span>)</a></div>';
        {
            $html .= '<div class="cart" id="my-cart"><a href="' . (MULTI_LANG ? DS . $lng : '') . DS . $_LNG->cart->rewrite . EXT . '" title="' . $_LNG->cart->yourcart . '"><i class="fa fa-shopping-cart"></i></a> <span id="item-amount">0 đ</span><br /><span id="item-cart">0 sản phẩm</span></div>';
        }
        $html .= '</li>';
        return $html;
    }


    function buildMiniCart2($route, $cart, $_LNG, $lng = 'vi', $lang = 'vi-VN', $html = '') {
        $html = '<div class="dropdown-menu-container">
		  <div class="dropdown-menu no-border-radius col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<h4><a href="/gio-hang.html" rel="nofollow">Giỏ hàng</a></h4>
			<p class="divider"></p>
			<ul class="s-list no-padding">';
        if ($route->name <> 'orderfinished' && isset($_SESSION['PNSDOTVN_CART']) && !empty($_SESSION['PNSDOTVN_CART']) && count($_SESSION['PNSDOTVN_CART'])) {
            $pns_cart = $_SESSION['PNSDOTVN_CART'];
            $i        = 0;
            foreach ($pns_cart as $id => $qty) {
                $_id     = explode('|', $id);
                $_itemid = $_id[0] . (isset($_id[1]) && !empty($_id[1]) ? '_' . $_id[2] : '_nocolor-0') . (isset($_id[3]) && !empty($_id[3]) ? '_' . $_id[4] : '_nosize-0');
                $rst     = $this->getDynamicJoin(prefixTable . 'product', prefixTable . 'product_desc', [
                    'name'    => 'name',
                    'rewrite' => 'rewrite',
                ], 'INNER JOIN', 't1.id = ' . $_id[0] . ' AND t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
                if ($this->totalRows($rst) == 1) {
                    $row = $this->nextObject($rst);
                    $this->freeResult($rst);
                    $pic   = explode(';', $row->picture);
                    $color = $this->getColorbyCode($_id[2], $lang);
                    $size  = $this->getSizebyCode($_id[4], $lang);
                    $tmp   = isset($color->name) || isset($size->name) ? ' (' . (!empty($color->name) ? stripslashes($color->name) : '') . (!empty($color->name) && !empty($size->name) ? ' - ' : '') . (!empty($size->name) ? stripslashes($size->name) : '') . ')' : '';
                    $title = str_replace('"', '', $row->name);
                    $html  .= '<li class="row">
					  <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"> <img src="/thumb/400x400/1:1' . $pic[0] . '" alt="shoe" class="img-responsive"> </div>
					  <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
						<h6><strong> ' . stripslashes($row->name) . ' </strong><span class="text-muted pull-right"> x ' . $qty . ' </span></h6>
						<h6> ' . ($row->price > 0 ? $this->pricevnd($row->price, $_LNG->product->currency) : $_LNG->contact->title) . ' </h6>
					  </div>
					</li>
					<li class="divider margin-bottom-1"></li>';
                    $i++;
                }
            }
        }
        $html .= '<li class="row">
				<div class="col-lg-6">
				  <h2 class="margin-top-0 text-primary"> ' . $this->pricevnd($cart->totalprice(), $_LNG->product->currency) . ' </h2>
				</div>
				<div class="col-lg-6">
				  <button onclick="window.location.href=\'/thanh-toan.html\'" class="btn btn-success btn-block btn-mina btn-mina-rip-m pull-right"> Thanh toán </button>
				</div>
			  </li>
			</ul>
		  </div>
		</div>';
        return $html;
    }

    function getMemberInfo($id, $data = NULL) {
        if (!empty($id)) {
            $rst = $this->getDynamicJoin(prefixTable . 'customer', prefixTable . 'customer_address', [
                'full_address'  => 'full_address',
                'address'       => 'address',
                'district_id'   => 'district_id',
                'district_name' => 'district_name',
                'city_id'       => 'city_id',
                'city_name'     => 'city_name',
                'country_id'    => 'country_id',
                'country_name'  => 'country_name',
            ], 'inner join', "t1.status = 1 and t1.id = " . $id, '', 't2.csid = t1.id');
            if ($this->totalRows($rst) > 0) {
                $row  = $this->nextObject($rst);
                $data = [
                    'name'          => $row->name,
                    'email'         => $row->email,
                    'address'       => $row->address,
                    'full_address'  => $row->full_address,
                    'district_id'   => $row->district_id,
                    'district_name' => $row->district_name,
                    'city_id'       => $row->city_id,
                    'city_name'     => $row->city_name,
                    'country_id'    => $row->country_id,
                    'country_name'  => $row->country_name,
                    'phone'         => $row->phone,
                    'mobile'        => $row->mobile,
                    'groupid'       => $row->groupid,
                    #'birthdate' => $row->birthdate,
                    #'pin' => $row->pin,
                    #'salutation' => $row->salutation,
                    #'gender' => $row->gender,
                    #'wishlist' => $row->wishlist,
                    #'point' => $row->point
                ];
            }
        }
        return $data;
    }

    // => NEW <=

    function printr($d, $e = FALSE) {
        echo '<pre>';
        print_r($d);
        echo '</pre>', "\n";
        if ($e == TRUE) {
            exit;
        }
    }

    function getMenu($p, $d, $pid = 0, $data = '') {
        switch ($p) {
            case 'main' :
                $q = $this->Query('SELECT t2.id, t1.name, t1.rewrite, t1.metatitle AS title FROM ' . prefixTable . 'menu_desc t1 INNER JOIN ' . prefixTable . 'menu t2 ON t2.id = t1.id INNER JOIN ' . prefixTable . 'menu_ordering t3 ON t3.menuid = t2.id INNER JOIN ' . prefixTable . 'menu_types t4 ON t4.id = t3.typeid WHERE t1.lang = "' . $d->code . '" AND t2.status = 1 AND t2.parentid = ' . $pid . ' AND t4.menutype = "mainmenu" ORDER BY t3.ordering, t1.name');
                break;
            case 'catalog' :
                $q = $this->Query('SELECT t2.id, t1.name, t1.rewrite, t1.metatitle AS title FROM ' . prefixTable . 'category_desc t1 INNER JOIN ' . prefixTable . 'category t2 ON t2.id = t1.id WHERE t1.lang = "' . $d->code . '" AND t2.status = 1' . ($pid == 0 ? ' AND t2.showmain = 1' : '') . ' AND t2.parentid = ' . $pid . ' ORDER BY t2.ordering, t1.name');
                break;
            case 'm-catalog' :
                $q = $this->Query('SELECT t2.id, t1.name, t1.rewrite, t1.metatitle AS title FROM ' . prefixTable . 'category_desc t1 INNER JOIN ' . prefixTable . 'category t2 ON t2.id = t1.id WHERE t1.lang = "' . $d->code . '" AND t2.status = 1 AND t2.parentid = ' . $pid . ' ORDER BY t2.ordering, t1.name');
                break;
        }
        if (isset($q) && !empty($q) && $this->totalRows($q)) {
            while ($r = $this->nextObject($q)) {
                $r->name = stripslashes($r->name);
                switch ($p) {
                    case 'main' :
                        $r->href = (in_array($r->rewrite, [
                            'trang-chu',
                            'home',
                        ]) ? HOST : ((MULTI_LANG ? DS . $d->code2 : '') . DS . $r->rewrite . EXT));
                        break;
                    case 'catalog' :
                    case 'm-catalog' :
                        $r->href = ((MULTI_LANG ? DS . $d->code2 : '') . DS . $r->rewrite . '-' . $r->id . EXT);
                        break;
                }
                $data[] = [
                    'catalog' => $r,
                    'child'   => ($r->rewrite == 'san-pham' ? $this->getCatalogMenu($d->code) : $this->getMenu($p, $d, $r->id)),
                ];
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function getCatalogMenu($l, $pid = 0, $t = '', $data = '') {
        $q = $this->Query('SELECT t2.id, t2.picture, t1.name, t1.rewrite, t1.metatitle AS title FROM ' . prefixTable . 'category_desc t1 INNER JOIN ' . prefixTable . 'category t2 ON t2.id = t1.id WHERE t1.lang = "' . $l . '" AND t2.status = 1 AND t2.parentid = ' . $pid . ' ORDER BY t2.ordering, t1.name');
        if ($this->totalRows($q)) {
            while ($r = $this->nextObject($q)) {
                $r->name = stripslashes($r->name);
                $r->href = ((MULTI_LANG ? DS . $d->code2 : '') . DS . $r->rewrite . '-' . $r->id . EXT);
                $data[]  = [
                    'catalog' => $r,
                    'child'   => $this->getCatalogMenu($l, $r->id),
                ];
            }
            $this->freeResult($q);
        }
        else if ($t == 2) {
            $q = $this->Query('SELECT t2.parentid FROM ' . prefixTable . 'category_desc t1 INNER JOIN ' . prefixTable . 'category t2 ON t2.id = t1.id WHERE t1.lang = "' . $l . '" AND t2.status = 1 AND t2.id = ' . $pid . ' LIMIT 1');
            if ($this->totalRows($q) == 1) {
                $r = $this->nextObject($q);
                $this->freeResult($q);
                $data = $this->getCatalogMenu($l, $r->parentid, $t);
            }
        }
        return $data;
    }

    function getAdvertising($p, $data = '') {
        $q = $this->Query('SELECT name, bannerurl AS src, clickurl AS href, target, nofollow FROM ' . prefixTable . 'banner WHERE status = 1 AND position = "' . $p . '" ORDER BY ordering, id DESC');
        if ($this->totalRows($q)) {
            while ($r = $this->nextObject($q)) {
                $data[] = $r;
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function getCatalog($d, $s = FALSE, $data = '') {
        $q = $this->Query('SELECT t1.id, t2.name, t2.rewrite, t2.metatitle AS title, t1.jbanner, t2.html FROM ' . prefixTable . 'category t1 INNER JOIN ' . prefixTable . 'category_desc t2 ON t2.id = t1.id WHERE t1.status = 1' . ($s ? ' AND t1.showindex = 1' : ' AND t1.parentid = 0') . ' AND t2.lang = "' . $d->code . '" ORDER BY t1.ordering, t2.name');
        if ($this->totalRows($q)) {
            while ($r = $this->nextObject($q)) {
                $r->name   = stripslashes($r->name);
                $r->href   = ((MULTI_LANG ? DS . $d->code2 : '') . DS . $r->rewrite . '-' . $r->id . EXT);
                $r->banner = $this->arrayToObject(unserialize($r->jbanner));
                if (isset($r->banner->banner1->status) && $r->banner->banner1->status) {
                    $r->banner1 = $r->banner->banner1;
                }
                if (isset($r->banner->banner2->status) && $r->banner->banner2->status) {
                    $r->banner2 = $r->banner->banner2;
                }
                $t1     = $this->getChildCategory($r->id);
                $t2     = (is_array($t1) && count($t1) > 0) ? implode(',', $t1) : $r->id;
                $d->ids = $t2;
                $data[] = [
                    'catalog' => $r,
                    'child'   => $this->getProduct($d),
                ];
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function getProduct($d, $data = '') {
        $f = '';
        if (isset($_SESSION['PNSDOTVN_PRODUCT_FILTER']) && is_array($_SESSION['PNSDOTVN_PRODUCT_FILTER']) && !empty($_SESSION['PNSDOTVN_PRODUCT_FILTER']) && count($_SESSION['PNSDOTVN_PRODUCT_FILTER']) > 0) {
            $filter = $this->getFilter($d);
            #$this->printr($_SESSION['PNSDOTVN_PRODUCT_FILTER']);
            foreach ($_SESSION['PNSDOTVN_PRODUCT_FILTER'] as $k => $v) {
                switch ($k) {
                    case 'updated' :
                        if (in_array($v, array_keys($d->UPDATE_ADDON))) {
                            $t1 = date("Y-m-d H:i:s");
                            $t2 = date("Y-m-d H:i:s", strtotime($t1) - (60 * 60 * 24 * $d->UPDATE_ADDON[$v]['day']));
                            $f  .= ' AND (t1.modified BETWEEN "' . $t2 . '" AND "' . $t1 . '")';
                        }
                        break;
                    case 'saleoff' :
                        if (in_array($v, array_keys($d->SALEOFF_ADDON))) {
                            $f .= ' AND (t1.sale_off BETWEEN ' . $d->SALEOFF_ADDON[$v]['from'] . ' AND ' . $d->SALEOFF_ADDON[$v]['to'] . ')';
                        }
                        break;
                    case 'price' :
                        $tmp = explode(';', $v);
                        $f   .= ' AND t1.price BETWEEN ' . $tmp[0] . ' AND ' . $tmp[1];
                        /*
						if (isset($d->PriceFilter) && isset($d->PriceFilterSQL)) {
							$key = array_search($v, $d->PriceFilter);
							$f .= ' AND ' . $d->PriceFilterSQL[$key];
						}
						*/
                        break;
                    case 'brand' :
                        $q = $this->Query('SELECT id FROM ' . prefixTable . 'brand WHERE status = 1 AND name = "' . $this->checkValues(str_replace('+', ' ', $v)) . '" LIMIT 1');
                        if ($this->totalRows($q)) {
                            $v = $this->nextObject($q);
                            $this->freeResult($q);
                            $f           .= ' AND t1.' . $k . ' = "' . $v->id . '"';
                            $d->filter[] = 'brand';
                        }
                        break;
                    case 'supplier' :
                        $q = $this->Query('SELECT id FROM ' . prefixTable . 'supplier WHERE status = 1 AND name = "' . $this->checkValues(str_replace('+', ' ', $v)) . '" LIMIT 1');
                        if ($this->totalRows($q)) {
                            $v = $this->nextObject($q);
                            $this->freeResult($q);
                            $f .= ' AND t1.' . $k . ' = "' . $v->id . '"';
                        }
                        break;
                    case 'keyword' :
                        $f .= '';
                        break;
                    /*
					default :
				 		$f .= ' AND t1.' . $k . ' = "' .  $v . '"';
						break;
					*/
                }
                if (is_array($filter) && count($filter) > 0 && in_array($k, $filter)) {
                    $q = $this->Query('SELECT t2.id, t3.id AS oid FROM ' . prefixTable . 'product_option_data_desc t1 INNER JOIN ' . prefixTable . 'product_option_data t2 ON t2.id = t1.id INNER JOIN ' . prefixTable . 'product_option t3 ON t3.id = t2.oid WHERE t2.status = 1 AND t1.lang = "' . $d->code . '" AND t1.name = "' . $v . '" AND t3.status = 1 AND t3.code = "' . $k . '" LIMIT 1');
                    if ($this->totalRows($q) == 1) {
                        $r = $this->nextObject($q);
                        $this->freeResult($q);
                        $f           .= ' AND t1.joption LIKE "%opt' . $r->oid . '_' . $r->id . '%"';
                        $d->filter[] = $k;
                    }
                }
            }
        }
        $q = '';
        #$this->printr($d);
        switch ($d->route->name) {
            case 'search' :
                $d->PageUrl = (isset($d->search) && !empty($d->search) ? '?keyword=' . str_replace(' ', '+', $d->search) : '');
                $a          = '';
                if (isset($d->search) && !empty($d->search)) {
                    $b[] = 't2.name LIKE "%' . $d->search . '%"';
                    $b[] = 't1.code LIKE "%' . $d->search . '%"';
                    #$b[] = 't2.introtext LIKE "%' . $d->search . '%"';
                    #$b[] = 't2.description LIKE "%' . $d->search . '%"';
                    $b[] = 't2.metatitle LIKE "%' . $d->search . '%"';
                    $b[] = 't2.metakey LIKE "%' . $d->search . '%"';
                    $b[] = 't2.metadesc LIKE "%' . $d->search . '%"';
                    $a   = ' AND (' . implode(' OR ', $b) . ')';
                }
                if (isset($d->catalog_search) && $d->catalog_search > 0) {
                    $cs1        = $this->getChildCategory($d->catalog_search);
                    $cs2        = (is_array($cs1) && count($cs1) > 0) ? implode(',', $cs1) . ',' . $d->catalog_search : $d->catalog_search;
                    $a          .= ' AND t1.cid IN (' . $cs2 . ')';
                    $d->PageUrl .= '&catalog=' . $d->catalog_search;
                }
                break;
            case 'brand' :
                switch ($d->route->type) {
                    case 'all' :
                        $q = $this->pagingQueryJoin(prefixTable . 'brand t1', 't1.status = 1 AND t2.lang = "' . $d->code . '"', 't1.ordering, t1.created DESC', 't1.id, t1.picture AS src, t2.name, t2.rewrite, t2.metatitle AS title', '', [prefixTable . 'brand_desc t2' => 't2.id = t1.id'], 'INNER JOIN', $d->PageUrl, $d->PageNo, $d->PageSize, $d->Pagenumber, $d->ModePaging);
                        break;
                    case 'detail' :
                        $q = $this->pagingQueryJoin(prefixTable . 'product t1', 't1.status = 1 AND t1.brand = ' . $d->route->id . ' AND t2.lang = "' . $d->code . '" AND t3.status = 1 AND t4.status = 1' . $f, (isset($d->orderby) ? $d->orderby : 't1.created DESC'), 't1.id, t1.picture AS src, t1.list_price, t1.sale_off, t1.price, t1.new, t1.outofstock, t1.brand, t2.name, t2.rewrite, t2.introtext, t2.metatitle AS title', '', [
                            prefixTable . 'product_desc t2' => 't2.id = t1.id',
                            prefixTable . 'category t3'     => 't3.id = t1.cid',
                            prefixTable . 'brand t4'        => 't4.id = t1.brand',
                        ], 'INNER JOIN', $d->PageUrl, $d->PageNo, $d->PageSize, $d->Pagenumber, $d->ModePaging);
                        break;
                }
                break;
            case 'supplier' :
                switch ($d->route->type) {
                    case 'all' :
                        $q = $this->pagingQueryJoin(prefixTable . 'supplier t1', 't1.status = 1 AND t2.lang = "' . $d->code . '"', 't1.ordering, t1.created DESC', 't1.id, t1.picture AS src, t2.name, t2.rewrite, t2.metatitle AS title', '', [prefixTable . 'supplier_desc t2' => 't2.id = t1.id'], 'INNER JOIN', $d->PageUrl, $d->PageNo, $d->PageSize, $d->Pagenumber, $d->ModePaging);
                        break;
                    case 'detail' :
                        $q = $this->pagingQueryJoin(prefixTable . 'product t1', 't1.status = 1 AND t1.supplier = ' . $d->route->id . ' AND t2.lang = "' . $d->code . '" AND t3.status = 1 AND t4.status = 1' . $f, (isset($d->orderby) ? $d->orderby : 't1.created DESC'), 't1.id, t1.picture AS src, t1.list_price, t1.sale_off, t1.price, t1.new, t1.outofstock, t1.supplier, t2.name, t2.rewrite, t2.introtext, t2.metatitle AS title', '', [
                            prefixTable . 'product_desc t2' => 't2.id = t1.id',
                            prefixTable . 'category t3'     => 't3.id = t1.cid',
                            prefixTable . 'supplier t4'     => 't4.id = t1.supplier',
                        ], 'INNER JOIN', $d->PageUrl, $d->PageNo, $d->PageSize, $d->Pagenumber, $d->ModePaging);
                        break;
                }
                break;
            case 'allproduct' :
                $a = '';
                switch ($d->route->type) {
                    case 'promo' :
                        $d->PageUrl    = '#';
                        $d->PageSize   = NEW_PRODUCT_ITEM;
                        $d->PageNo     = 1;
                        $d->Pagenumber = 1;
                        $d->ModePaging = '';
                        $a             = ' AND t1.promo = 1';
                        break;
                    case 'flashsale' :
                        $d->PageUrl    = '#';
                        $d->PageSize   = NEW_PRODUCT_ITEM;
                        $d->PageNo     = 1;
                        $d->Pagenumber = 1;
                        $d->ModePaging = '';
                        $a             = ' AND t1.flashsale = 1';
                        break;
                }
                break;
            case 'category' :
                $t1 = $this->getChildCategory($d->route->id);
                $t2 = (is_array($t1) && count($t1) > 0) ? implode(',', $t1) . ',' . $d->route->id : $d->route->id;
                $a  = ' AND t1.cid IN (' . $t2 . ')';
                break;
            case 'product' :
                $d->PageUrl    = '#';
                $d->PageSize   = 1;
                $d->PageNo     = 1;
                $d->Pagenumber = 1;
                $d->ModePaging = '';
                $a             = ' AND t1.id = ' . $d->route->id;
                $c             = ', t1.code, t1.list_price, t1.sale_off, t1.cid, t1.brand, t1.qty, t1.supplier, t1.outofstock, t1.jrelated, t1.jalsobuy, t1.joption, t1.info, t1.rating_vote, t1.rating_point, t1.wholesale ,t2.introtext, t2.description, t2.guide, t2.promodesc';
                break;
            case 'related' :
                $d->PageUrl    = '#';
                $d->PageSize   = count($d->jrelated);
                $d->PageNo     = 1;
                $d->Pagenumber = 1;
                $d->ModePaging = '';
                $a             = ' AND t1.id IN (' . implode(',', $d->jrelated) . ')';
                $f             = '';
                break;
            case 'inbrand' :
                $t1            = $this->getChildCategory($d->incatalog);
                $t2            = (is_array($t1) && count($t1) > 0) ? implode(',', $t1) . ',' . $d->incatalog : $d->incatalog;
                $d->PageUrl    = '#';
                $d->PageSize   = 12;
                $d->PageNo     = 1;
                $d->Pagenumber = 1;
                $d->ModePaging = '';
                $a             = ' AND t1.cid IN (' . $t2 . ') AND t1.id != ' . $d->inproduct . ' AND t1.brand = ' . $d->inbrand;
                $f             = '';
                break;
            case 'hot' :
                $d->PageUrl    = '#';
                $d->PageSize   = HOT_PRODUCT_ITEM;
                $d->PageNo     = 1;
                $d->Pagenumber = 1;
                $d->ModePaging = '';
                $a             = ' AND t1.hot = 1';
                break;
            case 'new' :
                $d->PageUrl    = '#';
                $d->PageSize   = NEW_PRODUCT_ITEM;
                $d->PageNo     = 1;
                $d->Pagenumber = 1;
                $d->ModePaging = '';
                $a             = ' AND t1.new = 1';
                break;
            default :
                $d->PageUrl    = '#';
                $d->PageSize   = HOME_PRODUCT_ITEM;
                $d->PageNo     = 1;
                $d->Pagenumber = 1;
                $d->ModePaging = '';
                $d->orderby    = 't1.ordering2 DESC, t1.created DESC';
                $a             = (!empty($d->ids) ? ' AND t1.cid IN (' . $d->ids . ')' : '') . (isset($d->brand) ? ' AND brand = ' . $d->brand : '');
                break;
        }
        $q = empty($q) ? $this->pagingQueryJoin(prefixTable . 'product t1', 't1.status = 1 AND t2.lang = "' . $d->code . '" AND t3.status = 1' . $a . $f, (isset($d->orderby) ? $d->orderby : 't1.created DESC'), 't1.id, t1.picture AS src, t1.list_price, t1.sale_off, t1.price, t1.new, t1.outofstock, t1.brand, t1.lname, t1.lcolor, t1.rating_vote, t1.rating_point, t2.name, t2.rewrite, t2.introtext, t2.metatitle AS title' . (isset($c) ? $c : ''), '', [
            prefixTable . 'product_desc t2' => 't2.id = t1.id',
            prefixTable . 'category t3'     => 't3.id = t1.cid',
        ], 'INNER JOIN', $d->PageUrl, $d->PageNo, $d->PageSize, $d->Pagenumber, $d->ModePaging) : $q;
        if (isset($q[0]) && !empty($q[0]) && $this->totalRows($q[0])) {
            while ($r = $this->nextObject($q[0])) {
                $r->name        = stripslashes($r->name);
                $r->href        = ((MULTI_LANG ? DS . $d->code2 : '') . DS . ($d->route->name == 'brand' && $d->route->type == 'all' ? 'thuong-hieu' : ($d->route->name == 'supplier' && $d->route->type == 'all' ? 'nha-cung-cap' : 'san-pham')) . DS . $r->rewrite . '-' . $r->id . EXT);
                $r->picture     = $r->src;
                $p              = explode(';', $r->src);
                $r->src         = isset($p[0]) ? $p[0] : $r->src;
                $r->promo       = (isset($r->list_price) && isset($r->price) && $r->list_price > $r->price ? TRUE : FALSE);
                $data['list'][] = $r;
            }
            $this->freeResult($q[0]);
            if (isset($q[1]) && !empty($q[1])) {
                $data['page'] = $q[1];
            }
            $data['total'] = isset($q[2]) && $q[2] > 0 ? $q[2] : 0;
        }
        return $data;
    }

    function getBrand($d, $data = '') {
        switch ($d->route->name) {
            case 'home' :
                $a = $b = '';
                $s = 'SELECT DISTINCT t1.id, t1.picture AS src, t4.name, t4.rewrite, t4.metatitle AS title FROM ' . prefixTable . 'brand t1 INNER JOIN ' . prefixTable . 'brand_desc t4 ON t4.id = t1.id INNER JOIN ' . prefixTable . 'product t2 ON t2.brand = t1.id INNER JOIN ' . prefixTable . 'category t3 ON t3.id = t2.cid WHERE t1.status = 1 AND t2.status = 1 AND t3.status = 1 AND t3.id IN (' . $d->ids . ') AND t4.lang = "' . $d->code . '" ORDER BY t1.ordering, t4.name LIMIT 0,6';
                break;
            case 'product' :
                $a = ' AND t1.id = ' . $d->route->id;
                $b = ' LIMIT 1';
                break;
            case 'left' :
                $cond = '';
                if (isset($d->route->tmp) && $d->route->tmp == 'category') {
                    $ida  = $this->getChildCategory($d->route->id);
                    $ids  = (is_array($ida) && count($ida) > 0) ? implode(',', $ida) : $d->route->id;
                    $cond = ' AND t3.id in (' . $ids . ')';
                }
                $s = 'SELECT DISTINCT t1.id, t1.picture AS src, t4.name, t4.rewrite, t4.metatitle AS title FROM ' . prefixTable . 'brand t1 INNER JOIN ' . prefixTable . 'brand_desc t4 ON t4.id = t1.id INNER JOIN ' . prefixTable . 'product t2 ON t2.brand = t1.id INNER JOIN ' . prefixTable . 'category t3 ON t3.id = t2.cid WHERE t1.status = 1 AND t2.status = 1 AND t3.status = 1' . $cond . ' AND t4.lang = "' . $d->code . '" ORDER BY t1.ordering, t4.name';
                break;
        }
        $q = $this->Query((isset($s) && !empty($s) ? $s : 'SELECT t1.id, t1.picture AS src, t2.name, t2.rewrite, t2.metatitle AS title FROM ' . prefixTable . 'brand t1 INNER JOIN ' . prefixTable . 'brand_desc t2 ON t2.id = t1.id WHERE t1.status = 1 AND t2.lang = "' . $d->code . '"' . $a . ' ORDER BY t1.ordering, t1.id DESC' . $b));
        if ($this->totalRows($q)) {
            while ($r = $this->nextObject($q)) {
                $r->name = stripslashes($r->name);
                $r->href = (MULTI_LANG ? DS . $d->code2 : '') . DS . 'thuong-hieu' . DS . $r->rewrite . '-' . $r->id . EXT;
                $data[]  = $r;
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function getSupplier($d, $data = '') {
        switch ($d->route->name) {
            case 'home' :
                $a = $b = '';
                $s = 'SELECT DISTINCT t1.id, t1.picture AS src, t4.name, t4.rewrite, t4.metatitle AS title FROM ' . prefixTable . 'supplier t1 INNER JOIN ' . prefixTable . 'supplier_desc t4 ON t4.id = t1.id INNER JOIN ' . prefixTable . 'product t2 ON t2.supplier = t1.id INNER JOIN ' . prefixTable . 'category t3 ON t3.id = t2.cid WHERE t1.status = 1 AND t2.status = 1 AND t3.status = 1 AND t3.id IN (' . $d->ids . ') AND t4.lang = "' . $d->code . '" ORDER BY t1.ordering, t4.name LIMIT 0,6';
                break;
            case 'product' :
                $a = ' AND t1.id = ' . $d->route->id;
                $b = ' LIMIT 1';
                break;
            case 'left' :
                $cond = '';
                if (isset($d->route->tmp) && $d->route->tmp == 'category') {
                    $ida  = $this->getChildCategory($d->route->id);
                    $ids  = (is_array($ida) && count($ida) > 0) ? implode(',', $ida) : $d->route->id;
                    $cond = ' AND t3.id in (' . $ids . ')';
                }
                $s = 'SELECT DISTINCT t1.id, t1.picture AS src, t4.name, t4.rewrite, t4.metatitle AS title FROM ' . prefixTable . 'supplier t1 INNER JOIN ' . prefixTable . 'supplier_desc t4 ON t4.id = t1.id INNER JOIN ' . prefixTable . 'product t2 ON t2.supplier = t1.id INNER JOIN ' . prefixTable . 'category t3 ON t3.id = t2.cid WHERE t1.status = 1 AND t2.status = 1 AND t3.status = 1' . $cond . ' AND t4.lang = "' . $d->code . '" ORDER BY t1.ordering, t4.name';
                break;
        }
        $q = $this->Query((isset($s) && !empty($s) ? $s : 'SELECT t1.id, t1.picture AS src, t2.name, t2.rewrite, t2.metatitle AS title FROM ' . prefixTable . 'supplier t1 INNER JOIN ' . prefixTable . 'supplier_desc t2 ON t2.id = t1.id WHERE t1.status = 1 AND t2.lang = "' . $d->code . '"' . $a . ' ORDER BY t1.ordering, t1.id DESC' . $b));
        if ($this->totalRows($q)) {
            while ($r = $this->nextObject($q)) {
                $r->name = stripslashes($r->name);
                $r->href = (MULTI_LANG ? DS . $d->code2 : '') . DS . 'nha-cung-cap' . DS . $r->rewrite . '-' . $r->id . EXT;
                $data[]  = $r;
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function getNewsCatalog($d, $data = '') {
        foreach ($d->catalog as $k => $v) {
            $q = $this->Query('SELECT t2.name, t2.rewrite, t2.metatitle AS title FROM ' . prefixTable . 'menu t1 INNER JOIN ' . prefixTable . 'menu_desc t2 ON t2.id = t1.id WHERE t1.status = 1 AND t2.lang = "' . $d->code . '" AND t1.id = ' . $v . ' LIMIT 1');
            if ($this->totalRows($q)) {
                $r = $this->nextObject($q);
                $this->freeResult($q);
                $r->href = DS . $r->rewrite . EXT;
                $c       = $r;
                $t1      = $this->getChildMenu($v);
                $t2      = (is_array($t1) && count($t1) > 0) ? implode(',', $t1) . ',' . $v : $v;
                $l       = '';
                $q       = $this->Query('SELECT t1.id, t1.picture AS src, t1.mid, t2.name, t2.rewrite, t2.metatitle AS title, t2.introtext, t2.content, t1.created, t1.modified FROM ' . prefixTable . 'cms t1 INNER JOIN ' . prefixTable . 'cms_desc t2 on t2.id = t1.id INNER JOIN ' . prefixTable . 'menu t3 on t3.id = t1.mid WHERE t1.status = 1 AND t2.lang = "' . $d->code . '" AND t3.status = 1 AND t1.mid IN (' . $t2 . ') ORDER BY t1.ordering, t1.created DESC LIMIT 0,4');
                if ($this->totalRows($q) > 0) {
                    while ($r = $this->nextObject($q)) {
                        $m            = $this->getMenuAliasArray($d->code, $r->mid);
                        $r->alt       = $r->rewrite;
                        $r->href      = (MULTI_LANG ? DS . $d->code2 : '') . DS . implode(DS, $m) . DS . $r->rewrite . '-' . $r->id . EXT;
                        $r->name      = stripslashes($r->name);
                        $r->created   = strtotime($r->created);
                        $r->modified  = strtotime($r->modified);
                        $r->introtext = (!empty($r->introtext) ? $r->introtext : (isset($r->content) && !empty($r->content) ? $this->takeShortText(strip_tags($r->content), 100) : ''));
                        $l[]          = $r;
                    }
                    $this->freeResult($q);
                    $data[] = [
                        'item' => $c,
                        'list' => $l,
                    ];
                }
            }
        }
        return $data;
    }

    function getNews($d, $data = '') {
        switch ($d->route->name) {
            case 'MAYBE_INTERESTED' :
                $d->PageUrl    = '#';
                $d->PageSize   = MAYBE_INTERESTED_NEWS_ITEM;
                $d->PageNo     = 1;
                $d->Pagenumber = 1;
                $d->ModePaging = '';
                $a             = ' AND t1.id != ' . $d->route->id;
                $k             = explode(',', $d->keywords);
                if (is_array($k) && count($k) > 0) {
                    foreach ($k as $i) {
                        $j[] = 't2.metakey LIKE "%' . $i . '%"';
                    }
                    $a .= ' AND (' . implode(' OR ', $j) . ')';
                }
                $b = ', t1.created';
                break;
            case 'related' :
                $d->PageUrl    = '#';
                $d->PageSize   = RELATED_ARTICLE_ITEM;
                $d->PageNo     = 1;
                $d->Pagenumber = 1;
                $d->ModePaging = '';
                $t1            = $this->getChildMenu($d->mid);
                $t2            = (is_array($t1) && count($t1) > 0) ? implode(',', $t1) . ',' . $d->mid : $d->mid;
                $a             = ' AND t1.id != ' . $d->route->id . ' AND t1.mid IN (' . $t2 . ')';
                $b             = '';
                break;
            case 'home' :
                $d->PageUrl    = '#';
                $d->PageSize   = HOME_ARTICLE_ITEM;
                $d->PageNo     = 1;
                $d->Pagenumber = 1;
                $d->ModePaging = '';
                $a             = ' AND t3.display IN ("NEWS","LIST")';
                $b             = '';
                break;
            case 'tag' :
            case 'cms' :
                switch ($d->route->type) {
                    case 'tag' :
                        $a = ' AND t2.metakey LIKE "%' . str_replace('+', ' ', $d->route->id) . '%"';
                        break;
                    case 'all' :
                        $t1 = $this->getChildMenu($d->route->id);
                        #print_r($t1);
                        $t2 = (is_array($t1) && count($t1) > 0) ? implode(',', $t1) . ',' . $d->route->id : $d->route->id;
                        $a  = ' AND t1.mid IN (' . $t2 . ')';
                        break;
                    case 'detail' :
                        $d->PageUrl    = '#';
                        $d->PageSize   = 1;
                        $d->PageNo     = 1;
                        $d->Pagenumber = 1;
                        $d->ModePaging = '';
                        $a             = ' AND t1.id = ' . $d->route->id;
                        break;
                }
                $b = ', t2.content, t2.html1, t2.html2, t1.created, t1.modified';
                break;
        }
        if (isset($t1) && count($t1) > 0 && $d->route->name == 'cms' && $d->route->type == 'all') {
            $d->catalog      = $t1;
            $data['catalog'] = $this->getNewsCatalog($d);
        }
        $q = $this->pagingQueryJoin(prefixTable . 'cms t1', 't1.status = 1 AND t2.lang = "' . $d->code . '" AND t3.status = 1' . $a, 't1.ordering, t1.created DESC', 't1.id, t1.picture AS src, t1.mid, t2.name, t2.rewrite, t2.metatitle AS title, t1.rating_point, t1.rating_vote, t2.introtext' . $b, '', [
            prefixTable . 'cms_desc t2' => 't2.id = t1.id',
            prefixTable . 'menu t3'     => 't3.id = t1.mid',
        ], 'INNER JOIN', $d->PageUrl, $d->PageNo, $d->PageSize, $d->Pagenumber, $d->ModePaging);
        if (isset($q[0]) && !empty($q[0]) && $this->totalRows($q[0])) {
            while ($r = $this->nextObject($q[0])) {
                $m       = $this->getMenuAliasArray($d->code, $r->mid);
                $r->name = stripslashes($r->name);
                $r->href = (MULTI_LANG ? DS . $d->code2 : '') . DS . implode(DS, $m) . DS . $r->rewrite . '-' . $r->id . EXT;
                $r->alt  = $r->rewrite;
                if (in_array($d->route->name, ['cms', 'tag'])) {
                    $r->created   = strtotime($r->created);
                    $r->modified  = strtotime($r->modified);
                    $r->introtext = (!empty($r->introtext) ? $r->introtext : (isset($r->content) && !empty($r->content) ? $this->takeShortText(strip_tags($r->content), 100) : ''));
                }
                $data['list'][] = $r;
            }
            $this->freeResult($q[0]);
            if (isset($q[1]) && !empty($q[1])) {
                $data['page'] = $q[1];
            }
            $data['total'] = isset($q[2]) && $q[2] > 0 ? $q[2] : 0;
        }
        return $data;
    }

    function getCart($l, $_LNG, $data = '') {
        if (isset($_SESSION['PNSDOTVN_CART']) && !empty($_SESSION['PNSDOTVN_CART']) && is_array($_SESSION['PNSDOTVN_CART']) && ($t = count($_SESSION['PNSDOTVN_CART'])) > 0) {
            $a             = @$_SESSION['PNSDOTVN_CART'];
            $data['item']  = $t;
            $data['total'] = 0;
            foreach ($a as $b => $c) {
                $d = explode('|', $b);
                $q = $this->Query('SELECT t1.id, t1.wholesale, t1.code, t1.picture, t1.price, t1.sale_off, t1.list_price, t1.info, t2.name, t2.rewrite, t2.metatitle AS title FROM ' . prefixTable . 'product t1 INNER JOIN ' . prefixTable . 'product_desc t2 ON t2.id = t1.id WHERE t1.status = 1 AND t1.id = ' . $d[0] . ' AND t2.lang = "' . $l . '" LIMIT 1');
                if ($this->totalRows($q) == 1) {
                    $r = $this->nextObject($q);
                    $this->freeResult($q);
                    $p                 = explode(';', $r->picture);
                    $priceByAttr = $this->getPrice($d[0], $d[2]);
                    if($priceByAttr) {
                        $price = $priceByAttr['price'];
                    } else {
                        $price = $r->price;
                        if($r->wholesale) {
                            $wholesale = unserialize($r->wholesale);
                            $price = $this->getPriceByWholesale($wholesale, $c);
                        }
                    }

                    $r->ids            = $b;
                    $r->name           = stripslashes($r->name);
                    $r->href           = (MULTI_LANG ? DS . $_LNG : '') . DS . $_LNG->others->product->rewrite . DS . $r->rewrite . '-' . $r->id . EXT;
                    $r->src            = isset($p[0]) ? $p[0] : '';
                    $r->qty            = $c;
                    $r->price_txt      = $this->pricevnd($price, $_LNG->product->currency);
                    $r->list_price_txt = $this->pricevnd($r->list_price, $_LNG->product->currency);
                    $r->amount         = $price * $r->qty;
                    $r->amount_txt     = $this->pricevnd($r->amount, $_LNG->product->currency);
                    if($priceByAttr['attr'] == 'size') {
                        $g       = $this->getSizebyCode($d[2], $l);
                        $r->size .= isset($g->name) && !empty($g->name) ? stripslashes($g->name) : '';
                    } else {
                        $f = $this->getColorbyCode($d[2], $l);
                        $r->color .= isset($f->name) && !empty($f->name) ? stripslashes($f->name) : '';
                    }
                    $data['list'][] = $r;
                    $data['total']  += $r->amount;
                }
            }
            $data['total_txt'] = $this->pricevnd($data['total'], $_LNG->product->currency);
        }
        return $data;
    }

    function checkFilter($d, $data, $type = '', $f = FALSE) {
        switch ($type) {
            case 'BRAND' :
                $s = 'SELECT id FROM ' . prefixTable . 'brand WHERE status = 1 AND name = "' . $this->checkValues(str_replace('+', ' ', $data)) . '" LIMIT 1';
                break;
            case 'SUPPLIER' :
                $s = 'SELECT id FROM ' . prefixTable . 'supplier WHERE status = 1 AND name = "' . $this->checkValues(str_replace('+', ' ', $data)) . '" LIMIT 1';
                break;
        }
        $filter = $this->getFilter($d, 'UPPER');
        if (is_array($filter) && count($filter) > 0 && in_array($type, $filter)) {
            $s = 'SELECT t1.id FROM ' . prefixTable . 'product_option_data_desc t1 INNER JOIN ' . prefixTable . 'product_option_data t2 ON t2.id = t1.id INNER JOIN ' . prefixTable . 'product_option t3 ON t3.id = t2.oid WHERE t2.status = 1 AND t1.lang = "' . $d->code . '" AND t1.name = "' . $this->checkValues(str_replace('+', ' ', $data)) . '" AND t3.status = 1 AND t3.code = "' . strtolower($type) . '" LIMIT 1';
        }
        if (isset($s) && !empty($s)) {
            $q = $this->Query($s);
            if ($this->totalRows($q) == 1) {
                $this->freeResult($q);
                $f = TRUE;
            }
        }
        return $f;
    }

    function getFilter($d, $t = '', $data = '') {
        $q = $this->Query('SELECT t2.code FROM ' . prefixTable . 'product_option_desc t1 INNER JOIN ' . prefixTable . 'product_option t2 ON t1.id = t2.id WHERE t2.status = 1 AND t2.filter = 1 AND t1.lang = "' . $d->code . '" ORDER BY t2.ordering, t1.name');
        if ($this->totalRows($q)) {
            while ($r = $this->nextObject($q)) {
                $data[] = ($t == 'UPPER' ? strtoupper($r->code) : $r->code);
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function checkProductOption($id, $ids = '', $flg = FALSE) {
        $q = $this->Query('SELECT id, joption FROM dynw_product WHERE status = 1 AND joption LIKE "%opt' . $id . '_%"' . (!empty($ids) ? ' AND cid IN (' . $ids . ')' : '') . ' LIMIT 1');
        if ($this->totalRows($q) == 1) {
            $r = $this->nextObject($q);
            $j = unserialize($r->joption);
            foreach ($j as $i) {
                if ($i['oid'] == $id && !empty($i['id']) && $i['id'] > 0) {
                    $flg = TRUE;
                }
            }
            $this->freeResult($q);
        }
        return $flg;
    }

    function getProductOption($d, $data = '') {
        $f   = ($d->route->name == 'product' && $d->route->type == 'detail' ? TRUE : FALSE);
        $flg = 1;
        $ids = '';
        if ($d->route->name == 'category') {
            $flg = 0;
            $ida = $this->getChildCategory($d->route->id);
            $ids = (is_array($ida) && count($ida) > 0) ? implode(',', $ida) . ',' . $d->route->id : $d->route->id;
            $q   = $this->Query('SELECT count(t1.id) AS total FROM dynw_product t1 INNER JOIN dynw_category t2 ON t2.id = t1.cid WHERE t1.status = 1 AND t2.status = 1 AND t1.cid IN (' . $ids . ')');
            $r   = $this->nextObject($q);
            $this->freeResult($q);
            if ($r->total > 0) {
                $flg = 1;
            }
        }
        if ($flg) {
            $q = $this->Query('SELECT t1.name, t1.rewrite, t2.id, t2.code FROM ' . prefixTable . 'product_option_desc t1 INNER JOIN ' . prefixTable . 'product_option t2 ON t2.id = t1.id WHERE t2.status = 1 AND t1.lang = "' . $d->code . '"' . ($f ? ' AND t2.id = ' . $d->joid : ' AND t2.type = "select" AND t2.filter = 1') . ' ORDER BY t2.ordering, t1.name');
            if ($this->totalRows($q)) {
                while ($r = $this->nextObject($q)) {
                    if ($this->checkProductOption($r->id, $ids)) {
                        $data[] = [
                            'catalog' => $r,
                            'child'   => $this->getProductOptionData(($f ? $d->jid : $r->id), $d),
                        ];
                    }
                }
                $this->freeResult($q);
            }
        }
        return $data;
    }

    function getProductOptionData($id, $d, $data = '') {
        $f = ($d->route->name == 'product' && $d->route->type == 'detail' ? TRUE : FALSE);
        $q = $this->Query('SELECT t1.name, t1.rewrite, t2.id FROM ' . prefixTable . 'product_option_data_desc t1 INNER JOIN ' . prefixTable . 'product_option_data t2 ON t2.id = t1.id INNER JOIN ' . prefixTable . 'product_option t3 ON t3.id = t2.oid WHERE t2.status = 1 AND t1.lang = "' . $d->code . '" AND' . ($f ? ' t2.id = ' . $id : ' t2.oid = ' . $id) . ' ORDER BY t2.ordering, t1.name');
        if ($this->totalRows($q)) {
            while ($r = $this->nextObject($q)) {
                $data[] = $r;
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function getMember($d, $_LNG, $data = '') {
        switch ($d->route->name) {
            case 'member' :
            case 'checkout' :
                $id = $_SESSION['member']['id'];
                $q  = $this->Query('SELECT t1.*, t2.address, t2.ward_id, t2.ward_name, t2.district_id, t2.district_name, t2.city_id, t2.city_name, t2.country_id, t2.country_name FROM ' . prefixTable . 'customer t1 INNER JOIN ' . prefixTable . 'customer_address t2 ON t2.id = t1.address WHERE t1.status = 1 AND t1.approve = 1 AND t1.id = ' . $id . ' LIMIT 1');
                if ($this->totalRows($q) == 0) {
                    $this->deleteDynamic(prefixTable . 'customer', 'userid = ' . $id);
                    unset($_SESSION['member']);
                    if (isset($_COOKIE[COOKIE_NAME])) {
                        setcookie(COOKIE_NAME, '', time() - COOKIE_TIME);
                    }
                    header('Location: ' . ((MULTI_LANG ? DS . $d->code2 : '') . DS . 'dang-nhap' . EXT . ($d->route->name == 'checkout' && CHECKOUT_NOT_REG ? '?PROCEED_TO_CHECKOUT' : '')));
                    exit;
                }
                $data = $this->nextObject($q);
                $this->freeResult($q);
                break;
        }
        return $data;
    }

    function getLocation($l, $pid = 0, $id = 0, $data = '') {
        $a = ($id > 0 ? ' AND id = ' . $id : '');
        $b = ($pid > 0 ? ' AND pid = ' . $pid : '');
        switch ($l) {
            case 'city' :
                $c = ' AND pid = 0' . $a . ' AND lgroup = 1';
                break;
            case 'district' :
                $c = $b . $a . ' AND lgroup = 2';
                break;
            case 'ward' :
                $c = $b . $a . ' AND lgroup = 3';
                break;
        }
        $d = ($id > 0 ? ' ORDER BY ordering' : '');
        $e = ($id > 0 ? ' LIMIT 1' : '');
        $q = $this->Query('SELECT id, name FROM ' . prefixTable . 'location WHERE status = 1' . $c . $d . $e);
        if ($this->totalRows($q)) {
            while ($r = $this->nextObject($q)) {
                $data[] = $r;
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function checkProductStatus($id, $status = FALSE) {
        $q = $this->Query('SELECT id FROM ' . prefixTable . 'product WHERE status = 1 AND id = ' . $id . ' LIMIT 1');
        if ($this->totalRows($q)) {
            $this->freeResult($q);
            $status = TRUE;
        }
        return $status;
    }

    function getWishlistProductMember($id, $wishlist = '') {
        $q = $this->Query('SELECT wishlist FROM ' . prefixTable . 'customer WHERE status = 1 AND approve = 1 AND id = ' . $id . ' LIMIT 1');
        if ($this->totalRows($q)) {
            $r = $this->nextObject($q);
            $this->freeResult($q);
            $wishlist = unserialize($r->wishlist);
        }
        return $wishlist;
    }

    function getWishlist($d, $_LNG, $out = '') {
        switch ($d->route->position) {
            case 'wishlist' :
                $q = $this->pagingQueryJoin(prefixTable . 'product t1', 't1.status = 1 AND t2.lang = "' . $d->code . '" AND t3.status = 1 AND t1.id IN (' . $d->ids . ')', 't1.created desc', 't1.*, t2.name, t2.rewrite, t2.introtext, t2.metatitle', '', [
                    prefixTable . 'product_desc t2' => 't2.id = t1.id',
                    prefixTable . 'category t3'     => 't3.id = t1.cid',
                ], 'INNER JOIN', $d->url, PAGE, WISHLIST_PRODUCT_ITEM, PAGE_NUMBER, 'Toolbar');
                if (isset($q[0]) && $q[2] > 0) {
                    $out['total'] = $q[2];
                    while ($r = $this->nextObject($q[0])) {
                        $pic           = explode(';', $r->picture);
                        $r->pic        = (isset($pic[0]) ? $pic[0] : '');
                        $r->name       = stripslashes($r->name);
                        $r->title      = str_replace('"', '', $r->name);
                        $r->href       = (MULTI_LANG ? DS . $d->code2 : '') . DS . $_LNG->product->rewrite . DS . $r->rewrite . '-' . $r->id . EXT;
                        $out['list'][] = $r;
                    }
                    if (isset($q[1]) && !empty($q[1])) {
                        $out['page'] = $q[1];
                    }
                }
                break;
            case 'member' :
            case 'product' :
                $q = $this->Query('SELECT wishlist FROM ' . prefixTable . 'customer WHERE status = 1 AND approve = 1 AND id = ' . $_SESSION['member']['id'] . ' LIMIT 1');
                if ($this->totalRows($q)) {
                    $r = $this->nextObject($q);
                    $this->freeResult($q);
                    $out = unserialize($r->wishlist);
                }
                break;
        }
        return $out;
    }

    function buildWishlistControl($id, $wishlist, $action, $csid) {
        switch ($action) {
            case 'add':
                if (empty($wishlist)) {
                    $wishlist = [];
                }
                #array_push($wishlist, $id);
                $wishlist[$id] = time();
                $this->Query('UPDATE ' . prefixTable . "customer SET wishlist = '" . serialize($wishlist) . "' WHERE id = " . $csid . ' LIMIT 1');
                break;
            case 'remove':
                unset($wishlist[$id]);
                $this->Query('UPDATE ' . prefixTable . "customer SET wishlist = '" . serialize($wishlist) . "' WHERE id = " . $csid . ' LIMIT 1');
                break;
        }
    }

    function getOrder($d, $out = '') {
        $csid = $_SESSION['member']['id'];
        switch ($d->route->position) {
            case 'list' :
                $q = $this->pagingQueryJoin(prefixTable . 'order t1', 't1.csid = ' . $csid, 't1.ordered', 't1.*', '', [prefixTable . 'order_status t2' => 't2.id = t1.status'], 'INNER JOIN', $d->url, PAGE, ORDER_LIST_ITEM, PAGE_NUMBER, MODE_PAGING);
                if (isset($q[0]) && $q[2] > 0) {
                    $out['total'] = $q[2];
                    while ($r = $this->nextObject($q[0])) {
                        $out['list'][] = $r;
                    }
                    $this->freeResult($q[0]);
                    if (isset($q[1]) && !empty($q[1])) {
                        $out['page'] = $q[1];
                    }
                }
                break;
            case 'detail' :
                $q = $this->getDynamic(prefixTable . 'order', 'csid = ' . $csid . ' AND order_code = "' . $d->oid . '#"', 'id LIMIT 1');
                if ($this->totalRows($q) == 1) {
                    $r            = $this->nextObject($q);
                    $out['order'] = $r;
                    $this->freeResult($q);
                    $q2 = $this->getDynamic(prefixTable . 'order_detail', 'order_id = "' . $r->id . '"', '');
                    if ($this->totalRows($q2) > 0) {
                        while ($r2 = $this->nextObject($q2)) {
                            $out['list'][] = $r2;
                        }
                        $this->freeResult($q2);
                    }
                }
                break;
        }
        return $out;
    }

    function getOrderStatus($id, $out = '') {
        $q = $this->Query('SELECT name FROM dynw_order_status WHERE id = "' . $id . '" LIMIT 1');
        if ($this->totalRows($q)) {
            $r   = $this->nextObject($q);
            $out = $r->name;
            $this->freeResult($q);
        }
        return $out;
    }

    function buildOrderStatusMemo($in, $out = '') {
        if (!empty($in) && is_array($in) && count($in) > 0) {
            $tmp = array_reverse($in);
            foreach ($tmp as $item) {
                $q = $this->Query('SELECT description FROM dynw_order_status WHERE id = "' . $item['status'] . '" LIMIT 1');
                if ($this->totalRows($q)) {
                    $r     = $this->nextObject($q);
                    $out[] = [
                        'date' => date('d-m-Y H:i', $item['date']),
                        'desc' => $r->description,
                    ];
                    $this->freeResult($q);
                }
            }
        }
        return $out;
    }

    /**
     * @param $color
     * @param $_LNG
     * @param string $lang
     * @param string $html
     *
     * @return string
     */
    function buildProductDetailColorList($color, $_LNG, $lang = 'vi-VN', $html = '') {
        if (!empty($color)) {
            $ida = array_keys($color);
            if (!empty($ida) && count($ida) > 0) {
                $ids = implode(',', $ida);
                $rst = $this->getDynamicJoin(prefixTable . 'color_desc', prefixTable . 'color', [
                    'code'    => 'code',
                    'picture' => 'picture',
                ], 'inner join', 't2.status = 1 and t2.id IN (' . $ids . ') and t1.lang = "' . $lang . '"', '', 't2.id = t1.id');
                if (($t = $this->totalRows($rst)) > 0) {
                    $html .= '
                        <div id="product-color" class="label">
                            <div class="tooltip-error" onclick="jQuery(this).hide();">
                            <p>Vui lòng chọn màu sắc</p>
                        </div>
                        <span class="fl">Màu sắc:</span>
                        <ul class="color product-attribute">';
                    while ($row = $this->nextObject($rst)) {
                        $name = stripslashes($row->name);
                        $html .= '
                            <li id="' . $row->code . '" class="selectOption' . ($t == 1 ? ' selected' : '') . '" title="' . $name . '">
                                <img alt="' . $name . '" src="/thumb/32x32/1:1' . $row->picture . '" height="32" width="32" title="' . $name . '" />
                                <input type="hidden" name="color" data-price="' . $this->pricevnd($color[$row->id]['price']) . '" value="' . $row->code . '" />
                            </li>';
                    }
                    $html .= '</ul></div>';
                }
            }
        }
        return $html;
    }

    /**
     * @param $productZise
     * @param string $lang
     * @param string $html
     *
     * @return string
     */
    function buildProductDetailZize($productZise, $lang = 'vi-VN', $html = '') {
        if (!empty($productZise)) {
            $ida = array_keys($productZise);
            if (!empty($ida) && count($ida) > 0) {
                $ids = implode(',', $ida);
                $rst = $this->getDynamicJoin(prefixTable . 'psize_desc', prefixTable . 'product_size', [
                    'code'    => 'code',
                    'picture' => 'picture',
                ], 'inner join', 't2.status = 1 and t2.id IN (' . $ids . ') and t1.lang = "' . $lang . '"', '', 't1.id = t2.id');
                if (($t = $this->totalRows($rst)) > 0) {
                    $html .= '
                    <div id="product-size" class="label" >
                        <div class="tooltip-error hidden">
                        <p>Vui lòng chọn kích thước</p></div>
                    <span class="fl" style="line-height: 35px;">Kích thước:</span>
                    <ul class="size product-attribute">';
                    $i    = 0;
                    while ($row = $this->nextObject($rst)) {
                        // $selected = $i == 0 ? 'selected' : '';
                        $name     = stripslashes($row->name);
                        $selected = '';
                        $html     .= '
                            <li class="selectOption ' . $selected . '" title="' . $name . '">
                                <img alt="' . $name . '" height="35" width="35" title="' . $name . '" />
                                <input type="hidden" name="size" class="size" value="' . $row->code . '" data-price="' . $this->pricevnd($productZise[$row->id]['price']) . '"/>
                            </li>';
                        $i++;
                    }
                    $html .= '</ul></div>';
                }

            }
        }
        return $html;
    }

    function buildRecentlyViewedProducts($d) {
        if (!isset($_SESSION['PNSDOTVN_VIEWED_PRODUCTS'])) {
            $_SESSION['PNSDOTVN_VIEWED_PRODUCTS'] = [];
        }
        $v = &$_SESSION['PNSDOTVN_VIEWED_PRODUCTS'];
        $p = array_keys($v);
        if (!in_array($d->id, $p)) {
            if (count($v) == VIEWED_PRODUCT_ITEM) {
                $i = 1;
                foreach ($v as $a => $b) {
                    if ($i == 1) {
                        unset ($v[$a]);
                    }
                    $i++;
                }
            }
            $v[$d->id] = [
                'id'         => $d->id,
                'name'       => $d->name,
                'title'      => $d->title,
                'rewrite'    => $d->rewrite,
                'code'       => $d->code,
                'href'       => $d->href,
                'list_price' => $d->list_price,
                'price'      => $d->price,
                'sales'      => $d->sale_off,
                'src'        => $d->src,
                'outofstock' => $d->outofstock,
            ];
        }
        else {
            unset($v[$d->id]);
            if (count($v) == VIEWED_PRODUCT_ITEM) {
                $i = 1;
                foreach ($v as $a => $b) {
                    if ($i == 1) {
                        unset ($v[$a]);
                    }
                    $i++;
                }
            }
            $v[$d->id] = [
                'id'         => $d->id,
                'name'       => $d->name,
                'title'      => $d->title,
                'rewrite'    => $d->rewrite,
                'code'       => $d->code,
                'href'       => $d->href,
                'list_price' => $d->list_price,
                'price'      => $d->price,
                'sales'      => $d->sale_off,
                'src'        => $d->src,
                'outofstock' => $d->outofstock,
            ];
        }
    }

    function getProductInBrand($d, $data = '') {
        $q = $this->Query('SELECT t1.id, t1.picture AS src, t1.list_price, t1.sale_off, t1.price, t1.lname, t1.lcolor, t1.outofstock , t2.name, t2.rewrite, t2.metatitle AS title FROM dynw_product t1 INNER JOIN dynw_product_desc t2 ON t2.id = t1.id INNER JOIN dynw_category t3 ON t3.id = t1.cid INNER JOIN dynw_brand t4 ON t4.id = t1.brand WHERE t1.status = 1 AND t3.status = 1 AND t4.status = 1 AND t3.id = ' . $d->catalog . ' AND t4.id = ' . $d->brand . ' AND t1.id != ' . $d->product . ' ORDER BY t1.ordering, t1.created DESC LIMIT 0,12');
        if (($data['total'] = $this->totalRows($q)) > 0) {
            while ($r = $this->nextObject($q)) {
                $r->name        = stripslashes($r->name);
                $r->href        = ((MULTI_LANG ? DS . $d->code2 : '') . DS . 'san-pham' . DS . $r->rewrite . '-' . $r->id . EXT);
                $r->picture     = $r->src;
                $p              = explode(';', $r->src);
                $r->src         = isset($p[0]) ? $p[0] : $r->src;
                $data['list'][] = $r;
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function buildProductComment($p, $html = '') {
        #$this->printr($p);
        $html  .= '<div class="cm-title"><img alt="comment-icon" align="absmiddle" src="' . IMAGES_PATH . 'communication-2.png" class="Communication" width="32" height="32" title="Nhận xét của khách hàng" />Nhận xét của khách hàng về ' . ($p->ctype == 'PRODUCT' ? 'sản phẩm' : ($p->ctype == 'ARTICLE' ? 'tin' : '')) . ' <strong>' . $p->name . '</strong>: </div>';
        $html  .= !$p->loggedin ? '<div class="cm-info">(Bạn phải là thành viên mới có quyền viết bình luận. <a href="' . DS . 'dang-nhap' . EXT . '?WRITE_A_COMMENT&PAGE=' . $p->href . '" title="Đăng nhập thành viên">Đăng nhập</a> hoặc <a href="' . DS . 'dang-ky' . EXT . '" title="Đăng ký thành viên">Đăng ký</a>)</div>' : '';
        $html  .= '<div class="user-comment">' . $this->buildFormComment($p) . '</div>';
        $ctype = [
            'PRODUCT' => 1,
            'ARTICLE' => 2,
        ];
        $rst   = $this->getDynamicJoin(prefixTable . 'comment', prefixTable . 'customer', [], 'INNER JOIN', 't1.approve = 1 AND t1.item = ' . $p->id . ' AND t2.status = 1 AND t1.type = ' . $ctype[$p->ctype], '', 't2.id = t1.member');
        if (($t = $this->totalRows($rst)) > 0) {
            $html .= '<div class="cm-count">Có ' . $t . ' nội dung bình luận:</div>';
            #$html .= '<div id="loadComment" class="content">' . $this->buildCommentList($p, $p->id) . '</div>';
            $html .= '<div id="loadComment" class="content"></div>';
        }
        $this->freeResult($rst);
        return $html;
    }

    function buildFormComment($p, $html = '') {
        $html .= '<form id="frmComment" name="frmComment" method="post" action="/xu-ly-binh-luan" enctype="application/x-www-form-urlencoded">';
        $html .= '<p class="text">';
        $html .= '<label for="content">Nội dung: </label>';
        $html .= '<textarea id="content" name="content" class="{validate:{required:true, maxlength:500}}" rows="3" cols="69" placeholder="Nội dung bình luận"></textarea>';
        $html .= '</p>';
        $html .= '<!--<p class="captcha">Mã bảo vệ: <input type="text" id="captcha" name="captcha" class="character {validate:{required:true, remote: \'/captcha-process\'}}" value="" maxlength="6" autocomplete="off" placeholder="Mã bảo vệ" /> <span id="captchaimage"><a rel="nofollow" tabindex="-1" href="javascript:void(0);" id="refreshimg" title="Click lên đây để lấy hình khác"><img alt="captcha-code" align="absmiddle" src="/captcha-image?' . time() . '" border="0" title="Click lên đây để lấy hình khác" /></a></span></p>-->';
        $html .= '<p class="btnsubmit">';
        $html .= '<input type="hidden" id="item" name="item" value="' . $p->id . '" />';
        $html .= '<input type="hidden" id="type" name="type" value="new" />';
        $html .= '<input type="hidden" id="ctype" name="ctype" value="' . $p->ctype . '" />';
        $html .= '<input type="submit" id="btnComment" name="btnSend" value="Gửi bình luận" />';
        $html .= '<input type="button" id="btnClear" name="btnClear" value="Nhập lại" />';
        $html .= '</p>';
        $html .= '</form>';
        $html .= '<div class="showdata corner_5" id="showdata" onclick="jQuery(this).css(\'display\',\'none\')">';
        $html .= '<h4>Vui lòng thực hiện các vấn đề dưới đây:</h4>';
        $html .= '<ol>';
        $html .= '<li><label for="content" class="error">Nội dung bình luận bắt buộc nhập.</label></li>';
        $html .= '<li><label for="captcha" class="error">Mã bảo vệ không đúng.</label></li>';
        $html .= '</ol>';
        $html .= '<a rel="nofollow" class="close" href="javascript:void(0);" onclick="jQuery(\'div.showdata\').fadeOut(300).hide(1)"></a>';
        $html .= '</div>';
        $html .= '<script type="text/javascript" src="' . JS_PATH . 'jquery.form.js"></script>';
        $html .= '<script type="text/javascript" src="' . JS_PATH . 'jquery.validate.pack.js"></script>';
        $html .= '<script type="text/javascript" src="' . JS_PATH . 'jquery.metadata.js"></script>';
        $html .= '<script type="text/javascript" src="' . JS_PATH . 'JS.comment.js"></script>';
        return $html;
    }

    function buildCommentList($p, $id, $pid = 0, $follow = 0, & $html = '') {
        $sorting = $pid == 0 ? 'DESC' : '';
        $rst     = $this->getDynamicJoin(prefixTable . 'comment', prefixTable . 'customer', ['name' => 'm'], 'INNER JOIN', 't1.approve = 1 AND t1.item = ' . $id . ' AND t1.pid = ' . $pid . ' AND t1.follow = ' . $follow . ' AND t2.status = 1', 't1.added ' . $sorting, 't2.id = t1.member');
        if ($this->totalRows($rst) > 0) {
            while ($row = $this->nextObject($rst)) {
                $c    = $pid == 0 ? 'cmt' : 'rep';
                $id   = $row->id . '_' . $row->added;
                $html .= '<div class="' . $c . '-block" id="comment_' . $id . '">';
                $html .= '<div class="avatar"><img alt="avatar-icon" align="absmiddle" src="' . IMAGES_PATH . 'no_avatar.jpg" class="avatar" width="64" height="64" title="" onerror="$(this).css({display:\'none\'})" /></div>';
                $html .= '<div class="' . $c . '-content">';
                $html .= '<div class="user">' . stripslashes($row->m) . '</div>';
                $html .= '<div class="text"><span class="arrow"></span>' . stripslashes($row->content) . '</div>';
                $html .= '<div class="reply"><span class="date">(' . date('d/m/Y - h:m A', $row->added) . ')</span>';
                $html .= '<div class="fr">';
                $html .= '<span class="report"><a rel="nofollow" id="reportComment_' . $id . '" class="tt-tipsy reportComment" original-title="Báo cáo vi phạm" href="javascript:;" title="Báo cáo vi phạm"><img alt="report-icon" align="absmiddle" src="' . IMAGES_PATH . 'warning.png" height="16" width="16" title="Báo cáo vi phạm" onerror="$(this).css({display:\'none\'})" /></a></span> ';
                $html .= '<span class="like"><a rel="nofollow" id="likeComment_' . $id . '" class="tt-tipsy likeComment" original-title="Thích" href="javascript:;" title="Thích bình luận này"><img alt="like-icon" align="absmiddle" src="' . IMAGES_PATH . 'like.png" height="16" width="16" title="Thích" onerror="$(this).css({display:\'none\'})" /></a> <a rel="nofollow" class="countLike" href="javascript:;" original-title="Anh A<br/>Anh B<br/>Anh C<br/>Anh D<br/>Anh E<br/>Anh F<br/>Anh G<br/>Anh H<br/>">5</a></span> ';
                $html .= '<span class="btnReply"><a rel="nofollow" id="replyComment_' . $id . '" class="tt-tipsy showReplyBox" original-title="Trả lời" href="javascript:;" title="Trả lời bình luận này"><img alt="reply-icon" align="absmiddle" src="' . IMAGES_PATH . 'comments_reply.png" height="16" width="16" title="Trả lời" onerror="$(this).css({display:\'none\'})" /></a></span>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</div>';
                $this->buildCommentList($p, $row->item, $row->id, $row->member, $html);
            }
            $this->freeResult($rst);
        }
        return $html;
    }

    function buildCommentARR($id, $type, $pid = 0, $follow = 0, & $arr = '') {
        $sorting = $pid == 0 ? 'DESC' : '';
        $ctype   = [
            'PRODUCT' => 1,
            'ARTICLE' => 2,
        ];
        $rst     = $this->getDynamicJoin(prefixTable . 'comment', prefixTable . 'customer', ['name' => 'm'], 'INNER JOIN', 't1.approve = 1 AND t1.item = ' . $id . ' AND t1.pid = ' . $pid . ' AND t1.follow = ' . $follow . ' AND t2.status = 1 AND t1.type = ' . $ctype[$type], 't1.added ' . $sorting, 't2.id = t1.member');
        if ($this->totalRows($rst) > 0) {
            $f = $this->chkLoggedin();
            while ($row = $this->nextObject($rst)) {
                $liked = unserialize($row->liked);
                $arr[] = [
                    'id'      => $row->id . '_' . $row->pid . '_' . $row->item,
                    #'parent' => $row->pid,
                    'member'  => $row->m,
                    'date'    => date('d/m/Y - h:m A', $row->added),
                    'content' => stripslashes($row->content),
                    'type'    => $pid == 0 ? 'cmt' : 'rep',
                    'like'    => (isset($liked['total']) ? $liked['total'] : 0),
                    'liked'   => (isset($liked['member']) && !empty($liked['member']) && count($liked['member']) > 0 && $f == TRUE && in_array($_SESSION['member']['id'], $liked['member']) ? 1 : 0),
                ];
                $this->buildCommentARR($row->item, $type, $row->id, $row->member, $arr);
            }
            $this->freeResult($rst);
        }
        return $arr;
    }

    function pnsdotvn_comment($t, $a) {
        switch ($t) {
            case 'article':
                $f = TRUE;
                #$rst = $this->getDynamicJoin(prefixTable . 'product', prefixTable . 'category', array(), 'INNER JOIN', 't1.status = 1 AND t2.status = 1 AND t1.id = ' . $a['id'], '', 't2.id = t1.catid');
                $q = $this->Query('SELECT t1.id FROM ' . prefixTable . 'cms t1 INNER JOIN ' . prefixTable . 'menu t2 ON t2.id = t1.mid WHERE t1.status = 1 AND t2.status = 1 AND t1.id = ' . $a['id'] . ' LIMIT 1');
                if ($this->totalRows($q) == 0) {
                    $f = FALSE;
                }
                $this->freeResult($q);
                return $f;
                break;

            case 'product':
                $f = TRUE;
                #$rst = $this->getDynamicJoin(prefixTable . 'product', prefixTable . 'category', array(), 'INNER JOIN', 't1.status = 1 AND t2.status = 1 AND t1.id = ' . $a['id'], '', 't2.id = t1.catid');
                $q = $this->Query('SELECT t1.id FROM ' . prefixTable . 'product t1 INNER JOIN ' . prefixTable . 'category t2 ON t2.id = t1.cid WHERE t1.status = 1 AND t2.status = 1 AND t1.id = ' . $a['id'] . ' LIMIT 1');
                if ($this->totalRows($q) == 0) {
                    $f = FALSE;
                }
                $this->freeResult($q);
                return $f;
                break;

            case 'comment':
                $f = TRUE;
                #$rst = $this->getDynamic(prefixTable . 'comment', 'approve = 1 AND id = ' . $a['id'] . ' AND pid = ' . $a['pid'], '');
                $q = $this->Query('SELECT id FROM ' . prefixTable . 'comment WHERE approve = 1 AND id = ' . $a['id'] . ' AND pid = ' . $a['pid'] . ' LIMIT 1');
                if ($this->totalRows($q) == 0) {
                    $f = FALSE;
                }
                $this->freeResult($q);
                return $f;
                break;

            case 'g_comment':
                $row = '';
                $rst = $this->getDynamic(prefixTable . 'comment', 'approve = 1 AND id = ' . $a['id'], '');
                if ($this->totalRows($rst) == 1) {
                    $row = $this->nextObject($rst);
                }
                $this->freeResult($rst);
                return $row;
                break;

            case 'like':
            case 'unlike':
                $l   = 0;
                $rst = $this->getDynamic(prefixTable . 'comment', 'approve = 1 AND id = ' . $a['id'] . ' AND pid = ' . $a['pid'] . ' AND item = ' . $a['p'], '');
                if ($this->totalRows($rst) == 1) {
                    $row   = $this->nextObject($rst);
                    $liked = unserialize($row->liked);
                    $f     = TRUE;
                    if ($this->chkLoggedin()) {
                        $m = $_SESSION['member']['id'];
                        if (!isset($liked['member']) || empty($liked['member']) || count($liked['member']) == 0) {
                            $liked['member'] = [];
                        }
                        if ($t == 'like' && !in_array($m, $liked['member'])) {
                            $liked['member'][] = $m;
                        }
                        else {
                            $f = FALSE;
                        }
                        if ($t == 'unlike' && in_array($m, $liked['member'])) {
                            $k = array_search($m, $liked['member']);
                            unset($liked['member'][$k]);
                        }
                    }

                    if (isset($liked['total']) && $liked['total'] > 0) {
                        $liked['total'] = $t == 'like' && $f == TRUE ? $liked['total'] + 1 : ($t == 'like' && $f == FALSE ? $liked['total'] : $liked['total'] - 1);
                    }
                    else {
                        $liked['total'] = 1;
                    }
                    $l = $liked['total'];
                    $this->updateTable(prefixTable . 'comment', ['liked' => serialize($liked)], 'id = ' . $a['id'] . ' AND pid = ' . $a['pid'] . ' AND item = ' . $a['p']);
                }
                $this->freeResult($rst);
                return $l;
                break;
        }
    }

    function chkLoggedin($flg = FALSE) {
        if (isset($_SESSION['member']) && !empty($_SESSION['member']) && is_array($_SESSION['member']) && count($_SESSION['member']) > 0) {
            $csid = $_SESSION['member']['id'];
            $rst  = $this->getDynamic(prefixTable . 'customer', 'status = 1 AND id = ' . $csid, 'id LIMIT 1');
            if ($this->totalRows($rst) == 1) {
                $row = $this->nextObject($rst);
                $this->freeResult($rst);
                #if (hash('sha512', $row->password . $_SERVER['HTTP_USER_AGENT']) == $_SESSION['PNSDOTVN_MEMBER']['LOGIN'])
                $flg = TRUE;
            }
        }
        return $flg;
    }

    function secondsToTime($inputSeconds) {
        $secondsInAMinute = 60;
        $secondsInAnHour  = 60 * $secondsInAMinute;
        $secondsInADay    = 24 * $secondsInAnHour;

        // extract days
        $days = floor($inputSeconds / $secondsInADay);

        // extract hours
        $hourSeconds = $inputSeconds % $secondsInADay;
        $hours       = floor($hourSeconds / $secondsInAnHour);

        // extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes       = floor($minuteSeconds / $secondsInAMinute);

        // extract the remaining seconds
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds          = ceil($remainingSeconds);

        // return the final array
        $obj = [
            'd' => (int) $days,
            'h' => (int) $hours,
            'm' => (int) $minutes,
            's' => (int) $seconds,
        ];
        return $obj;
    }

    function countComment($d, $t = 0) {
        switch ($d->route->name) {
            case 'cms' :
                $q = $this->Query('SELECT count(t1.id) AS t FROM dynw_comment t1 INNER JOIN dynw_cms t2 ON t2.id = t1.item WHERE t1.approve = 1 AND t1.type = 2 AND t2.status = 1 AND t1.item = ' . $d->route->id);
                if ($this->totalRows($q)) {
                    $r = $this->nextObject($q);
                    $this->freeResult($q);
                    $t = $r->t;
                }
                break;
        }
        return $t;
    }

    function getRelatedNewsInProduct($d, $p, $data = '') {
        if (!empty($d->keywords)) {
            $a = explode(',', $d->keywords);
            if (is_array($a) && count($a) > 0) {
                foreach ($a as $b) {
                    $c[] = 't2.related_key LIKE "%' . $b . '%"';
                }
                $q = $this->Query('SELECT t1.id, t2.name, t2.metatitle AS title, t2.rewrite, t1.picture AS src, t1.mid FROM ' . prefixTable . 'cms t1 INNER JOIN ' . prefixTable . 'cms_desc t2 ON t2.id = t1.id INNER JOIN ' . prefixTable . 'menu t3 ON t3.id = t1.mid WHERE t1.status = 1 AND t3.status = 1 AND t3.display IN ("NEWS", "LIST") AND ' . implode(' OR ', $c) . ' ORDER BY t1.created DESC LIMIT 0,' . INPRODUCT_RELATED_NEWS_ITEM);
                if (($data['total'] = $this->totalRows($q)) > 0) {
                    while ($r = $this->nextObject($q)) {
                        $r->name        = stripslashes($r->name);
                        $r->alt         = $r->rewrite;
                        $m              = $this->getMenuAliasArray($d->code, $r->mid);
                        $r->href        = (MULTI_LANG ? DS . $d->code2 : '') . DS . implode(DS, $m) . DS . $r->rewrite . '-' . $r->id . EXT;
                        $data['list'][] = $r;
                    }
                    $this->freeResult($q);
                }
            }
        }
        return $data;
    }

    function getPoint($point = 0) {
        $q = $this->Query('SELECT point FROM ' . prefixTable . 'customer WHERE status = 1 AND approve = 1 AND id = ' . $_SESSION['member']['id'] . ' LIMIT 1');
        if ($this->totalRows($q) == 1) {
            $r = $this->nextObject($q);
            $this->freeResult($q);
            $point = $r->point;
        }
        return $point;
    }

    function getPoint2Money($p2m = 0) {
        $q = $this->Query('SELECT t1.content FROM ' . prefixTable . 'setting_desc t1 INNER JOIN ' . prefixTable . 'setting t2 ON t2.id = t1.id WHERE t2.status = 1 AND t2.type = "point2money" LIMIT 1');
        if ($this->totalRows($q) == 1) {
            $r = $this->nextObject($q);
            $this->freeResult($q);
            $p2m = $r->content;
        }
        return $p2m;
    }

    function getMoney2Point($m2p = 0) {
        $q = $this->Query('SELECT t1.content FROM ' . prefixTable . 'setting_desc t1 INNER JOIN ' . prefixTable . 'setting t2 ON t2.id = t1.id WHERE t2.status = 1 AND t2.type = "money2point" LIMIT 1');
        if ($this->totalRows($q) == 1) {
            $r = $this->nextObject($q);
            $this->freeResult($q);
            $m2p = $r->content;
        }
        return $m2p;
    }

    function getPointHistory($d, $data = '') {
        $q = $this->pagingQueryJoin(prefixTable . 'point_history t1', 't1.status = 1 AND t1.member = ' . $_SESSION['member']['id'], 't1.added DESC', 't1.*, t2.content, t2.code, t2.symbol, t3.name AS source, t4.order_code', '', [
            prefixTable . 'point_action t2'  => 't2.id = t1.action',
            prefixTable . 'point_related t3' => 't3.id = t1.related',
            prefixTable . 'order t4'         => 't4.id = t1.rid',
        ], 'INNER JOIN', $d->url, PAGE, POINT_LIST_ITEM, PAGE_NUMBER, MODE_PAGING);
        if (isset($q[0]) && !empty($q[0]) && $this->totalRows($q[0]) > 0) {
            while ($r = $this->nextObject($q[0])) {
                $r->added       = date('d/m/Y H:i', $r->added);
                $r->content     = $r->content . ' <a rel="nofollow" href="/quan-ly-don-hang.html?VIEW_ORDER_DETAIL&CODE=' . $r->order_code . '" title="' . $r->order_code . '">' . $r->source . ' ' . $r->order_code . '</a>';
                $data['list'][] = $r;
            }
            $this->freeResult($q[0]);
            if (isset($q[1]) && !empty($q[1])) {
                $data['page'] = $q[1];
            }
            $data['total'] = isset($q[2]) ? $q[2] : 0;
        }
        return $data;
    }

    function countProduct($id, $type, $total = 0) {
        switch ($type) {
            case 'catalog' :
                $t1 = $this->getChildCategory($id);
                $t2 = (is_array($t1) && count($t1) > 0) ? implode(',', $t1) . ',' . $id : $id;
                $q  = $this->Query('SELECT count(id) AS total FROM dynw_product WHERE status = 1 AND cid IN (' . $t2 . ')');
                if ($this->totalRows($q) == 1) {
                    $r = $this->nextObject($q);
                    $this->freeResult($q);
                    $total = $r->total;
                }
                break;
            case 'brand' :
                $q = $this->Query('SELECT count(id) AS total FROM dynw_product WHERE status = 1 AND brand = ' . $id);
                if ($this->totalRows($q) == 1) {
                    $r = $this->nextObject($q);
                    $this->freeResult($q);
                    $total = $r->total;
                }
                break;
            case 'supplier' :
                $q = $this->Query('SELECT count(id) AS total FROM dynw_product WHERE status = 1 AND supplier = ' . $id);
                if ($this->totalRows($q) == 1) {
                    $r = $this->nextObject($q);
                    $this->freeResult($q);
                    $total = $r->total;
                }
                break;
            case 'product_option' :
                $d  = $id;
                $t3 = '';
                if ($d->route->name == 'category') {
                    $t1 = $this->getChildCategory($d->route->id);
                    $t2 = (is_array($t1) && count($t1) > 0) ? implode(',', $t1) . ',' . $d->route->id : $d->route->id;
                    $t3 = ' AND cid IN (' . $t2 . ')';
                }
                $q = $this->Query('SELECT count(id) AS total FROM dynw_product WHERE status = 1 AND joption LIKE "%' . $d->joption . '%"' . $t3);
                if ($this->totalRows($q) == 1) {
                    $r = $this->nextObject($q);
                    $this->freeResult($q);
                    $total = $r->total;
                }
                break;
            case 'updated' :
                $d  = $id;
                $a  = '';
                $t1 = date("Y-m-d H:i:s");
                $t2 = date("Y-m-d H:i:s", strtotime($t1) - (60 * 60 * 24 * $d->day));
                if ($d->route->tmp == 'category') {
                    $a1 = $this->getChildCategory($d->route->id);
                    $a2 = (is_array($a1) && count($a1) > 0) ? implode(',', $a1) . ',' . $d->route->id : $d->route->id;
                    $a  = ' AND t1.cid IN (' . $a2 . ')';
                }
                $q = $this->Query('SELECT count(t1.id) AS total FROM dynw_product t1 INNER JOIN dynw_category t2 ON t2.id = t1.cid WHERE t1.status = 1 AND (t1.modified BETWEEN "' . $t2 . '" AND "' . $t1 . '")' . $a);
                if ($this->totalRows($q) == 1) {
                    $r = $this->nextObject($q);
                    $this->freeResult($q);
                    $total = $r->total;
                }
                break;
            case 'saleoff' :
                $d = $id;
                $a = '';
                if ($d->route->tmp == 'category') {
                    $a1 = $this->getChildCategory($d->route->id);
                    $a2 = (is_array($a1) && count($a1) > 0) ? implode(',', $a1) . ',' . $d->route->id : $d->route->id;
                    $a  = ' AND t1.cid IN (' . $a2 . ')';
                }
                $q = $this->Query('SELECT count(t1.id) AS total FROM dynw_product t1 INNER JOIN dynw_category t2 ON t2.id = t1.cid WHERE t1.status = 1 AND (t1.sale_off BETWEEN ' . $d->fsale . ' AND ' . $d->tsale . ')' . $a);
                if ($this->totalRows($q) == 1) {
                    $r = $this->nextObject($q);
                    $this->freeResult($q);
                    $total = $r->total;
                }
                break;
        }
        return $total;
    }

    function getProductAZ($d, $data = '') {
        if ($d->alphabet == 'D') {
            $a = ' AND (t2.name LIKE "' . $d->alphabet . '%" || t2.name LIKE "Đ%")';
        }
        else {
            $a = ' AND t2.name LIKE "' . $d->alphabet . '%"';
        }
        $q = $this->pagingQueryJoin(prefixTable . 'product t1', 't1.status = 1 AND t2.lang = "' . $d->code . '" AND t3.status = 1' . $a, 't1.created DESC', 't1.id, t2.name, t2.rewrite, t2.metatitle AS title', '', [
            prefixTable . 'product_desc t2' => 't2.id = t1.id',
            prefixTable . 'category t3'     => 't3.id = t1.cid',
        ], 'INNER JOIN', $d->url, PAGE, $d->PageSize, PAGE_NUMBER, MODE_PAGING);
        if (isset($q[0]) && !empty($q[0]) && $this->totalRows($q[0]) > 0) {
            while ($r = $this->nextObject($q[0])) {
                $r->name        = stripslashes($r->name);
                $r->href        = ((MULTI_LANG ? DS . $d->code2 : '') . DS . 'san-pham' . DS . $r->rewrite . '-' . $r->id . EXT);
                $data['list'][] = $r;
            }
            $this->freeResult($q[0]);
            if (isset($q[1]) && !empty($q[1])) {
                $data['page'] = $q[1];
            }
            $data['total'] = isset($q[2]) ? $q[2] : 0;
        }
        return $data;
    }

    function getPrevNextProduct($d, $data = '') {
        $q = $this->Query('SELECT t1.id, t2.name, t2.rewrite, t2.metatitle FROM dynw_product t1 INNER JOIN dynw_product_desc t2 ON t2.id = t1.id INNER JOIN dynw_category t3 ON t3.id = t1.cid WHERE t1.status = 1 AND t2.lang = "' . $d->code . '" AND t3.status = 1 AND t3.id = ' . $d->catalog . ' ORDER BY t1.id');
        if ($this->totalRows($q) > 0) {
            while ($r = $this->nextObject($q)) {
                $r->name              = stripslashes($r->name);
                $r->href              = ((MULTI_LANG ? DS . $d->code2 : '') . DS . 'san-pham' . DS . $r->rewrite . '-' . $r->id . EXT);
                $data['list'][$r->id] = $r;
                $data['nav'][]        = $r->id;
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function getAlsoBoughtProduct2($d, $data = '') {
        $q = $this->Query('SELECT t1.id, t1.price, t1.picture AS src, t2.name, t2.rewrite, t2.metatitle FROM dynw_product t1 INNER JOIN dynw_product_desc t2 ON t2.id = t1.id INNER JOIN dynw_category t3 ON t3.id = t1.cid WHERE t1.status = 1 AND t1.id != ' . $d->product . ' AND t2.lang = "' . $d->code . '" AND t3.status = 1 AND t3.id = ' . $d->catalog . ' ORDER BY rand() LIMIT 0,3');
        if ($this->totalRows($q) > 0) {
            while ($r = $this->nextObject($q)) {
                $r->name        = stripslashes($r->name);
                $r->href        = ((MULTI_LANG ? DS . $d->code2 : '') . DS . 'san-pham' . DS . $r->rewrite . '-' . $r->id . EXT);
                $data['list'][] = $r;
                $data['price']  += $r->price;
            }
            $this->freeResult($q);
        }
        return $data;
    }

    function getAlsoBoughtProduct($d, $data = '') {
        $ja = unserialize($d->jalsobuy);
        #$this->printr($ja);
        if (is_array($ja) && count($ja) > 0) {
            $ids = implode(',', $ja);
            $q   = $this->Query('SELECT t1.id, t1.price, t1.picture AS src, t2.name, t2.rewrite, t2.metatitle FROM dynw_product t1 INNER JOIN dynw_product_desc t2 ON t2.id = t1.id INNER JOIN dynw_category t3 ON t3.id = t1.cid WHERE t1.status = 1 AND t1.id != ' . $d->product . ' AND t1.id IN (' . $ids . ') AND t2.lang = "' . $d->code . '" AND t3.status = 1 ORDER BY id DESC');
            if ($this->totalRows($q) > 0) {
                $data['price'] = 0;
                while ($r = $this->nextObject($q)) {
                    $r->name        = stripslashes($r->name);
                    $r->href        = ((MULTI_LANG ? DS . $d->code2 : '') . DS . 'san-pham' . DS . $r->rewrite . '-' . $r->id . EXT);
                    $data['list'][] = $r;
                    $data['price']  += $r->price;
                }
                $this->freeResult($q);
            }
        }
        return $data;
    }

    function getTextlink($p, $html = '') {
        $q = $this->Query('SELECT content FROM dynw_textlink WHERE position = "' . $p . '" AND status = 1 LIMIT 1');
        if ($this->totalRows($q) == 1) {
            $r = $this->nextObject($q);
            $this->freeResult($q);
            $html = stripslashes($r->content);
        }
        return $html;
    }

    /**
     * @param $id
     * @param null $value
     *
     * @return array
     */
    function getPrice($id, $value = NULL)
    {
        $rst = $this->getDynamic(prefixTable . 'product', 'id = ' . $id, '');
        if($this->totalRows($rst) == 1)
        {
            $row = $this->nextObject($rst);
            $info = unserialize($row->info);
            if(!empty($info['size'])) {
                foreach ($info['size'] as $item) {
                    if($item['code'] == $value) {
                        return [
                            'attr' => 'size',
                            'price' => $item['price']
                        ];
                    }
                }
            }
            if(!empty($info['color'])) {
                foreach ($info['color'] as $item) {
                    if($item['code'] == $value) {
                        return [
                            'attr' => 'color',
                            'price' => $item['price']
                        ];
                    }
                }
            }
        }

    }
    /**
     * @param $wholesale
     * @param $quantity
     *
     * @return int
     */
    function getPriceByWholesale($wholesale, $quantity) {
        $price = 0;
        foreach ($wholesale as $wsale) {
            if($wsale['quantity_from'] == $wsale['quantity_to'] && $quantity >= $wsale['quantity_from']) {
                $price = $wsale['wholesale_price'];
                break;
            }
            if($quantity >= $wsale['quantity_from'] && $quantity <= $wsale['quantity_to']) {
                $price = $wsale['wholesale_price'];
                break;
            }
        }
        return $price;
    }

    /**
     * @param $id
     * @param string $lang
     * @param string $data
     *
     * @return array|string
     */
    function getProductInfo($id, $lang = 'vi-VN', $data = '') {
        $rst = $this->getDynamicJoin(prefixTable . 'product', prefixTable . 'product_desc', [
            'name'    => 'pname',
            'rewrite' => 'rewrite',
        ], 'INNER JOIN', 't1.status = 1 AND t1.id = ' . $id . ' AND t2.lang = "' . $lang . '"', '', 't2.id = t1.id');
        if ($this->totalRows($rst) == 1) {
            $row = $this->nextObject($rst);
            if ($row->outofstock == 0) {
                $picture = explode(';', $row->picture);
                $jdata   = unserialize($row->info);
                $data    = [
                    'id'        => $row->id,
                    'name'      => $row->pname,
                    'rewrite'   => $row->rewrite,
                    'picture'   => $picture[0],
                    'price'     => $row->price,
                    'color'     => (isset($jdata['color']) ? $jdata['color'] : ''),
                    'wholesale' => $row->wholesale,
                ];
            }
            else {
                $data = ['outofstock' => 1];
            }
            $this->freeResult($rst);
        }
        return $data;
    }
}

$dbf = new BusinessLogic();