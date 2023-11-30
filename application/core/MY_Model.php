<?php

/**
 * A base model with a series of CRUD functions (powered by CI's query builder),
 * validation-in-model support, event callbacks and more.
 *
 * @link http://github.com/jamierumbelow/codeigniter-base-model
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class MY_Model extends CI_Model
{

    /* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */

    /**
     * This model's default database table. Automatically
     * guessed by pluralising the model name.
     */
    protected $_table;

    /**
     * The database connection object. Will be set to the default
     * connection. This allows individual models to use different DBs
     * without overwriting CI's global $this->db connection.
     */
    public $_database;

    /**
     * This model's default primary key or unique identifier.
     * Used by the get(), update() and delete() functions.
     */
    protected $primary_key = 'id';

    /**
     * Support for soft deletes and this model's 'deleted' key
     */
    protected $soft_delete = FALSE;
    protected $soft_delete_key = 'deleted';
    protected $_temporary_with_deleted = FALSE;
    protected $_temporary_only_deleted = FALSE;

    /**
     * The various callbacks available to the model. Each are
     * simple lists of method names (methods will be run on $this).
     */
    protected $before_create = array();
    protected $after_create = array();
    protected $before_update = array();
    protected $after_update = array();
    protected $before_get = array();
    protected $after_get = array();
    protected $before_delete = array();
    protected $after_delete = array();

    protected $callback_parameters = array();

    /**
     * Protected, non-modifiable attributes
     */
    protected $protected_attributes = array();

    /**
     * Relationship arrays. Use flat strings for defaults or string
     * => array to customise the class name and primary key
     */
    protected $belongs_to = array();
    protected $has_many = array();

    protected $_with = array();

    /**
     * An array of validation rules. This needs to be the same format
     * as validation rules passed to the Form_validation library.
     */
    protected $validate = array();

    /**
     * Optionally skip the validation. Used in conjunction with
     * skip_validation() to skip data validation for any future calls.
     */
    protected $skip_validation = FALSE;

    /**
     * By default we return our results as objects. If we need to override
     * this, we can, or, we could use the `as_array()` and `as_object()` scopes.
     */
    protected $return_type = 'object';
    protected $_temporary_return_type = NULL;

    /* --------------------------------------------------------------
     * GENERIC METHODS
     * ------------------------------------------------------------ */

    /**
     * Initialise the model, tie into the CodeIgniter superobject and
     * try our best to guess the table name.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper('inflector');

        $this->_fetch_table();

        $this->_database = $this->db;

        array_unshift($this->before_create, 'protect_attributes');
        array_unshift($this->before_update, 'protect_attributes');

        $this->_temporary_return_type = $this->return_type;

        // $this->db->query("SET application_name to 'MM_Middleware'");
    }

    /* --------------------------------------------------------------
     * CRUD INTERFACE
     * ------------------------------------------------------------ */

    /**
     * Fetch a single record based on the primary key. Returns an object.
     */
    function get_lookup($lookup_key)
    {
        $this->db->where('lookup_key', $lookup_key);
        $row = $this->db->get('t_mtr_lookup');
        if ($row) {
            return $row;
        } else {
            die('Lookup key not found!');
        }
    }

    public function get_all()
    {
        $this->trigger('before_get');

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE) {
            $this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $result = $this->_database->get($this->_table)
            ->{$this->_return_type(1)}();
        $this->_temporary_return_type = $this->return_type;

        foreach ($result as $key => &$row) {
            $row = $this->trigger('after_get', $row, ($key == count($result) - 1));
        }

        $this->_with = array();
        return $result;
    }
    /**
     * Fetch a single record based on an arbitrary WHERE call. Can be
     * any valid value to $this->_database->where().
     */
    public function insert($data, $skip_validation = FALSE)
    {
        if ($skip_validation === FALSE) {
            $data = $this->validate($data);
        }

        if ($data !== FALSE) {
            $data = $this->trigger('before_create', $data);

            $this->_database->insert($this->_table, $data);
            $error = $this->_database->error();

            if ($error['message'] != '') {
                return FALSE;
            }

            $insert_id = $this->_database->insert_id();

            $this->trigger('after_create', $insert_id);

            return $insert_id;
        } else {
            return FALSE;
        }
    }

    public function insert_many3($data, $table, $skip_validation = FALSE)
    {
        if ($skip_validation === FALSE) {
            $data = $this->validate($data);
        }

        if ($data !== FALSE) {
            $data = $this->trigger('before_create', $data);

            $query = $this->_database->insert_batch($table, $data);
            $error = $this->_database->error();

            if ($error['message'] != '') {
                return FALSE;
            }
            if ($query > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
    public function get_by()
    {
        $where = func_get_args();

        if ($this->soft_delete && $this->_temporary_with_deleted !== TRUE) {
            $this->_database->where($this->soft_delete_key, (bool)$this->_temporary_only_deleted);
        }

        $this->_set_where($where);

        $this->trigger('before_get');

        $row = $this->_database->get($this->_table)
            ->{$this->_return_type()}();
        $this->_temporary_return_type = $this->return_type;

        $row = $this->trigger('after_get', $row);

        $this->_with = array();
        return $row;
    }

    public function get_many_by()
    {
        $where = func_get_args();

        $this->_set_where($where);

        return $this->get_all();
    }

    public function update_by()
    {
        $args = func_get_args();
        $data = array_pop($args);

        $data = $this->trigger('before_update', $data);

        if ($this->validate($data) !== FALSE) {
            $this->_set_where($args);
            $result = $this->_database->set($data)
                ->update($this->_table);
            $this->trigger('after_update', array($data, $result));

            return $result;
        } else {
            return FALSE;
        }
    }

    private function _fetch_table()
    {
        if ($this->_table == NULL) {
            $this->_table = plural(preg_replace('/(_m|_model)?$/', '', strtolower(get_class($this))));
        }
    }

    /**
     * Guess the primary key for current table
     */
    private function _fetch_primary_key()
    {
        if ($this->primary_key == NULl) {
            $this->primary_key = $this->_database->query("SHOW KEYS FROM `" . $this->_table . "` WHERE Key_name = 'PRIMARY'")->row()->Column_name;
        }
    }

    /**
     * Set WHERE parameters, cleverly
     */
    protected function _set_where($params)
    {
        if (count($params) == 1 && is_array($params[0])) {
            foreach ($params[0] as $field => $filter) {
                if (is_array($filter)) {
                    $this->_database->where_in($field, $filter);
                } else {
                    if (is_int($field)) {
                        $this->_database->where($filter);
                    } else {
                        $this->_database->where($field, $filter);
                    }
                }
            }
        } else if (count($params) == 1) {
            $this->_database->where($params[0]);
        } else if (count($params) == 2) {
            if (is_array($params[1])) {
                $this->_database->where_in($params[0], $params[1]);
            } else {
                $this->_database->where($params[0], $params[1]);
            }
        } else if (count($params) == 3) {
            $this->_database->where($params[0], $params[1], $params[2]);
        } else {
            if (is_array($params[1])) {
                $this->_database->where_in($params[0], $params[1]);
            } else {
                $this->_database->where($params[0], $params[1]);
            }
        }
    }

    /**
     * Return the method name for the current return type
     */
    protected function _return_type($multi = FALSE)
    {
        $method = ($multi) ? 'result' : 'row';
        return $this->_temporary_return_type == 'array' ? $method . '_array' : $method;
    }

    public function protect_attributes($row)
    {
        foreach ($this->protected_attributes as $attr) {
            if (is_object($row)) {
                unset($row->$attr);
            } else {
                unset($row[$attr]);
            }
        }

        return $row;
    }

    /* --------------------------------------------------------------
     * QUERY BUILDER DIRECT ACCESS METHODS
     * ------------------------------------------------------------ */

    /**
     * A wrapper to $this->_database->order_by()
     */
    public function order_by($criteria, $order = 'ASC')
    {
        if (is_array($criteria)) {
            foreach ($criteria as $key => $value) {
                $this->_database->order_by($key, $value);
            }
        } else {
            $this->_database->order_by($criteria, $order);
        }
        return $this;
    }

    /**
     * A wrapper to $this->_database->limit()
     */
    public function limit($limit, $offset = 0)
    {
        $this->_database->limit($limit, $offset);
        return $this;
    }

    /* --------------------------------------------------------------
     * INTERNAL METHODS
     * ------------------------------------------------------------ */

    /**
     * Trigger an event and call its observers. Pass through the event name
     * (which looks for an instance variable $this->event_name), an array of
     * parameters to pass through and an optional 'last in interation' boolean
     */
    public function trigger($event, $data = FALSE, $last = TRUE)
    {
        if (isset($this->$event) && is_array($this->$event)) {
            foreach ($this->$event as $method) {
                if (strpos($method, '(')) {
                    preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);

                    $method = $matches[1];
                    $this->callback_parameters = explode(',', $matches[3]);
                }

                $data = call_user_func_array(array($this, $method), array($data, $last));
            }
        }

        return $data;
    }

    /**
     * Run validation on the passed data
     */
    public function validate($data)
    {
        if ($this->skip_validation) {
            return $data;
        }

        if (!empty($this->validate)) {
            foreach ($data as $key => $val) {
                $_POST[$key] = $val;
            }

            $this->load->library('form_validation');

            if (is_array($this->validate)) {
                $this->form_validation->set_rules($this->validate);

                if ($this->form_validation->run() === TRUE) {
                    return $data;
                } else {
                    return FALSE;
                }
            } else {
                if ($this->form_validation->run($this->validate) === TRUE) {
                    return $data;
                } else {
                    return FALSE;
                }
            }
        } else {
            return $data;
        }
    }

    public function insert_many2($data, $table)
    {

        $data_insert = array_keys($data[0]);
        $x = count($data_insert);
        $sql  = "INSERT INTO {$table}(  ";
        $a = 0;
        foreach ($data_insert as $key => $value) {
            $sql .= '' . $value . '';
            $sql .= (($x - $a) == 1 ? "" : ",");
            $a++;
        }

        $sql  .= ") VALUES";
        $j = count($data);
        for ($i = 0; $i < $j; $i++) {
            $b = 0;
            $sql .= "(";
            foreach ($data[$i] as $key => $value) {
                if ($value == null) {
                    $sql .= "null";
                } else {
                    $sql .= "'" . $value . "'";
                }

                $sql .= (($x - $b) == 1 ? "" : ",");
                $b++;
            }
            $sql .= ")";
            $sql .= (($j - $i) == 1 ? ";" : ",");
        }
        // print_r($sql);exit();

        $query = $this->db->query($sql);

        return $query;
    }
}
