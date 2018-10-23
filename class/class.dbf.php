<?php
require_once("config.database.php");
require_once("class.html.php");

class DBFunction extends HTML {

    protected $connect = NULL;

    protected $_dbh = NULL;

    /**
     * DBFunction constructor.
     */
    function __construct() {
        if (empty($this->connect)) {
            $this->connect = mysql_connect(HOSTADDRESS, DBACCOUNT, DBPASSWORD);
            mysql_select_db(DBNAME, $this->connect);
            $dsn = "mysql:host=" . HOSTADDRESS . ";dbname=" . DBNAME .';charset=utf8';
            // create a PDO connection with the configuration data
            $this->_dbh = new PDO($dsn, DBACCOUNT, DBPASSWORD);
        }
    }

    function getConnect() {
        return $this->connect;
    }

    function __destruct() {
        $this->connect = NULL;
    }

    function dbConnect() {
        try {
            if (empty($this->connect)) {
                $this->connect = mysql_connect(HOSTADDRESS, DBACCOUNT, DBPASSWORD);
                return mysql_select_db(DBNAME, $this->connect);
            }
        } catch (Exception $ex) {

            return FALSE;
        }
    }

    /*Dong ket noi
    -----------------------------------------------------------------*/
    function dbClose() {
        if ($this->connect != NULL) {
            mysql_close($this->connect);
            $this->connect = NULL;
        }
    }

    function escapeStr($str) {
        return mysql_real_escape_string($str, $this->connect);
    }

    /*function removeSQLInjection($str) {
        return mysql_real_escape_string($str,$this->connect);
    }*/

    /*Lay du lieu dong
    -----------------------------------------------------------------*/
    function getDynamic($tbName, $condition, $orderby, $debug = FALSE) {
        try {
            $this->dbConnect();
            $orderby   = (!empty($orderby)) ? ' ORDER BY ' . $orderby : '';
            $condition = (!empty($condition)) ? ' WHERE ' . $condition : '';
            $sql       = 'SELECT * FROM ' . $tbName . '  ' . $condition . '  ' . $orderby;
            if ($debug == TRUE) {
                echo $sql;
            }
            $rst = $this->doSQL($sql);
            return $rst;
        } catch (Exception $ex) {

            return NULL;
        }
    }

    /**
     * @param $table
     * @param $conditions
     * @param array $orderBys
     * @param null $limit
     * @param null $offset
     *
     * @return bool
     * @throws \Exception
     */
    function selectData($table, $conditions, $orderBys = [], $limit = NULL, $offset = NULL) {
        try {
            $where = '';
            if ($conditions) {
                $where = ' WHERE ';
                $condition = [];
                foreach ($conditions as $con) {
                    if(is_string($con['value'])) {
                        $condition[]= $con['field'] . $con['type'] . "'" . $con['value'] . "'" . ' ';
                    } else {
                        $condition[]= $con['field'] . $con['type'] . $con['value'] . ' ';
                    }
                }
                $where .= implode(' AND ', $condition);
            }

            $order = '';
            if ($orderBys) {
                $order = ' ORDER BY ';
                $orderBy = [];
                foreach ($orderBys as $con) {
                    $orderBy[] = $con['field'] . ' ' . $con['type'];
                }
                $order .= implode(',', $orderBy);
            }
            $range = '';
            if (!is_null($limit) && !is_null($offset)) {
                $range = ' limit ' . $limit . ' offset ' . $offset . ' ';
            }

            $sql = 'SELECT * FROM ' . $table . ' ' . $where . ' ' . $order . ' ' .$range;
            $stmt = $this->_dbh->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            throw $e;
        }
    }

    //Join 2 bang
    function getDynamicJoin($tbName1, $tbName2, $arrayNewName = NULL, $typejoin = "inner join", $condition, $orderby, $on, $debug = FALSE) {
        try {

            $this->dbConnect();
            $orderby   = ($orderby != "") ? " ORDER BY " . $orderby : "";
            $on        = ($on != "") ? " ON " . $on : "";
            $condition = ($condition != "") ? " WHERE " . $condition : "";

            $str = "";
            foreach ($arrayNewName as $key => $value) {
                $str .= " t2.$key as $value ,";
            }
            $str = substr($str, 0, (strlen($str) - 1));
            if ($str != "") {
                $str = ", " . $str;
            }

            if ($condition != "") {
                $sql = "SELECT t1.* $str FROM " . $tbName1 . " t1 " . $typejoin . "  " . $tbName2 . " t2 " . $on . " " . $condition . "  " . $orderby;
                if ($debug == TRUE) {
                    echo $sql;
                }
            }
            #echo $sql;
            $rst = mysql_query($sql);

            return $rst;

        } catch (Exception $ex) {


            return;
        }
    }

    /*Phuong thuc insert du lieu vao bang
    -----------------------------------------------------------------*/
    function insertTable($tbName, $arrayValue) {

        try {
            $con = $this->dbConnect();
            $str = "";
            $k   = "";
            foreach ($arrayValue as $key => $value) {
                $str .= $value . ",;+;,";
                $k   .= $key . ",";
            }
            $str = substr($str, 0, (strlen($str) - 5));
            $str = str_replace(",;+;,", "','", $str);
            $k   = substr($k, 0, (strlen($k) - 1));
            $sql = "INSERT INTO " . $tbName . "(" . $k . ") VALUES('" . $str . "')";

            $affect = mysql_query($sql);
            $affect = mysql_insert_id();

            return $affect;

        } catch (Exception $ex) {


            return;
        }
    }

    /**
     * @param $tbName
     * @param $fields
     * @param $values
     *
     * @return string
     * @throws \Exception
     */
    function insertData($tbName, $fields, $values) {
        try {
            $varFields = array_map(function ($val) {
                return ':' . $val;
            }, $fields);
            $values = array_combine($varFields, $values);
            $sql = "INSERT INTO {$tbName} (" . implode(',', $fields) . ") 
                          VALUES (" . implode(',', $varFields) . ")";
            $stmt = $this->_dbh->prepare($sql);
            $stmt->execute($values);
            return $this->_dbh->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        }
    }

    function updateData($tbName, $conditions = [], $values) {
        try {

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $tbName
     * @param $field
     * @param array $values
     *
     * @return bool
     * @throws \Exception
     */
    function deleteData($tbName, $field, $values = []) {
        try {
            $sql = "DELETE FROM {$tbName} WHERE {$field} ";
            if (is_array($values) && count($values) > 1) {
                $sql  .= 'IN (' . implode(',', $values) . ')';
                $stmt = $this->_dbh->prepare($sql);
                return $stmt->execute();
            }
            if (!is_array($values) && !empty($values)) {
                $sql  .= '=:' . $field;
                $stmt = $this->_dbh->prepare($sql);
                return $stmt->execute([':' . $field => $values]);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
    /*Phuong thuc cap nhat du lieu vao bang
    -----------------------------------------------------------------*/
    function updateTable($tbName, $arrayValue, $condition, $debug = FALSE) {

        try {
            $str       = '';
            $condition = ($condition != "") ? " WHERE " . $condition : "";
            $this->dbConnect();
            //
            foreach ($arrayValue as $key => $value) {
                if (strpos("$arrayValue[$key]", "$key") === FALSE) {
                    $str .= "$key='" . $arrayValue[$key] . "',";
                }
                else {
                    $str .= "$key='" . $arrayValue[$key] . "',";
                }
            }
            //
            $str = substr($str, 0, (strlen($str) - 1));
            $sql = "UPDATE " . $tbName . " SET " . $str . " " . $condition;
            if ($debug) {
                echo $sql;
            }
            $affect = mysql_query($sql);

            return $affect;
        } catch (Exception $ex) {


            return;
        }
    }

    /*Xoa du lieu dong
    -----------------------------------------------------------------*/
    function deleteDynamic($tbName, $condition) {
        try {
            $this->dbConnect();
            $condition = ($condition != "") ? " WHERE " . $condition : "";
            $sql       = "DELETE FROM " . $tbName . " " . $condition;
            $affect    = mysql_query($sql);

            return $affect;

        } catch (Exception $ex) {


            return 0;
        }
    }

    /*Tra ve tong so dong dua tren tap ket qua tra ve
    -----------------------------------------------------------------*/
    function totalRows($result) {
        return mysql_num_rows($result);
    }

    //chuyen thanh mang rst
    function arrayRST($rst, $colid, $col_parent) {
        try {
            $mang = NULL;
            if ($rst) {
                while ($row = $this->nextData($rst)) {
                    $mang[$row[$colid]] = $row[$col_parent];
                }
            }
            return $mang;
        } catch (Exception $ex) {


            return;
        }
    }

    /*Cho biet tong so field trong bang
    -----------------------------------------------------------------*/
    function totalFields($result) {
        try {
            return mysql_num_fields($result);

        } catch (Exception $ex) {


            return;
        }
    }

    /*duyet du lieu thong qua phuong thuc assoc
    -----------------------------------------------------------------*/
    function nextAssoc($result) {
        try {
            return mysql_fetch_assoc($result);
        } catch (Exception $ex) {


            return;
        }
    }

    /*duyet du lieu thong qua phuoc thuc fetch Array. Co the truy xuat thong
    ten field hay chi so index
    -----------------------------------------------------------------*/
    function nextData($result) {
        return mysql_fetch_array($result);
    }

    /*duyet du lieu thong qua phuoc thuc fetch Row. Giong assoc
    -----------------------------------------------------------------*/
    function nextRow($result) {
        return mysql_fetch_row($result);
    }

    /*duyet du lieu thong qua phuoc thuc fecth Object.
    -----------------------------------------------------------------*/
    function nextObject($result) {
        return mysql_fetch_object($result);
    }

    /*
    -----------------------------------------------------------------*/
    function queryEncodeUTF8() {
        mysql_query("SET CHARACTER SET 'utf8'");
    }

    /*Thuc hien truy van la select
    -----------------------------------------------------------------*/
    function doSQL($sql) {
        try {
            $this->dbConnect();
            $rst = mysql_query($sql, $this->connect);
            return $rst;
        } catch (Exception $ex) {

            return;
        }
    }

    /**
     * @param $sql
     * @param bool $all
     *
     * @return array|mixed
     */
    public function executeSql($sql, $all = FALSE) {
        try {
            $stmt = $this->_dbh->prepare($sql);
            $stmt->execute();
            if ($all) {
                $result = $stmt->fetchAll();
            }
            else {
                $result = $stmt->fetch();
            }
            return $result;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /*Thuc hien truy van la insert,delete,update
    -----------------------------------------------------------------*/
    function doNoSQL($sql) {
        try {
            $this->dbConnect();
            $affect = mysql_query($sql, $this->connect);
            return $affect;
        } catch (Exception $ex) {

            return;
        }

    }

    /*Giai phong bo nho
    -----------------------------------------------------------------*/
    function freeResult($result) {
        try {
            if (PHP_VERSION_ID < 50500) // mysql_free_result() function is deprecated as of PHP 5.5.0
            {
                mysql_free_result($result);
            }
        } catch (Exception $ex) {
            return;
        }
    }

    /*reset cau truc bang, va xoa du lieu bang
    -----------------------------------------------------------------*/
    function truncateTable($table) {
        try {
            $this->dbConnect();
            $sql    = "TRUNCATE TABLE " . $table;
            $affect = $this->doSQL($sql);

            return $affect;
        } catch (Exception $ex) {


            return;
        }
    }

    /* Return array from resultset
    -----------------------------------------------------------------*/
    function getArray($tbName, $condition, $orderby, $mode = "", &$array_col = NULL) {
        try {
            $str       = '';
            $rst       = $this->getDynamic($tbName, $condition, $orderby);
            $array_row = [];
            //if(is_array($array_col))$array_col = $this->getColumns($rst);
            switch ($mode) {
                case "stdObject":
                    while ($row = $this->nextObject($rst)) {
                        $array_row[] = $row;
                    }
                    break;
                case "Assoc":
                    while ($row = $this->nextAssoc($rst)) {
                        $array_row[] = $row;
                    }
                    break;
                case "Row":
                    while ($row = $this->nextRow($rst)) {
                        $array_row[] = $row;
                    }
                    break;
                case "Array":
                    while ($row = $this->nextData($rst)) {
                        $array_row[] = $row;
                    }
                    break;
                default:
                    while ($row = $this->nextData($rst)) {
                        $array_row[] = $row;
                    }
                    break;
            }
            unset($rst, $str, $row);
            return $array_row;
        } catch (Exception $ex) {
            return NULL;
        }
    }

    function SelectWithRowArray($array, $match = '', $idName, $datatextfield, $datavaluefield, $arrayMode = NULL, $Options = NULL) {
        try {
            $str = '<select name="' . $idName . '" id="' . $idName . '"' . (isset($Options['size']) && !empty($Options['size']) ? ' size="' . $Options['size'] . '"' : '') . (isset($Options['class']) && !empty($Options['class']) ? ' class="' . $Options['class'] . '"' : '') . (isset($Options['style']) && !empty($Options['style']) ? ' style="' . $Options['style'] . '"' : '') . (isset($Options['onchange']) && !empty($Options['onchange']) ? ' onchange="' . $Options['onchange'] . '"' : '') . '>';
            if (isset($Options['firstText']) && !empty($Options['firstText'])) {
                $str .= '<option value="">' . $Options['firstText'] . '</option>';
            }
            if (count($array) > 0) {
                switch ($arrayMode) {
                    case 'stdObject':
                        foreach ($array as $value) {
                            $str .= '<option value="' . $value->$datavaluefield . '"' . ($match == $value->$datavaluefield ? ' selected="selected"' : '') . '>' . $value->$datatextfield . '</option>';
                        }
                        break;
                    default:
                        foreach ($array as $value) {
                            $str .= '<option value="' . $value[$datavaluefield] . '"' . ($match == $value[$datavaluefield] ? ' selected="selected"' : '') . '>' . $value[$datatextfield] . '</option>';
                        }
                        break;
                }
            }
            $str .= '</select>';
            unset($key, $value);
            return $str;
        } catch (Exception $ex) {
            return '';
        }
    }

    /* Generate Select
    ******************************************************************/
    function SelectWithNormalArray($array, $match = '', $idName, $Options = NULL) {
        try {
            $att       = '';
            $firstText = '';
            if (!empty($Options) AND count($Options) > 0) {
                foreach ($Options as $name => $value) {
                    if ($name == 'firstText') {
                        $firstText = $value;
                    }
                    else {
                        $att .= ' ' . $name . '="' . $value . '"';
                    }
                }
            }
            $str = '<select name="' . $idName . '" id="' . $idName . '"' . $att . '>';
            if (!empty($Options["firstText"])) {
                $str .= '<option value="">' . $firstText . '</option>';
            }
            if (count($array) > 0) {
                foreach ($array as $key => $value) {
                    $str .= '<option value="' . $key . '"' . (($match == $key) ? " selected " : "") . '>' . $value . '</option>';
                }
            }
            $str .= '</select>';
            unset($key, $value);
            return $str;
        } catch (Exception $ex) {
            return '';
        }
    }

    /* Generate select with table
    *******************************************************************/
    function SelectWithTable($tablename, $where, $orderby, $idName, $datatextfield, $datavaluefield, $matchSelected, $arrayOption = NULL) {
        try {
            $att       = '';
            $firstText = '';
            $char      = '';
            if (!empty($arrayOption) AND count($arrayOption) > 0) {
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
            $str = "<select id=\"" . $idName . "\" name=\"" . $idName . "\"" . $att . ">";
            if ($firstText != '') {
                $str .= "<option value=\"\">" . $firstText . "</option>";
            }
            $array = $this->getArray($tablename, $where, $orderby);
            if (is_array($array)) {
                foreach ($array as $rs) {
                    $str .= "<option value=\"" . $rs[$datavaluefield] . "\"" . (($rs[$datavaluefield] == $matchSelected) ? ' selected="selected"' : '') . ">" . $char . $rs[$datatextfield] . "</option>";
                }
            }
            $str .= "</select>";
            unset($array, $rs);
            return $str;
        } catch (Exception $ex) {
            return '';
        }
    }


    function getvaluefromarray($match, $array) {
        foreach ($array as $key => $value) {
            if ($key == $match) {
                return $value;
            }
        }
    }


    function getNoidathang($arrayCity = NULL, $match, $dataTextField, $dataValueField, $arrayMode) {
        if (count($arrayCity) > 0) {
            switch ($arrayMode) {
                case 'stdObject':
                    foreach ($arrayCity as $value) {
                        if ($value->$dataValueField == $match) {
                            return $value->$dataTextField;
                        }
                    }
                    break;
                default:
                    foreach ($arraycity as $value) {
                        if ($value[$dataValueField] == $match) {
                            return $value[$dataTextField];
                        }
                    }
                    break;
            }
        }
    }


    function Query($sql, $debug = FALSE) {
        try {
            $this->dbConnect();
            if ($debug) {
                echo $sql;
            }
            $rst = mysql_query($sql, $this->connect);
            return $rst;
        } catch (Exception $ex) {
            $ex->getTrace();
        }
    }

    function Free_Result() {
        if (!empty($this->result)) {
            @mysql_free_result($this->result);
        }
    }

    function queryJoin($tbName, $Where = '', $Orderby = '', $Columns = '*', $Limit = '', $TableJoin = NULL, $TypeJoin = 'Inner Join', $debug = FALSE) {
        if (is_array($Columns) && count($Columns) > 0) {
            $Columns = implode(',', $Columns);
        }
        $query = "SELECT $Columns FROM " . $tbName . " " . $this->buildJoin($TableJoin, $TypeJoin) . $this->buildWhere($Where) . ((!empty($Orderby)) ? ' ORDER BY ' . $Orderby : '') . ((!empty($Limit)) ? ' Limit ' . $Limit : '');
        if ($debug == TRUE) {
            echo $query;
        }
        $rst = $this->Query($query);
        unset($query);
        return $rst;
    }

    function buildJoin($TableJoin = NULL, $TypeJoin = 'Inner Join') {
        $strJoin = ' ';
        if (is_array($TableJoin) && count($TableJoin) > 0) {
            foreach ($TableJoin as $Table => $Join) {
                $strJoin .= $TypeJoin . " $Table on $Join ";
            }
        }
        return $strJoin;
    }

    function buildWhere($Where) {
        if (is_array($Where) && count($Where) > 0) {
            $array           = NULL;
            $array_key_where = array_change_key_case(array_keys($Where), CASE_UPPER);
            if (in_array('AND', $array_key_where) || in_array('OR', $array_key_where)) {
                $array[] = $this->buildGroup($Where['AND']);
                $array[] = $this->buildGroup($Where['OR'], 'OR');
            }
            else {
                $array[] = $this->buildGroup($Where);
            }
            $Where = implode(' AND ', $array);
            unset($array);
        }
        return ((!empty($Where)) ? ' WHERE ' . $Where : '');
    }

    function buildGroup($Conditions = NULL, $Group = 'AND') {
        $tmp_array = [];
        if (is_array($Conditions) && count($Conditions) > 0) {
            foreach ($Conditions as $key => $value) {
                $match = NULL;
                $key   = trim($key);
                $key   = preg_replace('/\s+/', ' ', $key);
                preg_match('/\[+([a-zA-Z0-9|\>|\=|\!|\<|\s])+\]$/', $key, $match);
                if (!empty($key) && is_array($match) && count($match) > 0) {
                    $match = strtoupper(trim(array_pop($match)));
                    switch ($match) {
                        case 'LIKE':
                        case 'REGEXP':
                            $key         = preg_replace('/\]|\[/', '', $key);
                            $tmp_array[] = trim($key) . " '" . $this->safeParam($value) . "'";
                            break;
                        case 'IN':
                        case 'NOT IN':
                            $key         = preg_replace('/\]|\[/', '', $key);
                            $tmp_array[] = trim($key) . " ('" . implode("','", $this->safeParam(explode(',', trim($value, ',')))) . "')";
                            break;
                        case 'IS NOT NULL':
                            $key         = preg_replace('/\[+([^\|]+)+\]/', '', $key);
                            $tmp_array[] = '(' . trim($key) . ' is not null)';
                            break;
                        case 'BETWEEN':
                            $key         = preg_replace('/\[+([^\|]+)+\]/', '', $key);
                            $value       = explode('-', $value);
                            $tmp_array[] = '(' . trim($key) . ' between ' . $this->safeParam($value[0]) . ' and ' . $this->safeParam($value[1]) . ')';
                            break;
                        case '>' :
                        case '>=' :
                        case '<' :
                        case '<=' :
                        case '<>' :
                        case '!=' :
                        case '=' :
                            $key         = preg_replace('/\]|\[/', '', $key);
                            $tmp_array[] = trim($key) . " '" . $this->safeParam($value) . "'";
                            break;
                    }
                }
            }
        }
        return implode(' ' . $Group . ' ', $tmp_array);
    }

    function safeParam($string) {
        return $this->removeSQLInjection($string);
    }

    function removeSQLInjection($string) {
        // Check $string is string or not to escapes a string
        if (!empty($string) && is_string($string) && !get_magic_quotes_gpc()) {
            return mysql_real_escape_string($string);
        }
        else if (is_array($string) && count($string) > 0 && !get_magic_quotes_gpc()) {
            foreach ($string as $key => &$value) {
                if (!empty($value) && is_string($value)) {
                    $value = mysql_real_escape_string($value);
                }
            }
        }
        return $string;
    }

    function arrayToObject($array) {
        if (!is_array($array)) {
            return $array;
        }

        $object = new stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name => $value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = $this->arrayToObject($value);
                }
            }
            return $object;
        }
        else {
            return FALSE;
        }
    }

    function checkValues($value) {
        // Use this function on all those values where you want to check for both sql injection and cross site scripting
        //Trim the value
        $value = trim($value);

        // Stripslashes
        if (get_magic_quotes_gpc()) {
            $value = stripslashes($value);
        }

        // Convert all &lt;, &gt; etc. to normal html and then strip these
        $value = strtr($value, array_flip(get_html_translation_table(HTML_ENTITIES)));

        // Strip HTML Tags
        $value = strip_tags($value);

        // Quote the value
        $value = mysql_real_escape_string($value);
        /*$value = htmlspecialchars ($value);*/
        return $value;
    }
}

?>