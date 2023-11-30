<div class="pcoded-content">
  <div class="pcoded-inner-content">
    <div class="main-body">
      <div class="page-wrapper">
        <div class="page-body">
          <div class="row">
            <div class="col-md-12">
              <div class="card user-card">
                <br>
                <div class="card-header">
                  <div class="card-header-right">
                    <button type="button" class="btn btn-outline-success btn-sm btn-round" data-toggle="modal" data-target="#categoryModal">Tambah Kategori</button>
                  </div>
                </div>
                <div class="card-block" style="text-align: left;">
                  <table id="myTable" class="display" style="width:100%">
                    <thead>
                      <tr>
                        <th class="align-middle text-center">No</th>
                        <th class="align-middle text-center">Nama</th>
                        <th class="align-middle text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $no = 1;
                      foreach ($data as $value) { ?>
                        <tr>
                          <td class="text-center"><?= $no++ ?></td>
                          <td><?= $value->name ?></td>
                          <td>
                            <button type="button" class="btn btn-outline-warning btn-sm btn-round btn_update" data-toggle="modal" data-target="#updateCategoryModal" data-id="<?= $value->id ?>">Edit</button>
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
        <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:9999" data-keyboard="false" data-backdrop="static">
          <div class="modal-dialog" role=" document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Tambah Kategori</h5>
              </div>
              <div class="modal-body">
                <label class="text-danger">
                  <i>* data hanya boleh mengandung: <b>a-z A-Z 0-9 space - _ . , ? / @ = + : ( )</b><br>Jika terdapat data selain disebutkan maka tidak akan disimpan ke database agar terhindar dari XSS, dsb</i>
                </label>
                <form>
                  <div class="form-group">
                    <label class="col-form-label">Nama</label>
                    <input type="text" class="form-control" id="category_name">
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success save_category">Simpan</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end modal -->


        <!-- update modal -->
        <div class="modal fade" id="updateCategoryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:9999" data-keyboard="false" data-backdrop="static">
          <div class="modal-dialog" role=" document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="updateCategoryModalLabel">Update Kategori</h5>
              </div>
              <div class="modal-body">
                <label class="text-danger">
                  <i>* data hanya boleh mengandung: <b>a-z A-Z 0-9 space - _ . , ? / @ = + : ( )</b><br>Jika terdapat data selain disebutkan maka tidak akan disimpan ke database agar terhindar dari XSS, dsb</i>
                </label>
                <form>
                  <div class="form-group">
                    <label class="col-form-label">Nama</label>
                    <input type="text" class="form-control" id="category_name">
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success update_category">Update</button>
              </div>
            </div>
          </div>
        </div>
        <!-- end modal -->
      </div>
    </div>