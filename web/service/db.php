<?php

require_once 'config.php';

class DB {

    var $__config;
    var $__table;
    var $__query = array();

    function DB($host = "", $database = "", $user = "", $password = "", $newlink = null, $charset = null) {
        $this->__config = array('host' => $host
            , 'db' => $database
            , 'user' => $user
            , 'password' => $password
            , 'charset' => $charset);

        $this->__config['link'] = mysql_connect($host, $user, $password, $newlink) or $this->error();
        mysql_select_db($database, $this->__config['link']);
        // mysql_set_charset($charset, $this->__config['link']);
        if ($charset)
            mysql_query("SET NAMES '$charset'", $this->__config['link']);
    }

    function error() {
        throw new Exception(mysql_error());
    }

    function begin_query() {
        $this->__query = array();
        return $this;
    }

    function where($where) {
        $this->__query['where'][] = $where;
        return $this;
    }

    function table($table) {
        $this->__query['table'][] = $table;
        return $this;
    }

    function select($select) {
        $this->__query['select'][] = $select;
        return $this;
    }

    function order_by($order) {
        $this->__query['order_by'][] = $order;
        return $this;
    }

    function order_by_desc($order) {
        $this->__query['order_by'][] = "$order desc";
        return $this;
    }

    function limit($pos, $num) {
        $this->__query['limit'] = "$pos, $num";
        return $this;
    }

    function group_by($group_by) {
        $this->__query['group_by'][] = $group_by;
        return $this;
    }

    function count() {
        $where_s = ' 1 ';
        foreach ($this->__query['where'] as $w) {
            $where_s .= ' and ' . $this->__general_where($w);
        }

        $table_s = implode(',', $this->__query['table']);

        $select_s = ' count(*) ';

        $group_by_s = '';
        if (isset($this->__query['group_by']))
            $group_by_s = "group by " . implode(',', $this->__query['group_by']);

        $order_by_s = '';
        if (isset($this->__query['order_by']))
            $group_by_s = "group by " . implode(',', $this->__query['order_by']);

        $limit_s = 'limit 20';
        if (isset($this->__query['limit']))
            $limit_s = 'limit ' . $this->__query['limit'];

        $sql = "select $select_s from $table_s where $where_s $group_by_s $order_by_s $limit_s";

        $res = $this->__query($sql, 'Q');

        if ($res && $res->next()) {
            return $res->__data[0];
        }

        return 0;
    }

    function exec() {
        $where_s = ' 1 ';
        foreach ($this->__query['where'] as $w) {
            $where_s .= ' and ' . $this->__general_where($w);
        }

        $table_s = implode(",", $this->__query['table']);

        $select_s = ' * ';
        if (isset($this->__query['select']))
            $select_s = implode(',', $this->__query['select']);

        $group_by_s = '';
        if (isset($this->__query['group_by']))
            $group_by_s = 'group by ' . implode(',', $this->__query['group_by']);

        $order_by_s = '';
        if (isset($this->__query['order_by']))
            $order_by_s = 'order by ' . implode(',', $this->__query['order_by']);

        $limit_s = ' ';
        if (isset($this->__query['limit']))
        $limit_s = 'limit ' . $this->__query['limit'];

        $sql = "select $select_s from $table_s where $where_s $group_by_s $order_by_s $limit_s";

        // echo $sql;
        __info_log($sql, __FILE__, __LINE__);
        return $this->__query($sql, 'Q');
    }

    function update($where, $data) {
        if (empty($where) == true || empty($data) == true)
            return false;

        $sql = "update $this->__table set ";

        foreach ($data as $key => $value) {
            $key = mysql_real_escape_string($key);
            if (is_int($value) || is_float($value)) {
                $sql .= " $key = $value,";
            } else {
                $value = mysql_real_escape_string($value);
                $sql .= " $key = '$value',";
            }
        }

        $sql = substr($sql, 0, -1);

        $sql .= ' where ' . $this->__general_where($where);

        return $this->__query($sql, 'U');
    }

    function insert($data) {
        if (empty($data) == true)
            return false;

        $keys = '';
        $values = '';
        foreach ($data as $key => $value) {

            if (!is_int($value) && $value == NULL)
                continue;

            $key = mysql_real_escape_string($key);
            $keys .= " $key,";

            if (is_int($value) || is_float($value)) {
                $values .= " $value,";
            } else {
                $value = mysql_real_escape_string($value);
                $values .= "'$value',";
            }
        }


        $keys = substr($keys, 0, -1);
        $values = substr($values, 0, -1);

        $sql = "insert into $this->__table ($keys) values ($values)";
        return $this->__query($sql, 'I');
    }

    function delete($where) {
        $sql = "delete from $this->__table where " . $this->__general_where($where);
        $this->__query($sql, 'D');
    }

    function set_table($t) {
        $this->__table = $t;
        return $this;
    }

    function tables() {
        $r = $this->__query("show tables", 'Q');
        while ($r->next()) {
            $res[] = $r->__data[0];
        }
        return $res;
    }

    function desc() {
        $r = $this->__query("desc " . $this->__table, 'Q');
        while ($r->next()) {
            $res[$r->Field]["type"] = $r->Type;
            $res[$r->Field]['null'] = $r->Null == 'YES';
            $res[$r->Field]['key'] = $r->Key;
        }
        return $res;
    }

    function __query($sql, $action) {
        //print $sql;
        $res = mysql_query($sql, $this->__config['link'])
                or $this->error();
        if ($action == 'I')
            return mysql_insert_id($this->__config['link']);
        else if ($action == 'Q') {
            return new DBRecord($res, $this);
        }
        else
            return $res;
    }

    function __general_where($where) {
        if ($where == '')
            return ' 1 ';
        if (is_string($where))
            return $where;
        $sql = ' 1 ';
        foreach ($where as $key => $value) {
            if (is_int($key) == false) {
                $key = mysql_real_escape_string($key);
                if (is_int($value) || is_float($value)) {
                    $sql .= " and $key = $value ";
                } else {
                    $value = mysql_real_escape_string($value);
                    $sql .= " and $key = '$value' ";
                }
            } else {
                $sql .= " and $value ";
            }
        }
        return $sql;
    }

}

class DBRecord {

    var $__res;
    var $__data;
    var $__newdata;
    var $__db;

    function DBRecord($res, $db) {
        $this->__res = $res;
        $this->__db = $db;
    }

    function free() {
        mysql_free_result($this->__res);
    }

    function count() {
        return mysql_num_rows($this->__res);
    }

    function next() {
        $this->__newdata = array();
        $this->__data = mysql_fetch_array($this->__res); // mysql_fetch_array($this->__res);
        return $this->__data !== false;
    }

    function next_assoc() {
        $this->__newdata = array();
        $this->__data = mysql_fetch_assoc($this->__res); // mysql_fetch_array($this->__res);
        return $this->__data !== false;
    }

    function next_row() {
        $this->__newdata = array();
        $this->__data = mysql_fetch_row($this->__res); // mysql_fetch_array($this->__res);
        return $this->__data !== false;
    }

    function __set($name, $value) {
        $this->__newdata[$name] = $value;
    }

    function __get($name) {
        if (isset($this->__newdata[$name]))
            return $this->__newdata[$name];
        return $this->__data[$name];
    }

    function save() {
        $this->__db->update(array('id' => $this->id), $this->__newdata);
    }

    function delete() {
        $this->__db->delete(array('id' => $this->id));
    }

    function abort() {
        $this->__newdata = array();
    }

}

function get_db($db_config = 'db') {
    global $config;

    extract($config[$db_config]);

    static $singledb;
    if (!empty($singledb)) {
        try {
            $singledb->__query('select 1', 'Q');
        } catch (Exception $e) {
            echo "mysql get_db[$db_config] error " . $e->getMessage() . "\n";
            $singledb = new DB($host, $db, $user, $password, true, $charset);
        }
        return $singledb;
    }

    $singledb = new DB($host, $db, $user, $password, null, $charset);

    return $singledb;
}


?>