function ready() {
	$('#myTable').DataTable({
		scrollX: true,
	});
}

$('.del').click(function () {
	Swal.fire({
		title: 'Apakah anda yakin ingin menghapus ' + $(this).data('name') + '?',
		showCancelButton: true,
		confirmButtonText: "Ya",
		cancelButtonText: "Tidak",
	}).then((result) => {
		if (result.value) {
			$.ajax({
				type: 'POST',
				url: siteUrl + 'master/delete',
				data: {
					"master": 'category',
					"id": $(this).data('id')
				},
				beforeSend: function () {
					$('.del').prop('disabled', true);
				},
				success: function (msg) {
					Swal.fire({
						timer: 3000,
						title: 'Data berhasil dihapus!',
						type: 'success',
					}).then(function () {
						location.reload();
					});
				},
				error: function (msg) {
					Swal.fire({
						timer: 3000,
						title: 'Oops!',
						text: 'Please check your internet connection',
						type: 'error'
					})
				},
				complete: function () {
					$('.del').prop('disabled', false);
				}
			});
		}
	})
})

$('#categoryModal').on('shown.bs.modal', function () {
	$('#category_name').val('');
	$('#category_name').trigger('focus');
});

$('.btn_update').click(function () {
	id = $(this).data("id");
	$('.update_category').attr("data-id", id);

	$.ajax({
		type: 'GET',
		url: siteUrl + 'master/get_data_by_id',
		data: {
			"master": 'category',
			"id": id
		},
		success: function (msg) {
			data = JSON.parse(msg)
			if (data === 'idnotvalid') {
				Swal.fire({
					timer: 3000,
					title: 'Oops!',
					text: '(ID: '+id + ') Data tidak tersedia!',
					type: 'error'
				})

			} else {
				$('#updateCategoryModal #category_name').val(data.name);
			}
		},
		error: function (msg) {
			Swal.fire({
				timer: 3000,
				title: 'Oops!',
				text: 'Please check your internet connection',
				type: 'error'
			})
		}
	});
});

$('.save_category').click(function () {
	if ($('#category_name').val() == '') {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Nama kategori wajib diisi!',
			type: 'error'
		})
	} else {
		Swal.fire({
			title: "Apakah anda yakin ingin menyimpan produk " + $('#category_name').val() + "?",
			showCancelButton: true,
			confirmButtonText: "Ya",
			cancelButtonText: "Tidak",
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.value) {
				$.ajax({
					type: 'POST',
					url: siteUrl + 'master/add',
					data: {
						"master": 'category',
						'name': $('#category_name').val()
					},
					beforeSend: function () {
						$('.save_category').prop('disabled', true);
					},
					success: function (msg) {
						var obj = JSON.parse(msg);
						if (obj == 'success') {
							Swal.fire({
								timer: 3000,
								title: 'Sukses',
								text: 'Data telah disimpan',
								type: 'success',
								onClose: () => {
									location.reload()
								}
							})
						} else if (obj === 'dataexist' || obj === 'failed') {
							Swal.fire({
								timer: 3000,
								title: 'Gagal',
								text: 'Data gagal disimpan/Kategori sudah tersedia!',
								type: 'error',
								onClose: () => {
									location.reload()
								}
							})
						} else {
							Swal.fire({
								timer: 3000,
								title: 'Gagal',
								text: 'Data gagal disimpan, ' + obj,
								type: 'error'
							})
						}
					},
					error: function (msg) {
						Swal.fire({
							timer: 3000,
							title: 'Oops!',
							text: 'Please check your internet connection',
							type: 'error'
						})
					},
					complete: function () {
						$('.save_category').prop('disabled', false);
					}
				})
			}
		})
	}
})

$('.update_category').click(function () {
	id = $(this).data("id");

	if ($('#updateCategoryModal #category_name').val() == '') {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Nama category wajib diisi!',
			type: 'error'
		})
	} else {
		Swal.fire({
			title: "Apakah anda yakin ingin mengupdate produk " + $('#updateCategoryModal #category_name').val() + "?",
			showCancelButton: true,
			confirmButtonText: "Ya",
			cancelButtonText: "Tidak",
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.value) {
				$.ajax({
					type: 'POST',
					url: siteUrl + 'master/update/' + id,
					data: {
						"master": 'category',
						'name': $('#updateCategoryModal #category_name').val()
					},
					beforeSend: function () {
						$('.update_category').prop('disabled', true);
					},
					success: function (msg) {
						var obj = JSON.parse(msg);

						if (obj === 'success') {
							Swal.fire({
								timer: 3000,
								title: 'Sukses',
								text: 'Data telah disimpan',
								type: 'success',
								onClose: () => {
									location.reload()
								}
							})
						} else if (obj === 'idnotvalid') {
							Swal.fire({
								timer: 3000,
								title: 'Gagal',
								text: '(ID: '+id + ') Data tidak tersedia!',
								type: 'error',
								onClose: () => {
									location.reload()
								}
							})
						} else if (obj === 'dataexist' || obj === 'failed') {
							Swal.fire({
								timer: 3000,
								title: 'Gagal',
								text: 'Data gagal disimpan/Kategori sudah tersedia!',
								type: 'error',
								onClose: () => {
									location.reload()
								}
							})
						} else {
							Swal.fire({
								timer: 3000,
								title: 'Gagal',
								text: 'Data gagal disimpan, ' + obj,
								type: 'error'
							})
						}
					},
					error: function (msg) {
						Swal.fire({
							timer: 3000,
							title: 'Oops!',
							text: 'Please check your internet connection',
							type: 'error'
						})
					},
					complete: function () {
						$('.update_category').prop('disabled', false);
					}
				})
			}
		})
	}
})