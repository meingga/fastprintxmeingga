<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('category_model');
        $this->load->model('status_model');
        $this->category = $this->category_model->get();
        $this->status = $this->status_model->get();
    }
    public function category()
    {
        $data = [
            'data'     => $this->category,
            'title'    => 'CATEGORY',
            'content'  => 'category',
        ];

        $this->load->view('_template/index', $data);
    }

    public function status()
    {
        $data = [
            'data'     => $this->status,
            'title'    => 'STATUS',
            'content'  => 'status',
        ];

        $this->load->view('_template/index', $data);
    }

    function get_data_category()
    {
        echo json_encode($this->category);
    }

    function get_data_status()
    {
        echo json_encode($this->status);
    }

    public function get_data_by_id()
    {
        $id = $this->input->get('id');
        if (preg_match('/[^0-9]/', $id)) {
            echo json_encode("idnotvalid");
            exit;
        }
        $master = $this->input->get('master');
        $model = $master . "_model";

        $data = $this->$model->get_by_id($id);
        echo json_encode($data);
    }
    
    public function add()
    {
        $this->form_validation->set_rules('name', 'name', 'trim|required|callback_special_char', ['special_char' => 'Nama mengandung spesial karakter, Hanya bisa input: a-z A-Z 0-9 space - _ . , ? / @ = + : ( )']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(strip_tags(validation_errors()));
        } else {
            $master = $this->input->post('master');
            $name   = $master === 'category' ? trim(strtoupper($this->input->post('name'))) : trim(strtolower($this->input->post('name')));
            $model = $master . "_model";

            $found = in_array($name, array_map(function ($object) {
                return $object->name;
            }, $this->$master));

            if (!$found) {
                $data = [
                    'name' => $name
                ];

                $this->db->trans_start();
                $this->$model->set($data);
                $this->db->trans_complete();
                if ($this->db->trans_status() == TRUE) {
                    echo json_encode("success");
                } else {
                    echo json_encode("failed");
                }
            } else {
                echo json_encode("dataexist");
            }
        }
    }

    public function delete()
    {
        $this->form_validation->set_rules('id', 'id', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(strip_tags(validation_errors()));
        } else {
            $master = $this->input->post('master');
            $id   = $this->input->post('id');
            $model = $master . "_model";

            $this->db->trans_start();
            $this->$model->delete($id);
            $this->db->trans_complete();

            if ($this->db->trans_status() == true) {
                echo json_encode("success");
            } else {
                echo json_encode("failed");
            }
        }
    }

    public function update($id)
    {
        $this->form_validation->set_rules('name', 'name', 'trim|required|callback_special_char', ['special_char' => 'Nama mengandung spesial karakter, Hanya bisa input: a-z A-Z 0-9 space - _ . , ? / @ = + : ( )']);

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(strip_tags(validation_errors()));
        } else {
            if (preg_match('/[^0-9]/', $id)) {
                echo json_encode("idnotvalid");
                exit;
            }
            $master  = $this->input->post('master');
            $name    = $master === 'category' ? trim(strtoupper($this->input->post('name'))) : trim(strtolower($this->input->post('name')));
            $model   = $master . "_model";

            $$master = $this->$model->get_by_id($id);
            if ($$master) {
                if ($$master->name != $name) {
                    $found = in_array($name, array_map(function ($object) {
                        return $object->name;
                    }, $this->$master));

                    if ($found) {
                        echo json_encode("dataexist");
                        exit;
                    }
                }

                $data = [
                    'name' => $name
                ];

                $this->db->trans_start();
                $this->$model->update($data, $id);
                $this->db->trans_complete();
                if ($this->db->trans_status() == TRUE) {
                    echo json_encode("success");
                } else {
                    echo json_encode("failed");
                }
            } else {
                echo json_encode("idnotvalid");
            }
        }
    }
}
