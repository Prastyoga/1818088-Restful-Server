<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Buku extends RestController
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Buku_model');
    $this->methods['index_get']['limit'] = 2;
  }

  public function index_get()
  {
    $id = $this->get('id', true);
    if ($id === null) {
      $p = $this->get('page', true);
      $p = (empty($p) ? 1 : $p);
      $total_data = $this->Buku_model->count();
      $total_page = ceil($total_data / 5);
      $start = ($p - 1) * 5;
      $list = $this->Buku_model->get(null, 5, $start);
      if ($list) {
        $data = [
          'status' => true,
          'page' => $p,
          'total_data' => $total_data,
          'total_page' => $total_page,
          'data' => $list
        ];
      } else {
        $data = [
          'status' => false,
          'msg' => 'Data tidak ditemukan'
        ];
      }
      $this->response($data, RestController::HTTP_OK);
    } else {
      $data = $this->Buku_model->get($id);
      if ($data) {
        $this->response(['status' => true, 'data' => $data], RestController::HTTP_OK);
      } else {
        $this->response(['status' => false, 'msg' => ' data dengan id '. $id . ' tidak ditemukan'], RestController::HTTP_NOT_FOUND);
      }
    }
  }

  public function index_post()
  {
    $data = [
      'id_buku' => $this->post('id_buku', true),
      'judul_buku' => $this->post('judul_buku', true),
      'penulis_buku' => $this->post('penulis_buku', true),
      'penerbit_buku' => $this->post('penerbit_buku', true),
      'thterbit_buku' => $this->post('thterbit_buku', true)
    ];
    $simpan = $this->Buku_model->add($data);
    if ($simpan['status']) {
      $this->response(['status' => true, 'msg' => $simpan['data'] . ' Data telah ditambahkan'], RestController::HTTP_CREATED);
    } else {
      $this->response(['status' => false, 'msg' => $simpan['msg']], RestController::HTTP_INTERNAL_ERROR);
    }
  }

  public function index_put()
  {
    $data = [
        'id_buku' => $this->put('id_buku', true),
        'judul_buku' => $this->put('judul_buku', true),
        'penulis_buku' => $this->put('penulis_buku', true),
        'penerbit_buku' => $this->put('penerbit_buku', true),
        'thterbit_buku' => $this->put('thterbit_buku', true)
      ];
    $id = $this->put('id_buku', true);
    if ($id === null) {
      $this->response(['status' => false, 'msg' => 'Masukkan ID dari buku yang akan diubah'], RestController::HTTP_BAD_REQUEST);
    }
    $simpan = $this->Buku_model->update($id, $data);
    if ($simpan['status']) {
      $status = (int)$simpan['data'];
      if ($status > 0)
        $this->response(['status' => true, 'msg' => $simpan['data'] . ' Data buku telah diubah'], RestController::HTTP_OK);
      else
        $this->response(['status' => false, 'msg' => 'Tidak ada data buku yang diubah'], RestController::HTTP_BAD_REQUEST);
    } else {
      $this->response(['status' => false, 'msg' => $simpan['msg']], RestController::HTTP_INTERNAL_ERROR);
    }
  }

  public function index_delete()
  {
    $id = $this->delete('id_buku', true);
    if ($id === null) {
      $this->response(['status' => false, 'msg' => 'Masukkan ID buku yang akan dihapus'], RestController::HTTP_BAD_REQUEST);
    }
    $delete = $this->Buku_model->delete($id);
    if ($delete['status']) {
      $status = (int)$delete['data'];
      if ($status > 0)
        $this->response(['status' => true, 'msg' => ' data dengan id '. $id . ' telah dihapus'], RestController::HTTP_OK);
      else
        $this->response(['status' => false, 'msg' => 'Tidak ada data buku yang dihapus'], RestController::HTTP_BAD_REQUEST);
    } else {
      $this->response(['status' => false, 'msg' => $delete['msg']], RestController::HTTP_INTERNAL_ERROR);
    }
  }
}