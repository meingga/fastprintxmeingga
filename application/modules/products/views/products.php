<div class="pcoded-content">
  <div class="pcoded-inner-content">
    <div class="main-body">
      <div class="page-wrapper">
        <div class="page-body">
          <div class="row">
            <div class="col-md-12">
              <div class="card user-card">
                <div class="card-header">
                  <div class="card-header-left">
                    <a class="btn btn-outline-warning btn-sm btn-round" href="<?= base_url() . $url ?>"><?= $url == 'products' ? 'Tampilkan semua data' : 'Tampilkan hanya data <b>bisa dijual</b>' ?></a>
                    <br><br><label class="text-danger">
                      <i>* data hanya boleh mengandung: <b>a-z A-Z 0-9 space - _ . , ? / @ = + : ( )</b><br>Jika terdapat data selain disebutkan maka tidak akan disimpan ke database agar terhindar dari XSS, dsb</i>
                    </label>
                  </div>
                  <div class="card-header-right">
                    <button class="btn btn-secondary btn-sm btn-round getData">Ambil Data dari API FASTPRINT</button>
                    <button type="button" class="btn btn-outline-success btn-sm btn-round" data-toggle="modal" data-target="#productModal">Tambah Produk</button>
                  </div>
                </div>
                <div class="card-block" style="text-align: left;">
                  <table id="myTable" class="display" style="width:100%">
                    <thead>
                      <tr>
                        <th class="align-middle text-center">No</th>
                        <th class="align-middle text-center">Nama</th>
                        <th class="align-middle text-center">Kategori</th>
                        <th class="align-middle text-center">Harga</th>
                        <th class="align-middle text-center">Status</th>
                        <th class="align-middle text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1;
                      foreach ($products as $value) { ?>
                        <tr>
                          <td class="text-center"><?= $no++ ?></td>
                          <td><?= $value->name ?></td>
                          <td><?= $value->category ?></td>
                          <td class="text-right"><?= "Rp " . number_format($value->price, 0, ',', '.') ?></td>
                          <td>
                            <div class="label-main">
                              <label class="label <?= $value->status === 'bisa dijual' ? 'label-success' : ($value->status === 'tidak bisa dijual' ? 'label-danger' : 'label-inverse') ?>"><?= $value->status ?></label>
                            </div>
                          </td>
                          <td>

                            <button type="button" class="btn btn-outline-warning btn-sm btn-round btn_update" data-toggle="modal" data-target="#updateProductModal" data-id="<?= $value->id ?>">Edit</button>
                            <button class="btn btn-outline-danger btn-sm btn-round del" data-id="<?= $value->id ?>" data-name="<?= $value->name ?>">Hapus</button>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div id="styleSelector"></div>

        <!-- add modal -->
        <div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:9999" data-keyboard="false" data-backdrop="static">
          <div class="modal-dialog modal-lg" role=" document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Tambah Produk</h5>
              </div>
              <div class="modal-body">
                <form>
                  <div class="form-group">
                    <label class="col-form-label">Nama</label>
                    <input type="text" class="form-control" id="product_name">
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Kategori</label>
                    <select name="product_category" class="form-control" id="product_category">
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Harga</label>
                    <input type="text" class="form-control" id="product_price" onkeypress="return hanyaAngka(event)" maxlength="15">
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Status</label>
                    <select name="product_status" class="form-control" id="product_status">
                    </select>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success save_product">Simpan</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end modal -->


        <!-- update modal -->
        <div class="modal fade" id="updateProductModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:9999" data-keyboard="false" data-backdrop="static">
          <div class="modal-dialog modal-lg" role=" document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="updateProductModalLabel">Update Produk</h5>
              </div>
              <div class="modal-body">
                <form>
                  <div class="form-group">
                    <label class="col-form-label">Nama</label>
                    <input type="text" class="form-control" id="product_name">
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Kategori</label>
                    <select name="product_category" class="form-control" id="product_category">
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Harga</label>
                    <input type="text" class="form-control" id="product_price" onkeypress="return hanyaAngka(event)" maxlength="16">
                  </div>
                  <div class="form-group">
                    <label class="col-form-label">Status</label>
                    <select name="product_status" class="form-control" id="product_status">
                    </select>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success update_product">Update</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end modal -->
      </div>
    </div>