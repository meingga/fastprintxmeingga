<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('master/category_model', 'category_model');
        $this->load->model('master/status_model', 'status_model');
        $this->products = $this->products_model->get();
        $this->category = $this->category_model->get();
        $this->status   = $this->status_model->get();
    }

    public function index()
    {
        $is_selling = $this->input->get('is_selling');
        $url = 'products?is_selling=true';
        if ($is_selling) {
            $this->products = $this->products_model->get($is_selling);
            $url = 'products';
        }

        $data = [
            'products' => $this->products,
            'url'      => $url,
            'title'    => 'PRODUCTS',
            'content'  => 'products',
        ];
        $this->load->view('_template/index', $data);
    }

    public function get_data_by_id()
    {
        $id = $this->input->get('id');
        if (preg_match('/[^0-9]/', $id)) {
            echo json_encode("idnotvalid");
            exit;
        }
        $product = $this->products_model->get_by_id($id);
        $data = [
            'product'  => $product,
            'category' => $this->category,
            'status'   => $this->status,
        ];

        echo json_encode($data);
    }

    public function add()
    {
        $this->form_validation->set_rules('name', 'name', 'trim|required|callback_special_char', ['special_char' => 'Nama mengandung spesial karakter, Hanya bisa input: a-z A-Z 0-9 space - _ . , ? / @ = + : ( )']);
        $this->form_validation->set_rules('category', 'category', 'trim|numeric');
        $this->form_validation->set_rules('price', 'price', 'trim|numeric|max_length[9]');
        $this->form_validation->set_rules('status', 'status', 'trim|numeric');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(strip_tags(validation_errors()));
        } else {
            $name   = trim(strtoupper($this->input->post('name')));
            $found = in_array($name, array_map(function ($object) {
                return $object->name;
            }, $this->products));

            if (!$found) {
                $data = [
                    'name'          => $name,
                    'price'         => $this->input->post('price'),
                    'category_id'   => $this->input->post('category'),
                    'status_id'     => $this->input->post('status'),
                ];

                $this->db->trans_start();
                $this->products_model->set($data);
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
            $this->db->trans_start();
            $this->products_model->delete($this->input->post('id'));
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
        $this->form_validation->set_rules('category', 'category', 'trim|numeric');
        $this->form_validation->set_rules('price', 'price', 'trim|numeric|max_length[9]');
        $this->form_validation->set_rules('status', 'status', 'trim|numeric');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(strip_tags(validation_errors()));
        } else {
            if (preg_match('/[^0-9]/', $id)) {
                echo json_encode("idnotvalid");
                exit;
            }
            $name    = trim(strtoupper($this->input->post('name')));
            $product = $this->products_model->get_by_id($id);
            if ($product) {
                if ($product->name != $name) {
                    $found = in_array($name, array_map(function ($object) {
                        return $object->name;
                    }, $this->products));

                    if ($found) {
                        echo json_encode("dataexist");
                        exit;
                    }
                }

                $data = [
                    'name'          => $name,
                    'category_id'   => $this->input->post('category'),
                    'price'         => $this->input->post('price'),
                    'status_id'     => $this->input->post('status'),
                ];

                $this->db->trans_start();
                $this->products_model->update($data, $id);
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

    public function add_data_api_fastprint()
    {
        $result = $this->_get_data_api_fastprint();

        if ($result && isset($result->data)) {
            $category = $this->category;
            $status = $this->status;
            $data = [];
            $this->db->trans_start();
            foreach ($result->data as $value) {
                // ada pengecekan data jika data tidak sesuai dengan kriteria maka akan di by pass / tidak di tambahkan
                $is_valid = $this->_validate_input_special_char($value->harga, 'numeric');
                if (!$is_valid) continue;
                $name_product = trim(strtoupper($value->nama_produk));
                $is_valid = $this->_validate_input_special_char($name_product, 'string');
                if (!$is_valid) continue;
                $category_product = trim(strtoupper($value->kategori));
                $is_valid = $this->_validate_input_special_char($category_product, 'string');
                if (!$is_valid) continue;
                $status_product = trim(strtolower($value->status));
                $is_valid = $this->_validate_input_special_char($status_product, 'string');
                if (!$is_valid) continue;

                $is_exist_procduct = in_array($name_product, array_map(function ($object) {
                    return $object->name;
                }, $this->products));
                $is_exist_category = in_array($category_product, array_map(function ($object) {
                    return $object->name;
                }, $category));
                $is_exist_status = in_array($status_product, array_map(function ($object) {
                    return $object->name;
                }, $status));


                if (!$is_exist_procduct) {
                    if (!$is_exist_category) {
                        $id_category = $this->category_model->set([
                            'name' => $category_product
                        ]);
                        array_push($category, (object)["id" => $id_category, "name" => $category_product]);
                    }
                    $search = $category_product;
                    $found = array_filter($category, function ($v, $k) use ($search) {
                        return $v->name === $search;
                    }, ARRAY_FILTER_USE_BOTH);
                    $id_category = array_values($found)[0]->id;

                    if (!$is_exist_status) {
                        $id_status = $this->status_model->set([
                            'name' => $status_product
                        ]);
                        array_push($status, (object)["id" => $id_category, "name" => $status_product]);
                    }
                    $search = $status_product;
                    $found = array_filter($status, function ($v, $k) use ($search) {
                        return $v->name === $search;
                    }, ARRAY_FILTER_USE_BOTH);
                    $id_status = array_values($found)[0]->id;

                    array_push($data, [
                        'name'          => $name_product,
                        'price'         => $value->harga,
                        'category_id'   => $id_category,
                        'status_id'     => $id_status
                    ]);
                }
            }

            if (count($data) > 0) {
                $this->products_model->insert_many3($data, 'products');
                $this->db->trans_complete();
                if ($this->db->trans_status() == TRUE) {
                    echo json_encode("success");
                } else {
                    echo json_encode("failed");
                }
            } else {
                echo json_encode("notnewdata");
            }
        } else {
            echo json_encode("notfound");
        }
    }

    // get data api fastprint
    private function _get_data_api_fastprint($stringUsername = null, $max = 1)
    {
        $this->load->library('restcurl');
        $url = 'https://recruitment.fastprint.co.id/tes/api_tes_programmer';
        $now = explode(" ", date('dmy H:i', strtotime(date('H:i') . ' +1 hour')));
        $username  = $stringUsername != null ? $stringUsername : "tesprogrammer" . $now[0] . "C" . explode(":", $now[1])[0];
        $date = date_create_from_format('dmy',  preg_replace('/[^0-9]/', '', explode("C", $username)[0]));
        $dateNow = $date->format('d-m-y');
        $stringPassword = "bisacoding-$dateNow";
        $password  = md5($stringPassword);
        $data = [
            'username' => $username,
            'password' => $password,
        ];

        $header = [
            "Content-Type:multipart/form-data"
        ];

        if ($max <= 2) {
            $curl = $this->restcurl->post($url, $data, $header);
            $result = json_decode($curl);

            if (isset($result->error)) {
                if ($result->error == 1) {
                    $max = $max + 1;
                    $data = $this->_get_data_api_fastprint($result->username, $max);
                    return $data;
                }
            }

            return $result;
        }
        return false;
    }

    // validation input
    private function _validate_input_special_char($str, $type)
    {
        if ($type === 'numeric') {
            // cek input jika bertipe numeric maka hanya boleh mengandung angka
            if (preg_match('/[^0-9]/', $str)) {
                return FALSE;
            }
        } else {
            // cek input jika bertipe string maka hanya boleh mengandung a-z A-Z space - _ . , ? / @ = + : ( )
            if (preg_match('/[^a-zA-Z0-9\s\-_\.,\?\/@=+:()]/', $str)) {
                return FALSE;
            }
        }
        return TRUE;
    }
}
