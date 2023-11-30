<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Category_model extends MY_Model
{
    public function get()
    {
        $sql = "SELECT * FROM category order by id asc";

        return $this->db->query($sql)->result();
    }

    public function get_by_id($id)
    {
        $sql = "SELECT *
                FROM category
                WHERE id = '{$id}'";

        return $this->db->query($sql)->row();
    }

    public function set($data)
    {
        $this->db->insert('category', $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('category');
    }

    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('category', $data);
    }
}

// EOF