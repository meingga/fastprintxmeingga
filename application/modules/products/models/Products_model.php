<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Products_model extends MY_Model
{
    public function get($is_selling = false)
    {
        $where = $is_selling === false ? "" : "AND s.name = 'bisa dijual'";
        $sql = "SELECT p.id, p.name, p.price, s.name as status, c.name as category
                FROM products p
                JOIN status s ON p.status_id = s.id {$where}
                JOIN category c ON p.category_id = c.id ORDER BY p.id DESC";

        return $this->db->query($sql)->result();
    }
    public function get_by_id($id)
    {
        $sql = "SELECT p.id, p.name, p.price, s.name as status, s.id as status_id, c.name as category, c.id as category_id
                FROM products p
                JOIN status s ON p.status_id = s.id
                JOIN category c ON p.category_id = c.id
                WHERE p.id = '{$id}'";

        return $this->db->query($sql)->row();
    }

    public function set($data)
    {
        $this->db->insert('products', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('products');
    }

    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('products', $data);
    }
}

// EOF