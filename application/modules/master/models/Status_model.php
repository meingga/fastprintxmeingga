<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Status_model extends MY_Model
{
    public function get()
    {
        $sql = "SELECT * FROM status order by id asc";

        return $this->db->query($sql)->result();
    }

    public function get_by_id($id)
    {
        $sql = "SELECT *
                FROM status
                WHERE id = '{$id}'";

        return $this->db->query($sql)->row();
    }

    public function set($data)
    {
        $this->db->insert('status', $data);
        return $this->db->insert_id();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('status');
    }

    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('status', $data);
    }
}

// EOF