<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 18.01.2017
 * Time: 12:02
 */

class GOKSELDB {

    var $table = false;
    var $where = '';
    var $order_by = '';
    var $limit = '';
    var $offset = '';
    var $select = '*';
    var $current_query = '';
    var $keep_query_after_get = FALSE;
    var $debug_queries = FALSE;

    var $db;

    function __construct(wpdb $wpdb, $table=false) {
        
        $this->db = $wpdb;
        $this->table = $table;
        if( $this->table ) {
            $tableSearch = $this->db->get_var("SHOW TABLES LIKE '$this->table'");
            if ($tableSearch != $this->table) {
                $this->refresh_database();
            }
        }

        //add_action('init', array(&$this, 'init'));
    }

    function init() {
        
        $tableSearch = $this->db->get_var("SHOW TABLES LIKE '$this->table'");
        if ($tableSearch != $this->table) {
            $this->refresh_database();
        }
    }

    function refresh_database() {
        echo '<div class="alert">You need to override the function refresh_database!</div>';
    }

    function keep_query() {
        $this->keep_query_after_get = TRUE;
    }

    function reset_query() {
        if ($this->keep_query_after_get) {
            $this->keep_query_after_get = FALSE;
        } else {
            $this->select = '*';
            $this->where = '';
            $this->order_by = '';
            $this->limit = '';
            $this->offset = '';
            $this->current_query = '';
        }
    }

    function select($key_or_array) {
        if (is_array($key_or_array)) {
            foreach ($key_or_array as $key) {
                $this->select($key);
            }
        } else {
            $escaped_key = (strpos($key_or_array, '(*)') === FALSE ? '`' . $key_or_array . '`' : $key_or_array);
            if ($this->select == '*') {
                $this->select = $escaped_key;
            } else {
                $this->select .= ', ' . $escaped_key;
            }
        }
    }

    function where($key_or_array, $value = NULL) {
        if (is_array($key_or_array)) {
            foreach ($key_or_array as $key => $val) {
                $this->where($key, $val);
            }
        } else {
            if ($this->where == '') {
                $this->where = ' WHERE ' . $key_or_array . ' = \'' . addslashes($value) . '\'';
            } else {
                $this->where .= ' AND ' . $key_or_array . ' = \'' . addslashes($value) . '\'';
            }
        }
    }
    function or_where($key_or_array, $value = NULL) {
        if ($this->where == '') {
            $this->where = ' WHERE ' . $key_or_array . ' = \'' . addslashes($value) . '\'';
        } else {
            $this->where .= ' OR ' . $key_or_array . ' = \'' . addslashes($value) . '\'';
        }
    }

    function order_by($key, $sort = 'ASC') {

        if($key == 'random') {
            $this->order_by = 'ORDER BY RAND() ';
        }
        else {
            $this->order_by = ' ORDER BY `' . $key . '` ' . $sort;
        }
    }

    function limit($row_count, $offset = NULL) {
        if ($offset != NULL) {
            $this->offset($offset);
        }
        $this->limit = ' LIMIT ' . $row_count;
    }

    function offset($offset) {
        $this->offset = ' OFFSET ' . $offset;
    }

    function get($id = NULL, $single = FALSE, $var = FALSE) {
        
        $output = NULL;
        if ($id != NULL) {
            $this->where('id', $id);
        }
        $this->build_get_query();
        if ($var) {
            $output = $this->db->get_var($this->current_query);
        } else {
            if ($id == NULL) {
                if ($single) {
                    $output = $this->db->get_row($this->current_query);
                } else {
                    $output = $this->db->get_results($this->current_query);
                }
            } else {
                $output = $this->db->get_row($this->current_query);
            }
        }
        $this->reset_query();
        return $output;
    }

    function get_var() {
        return $this->get(NULL, FALSE, TRUE);
    }

    function build_get_query() {
        $this->current_query = '';
        $this->current_query .= 'SELECT ' . $this->select . ' FROM ' . $this->table;
        $this->current_query .= $this->where;
        $this->current_query .= $this->order_by;
        $this->current_query .= $this->limit;
        $this->current_query .= $this->offset;
        if ($this->debug_queries && function_exists('dump')) { dump($this->current_query, 'current_query'); }
    }

    function save($data, $id = NULL) {
        
        if (!$data || !count($data)) {
            return FALSE;
        }

        if ($id != NULL) {
            // update row
            $result = $this->db->update($this->table, $data, array('id' => $id));
        } else {
            // insert new row
            $result = $this->db->insert($this->table, $data);
        }
        return $result;
    }

    function delete($id) {
        
        $this->db->query('DELETE FROM ' . $this->table . ' WHERE id="' . $id . '"');
    }

    function debug_print_database() {
        $data = $this->get();
        $row = 0;
        echo '<table>';
        foreach ($data as $result) {
            if ($row == 0) {
                echo '<thead><tr>';
                foreach ($result as $key=>$value) { echo '<th>' . $key . '</th>'; }
                $row++;
                echo '</tr></thead>';
            }
            echo '<tr>';
            foreach ($result as $key=>$value) {
                $val = $value;
                if (strlen($val) > 100) {
                    $val = substr(strip_tags($value), 0, 100) . ' [...] ';
                }
                echo '<td valign="top">' . $val . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table>';
    }

    public function delete_table() {
        
        $this->db->query("DROP TABLE IF EXISTS $this->table");
    }
}
