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
					"master": 'status',
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

$('#statusModal').on('shown.bs.modal', function () {
	$('#status_name').val('');
	$('#status_name').trigger('focus');
});

$('.btn_update').click(function () {
	id = $(this).data("id");
	$('.update_status').attr("data-id", id);

	$.ajax({
		type: 'GET',
		url: siteUrl + 'master/get_data_by_id',
		data: {
			"master": 'status',
			"id": id
		},
		success: function (msg) {
			data = JSON.parse(msg)
			if (data === 'idnotvalid') {
				Swal.fire({
					timer: 3000,
					title: 'Oops!',
					text: '(ID: ' + id + ') Data tidak tersedia!',
					type: 'error'
				})

			} else {
				$('#updateStatusModal #status_name').val(data.name);
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

$('.save_status').click(function () {
	if ($('#status_name').val() == '') {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Nama status wajib diisi!',
			type: 'error'
		})
	} else {
		Swal.fire({
			title: "Apakah anda yakin ingin menyimpan produk " + $('#status_name').val() + "?",
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
						"master": 'status',
						'name': $('#status_name').val()
					},
					beforeSend: function () {
						$('.save_status').prop('disabled', true);
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
								text: 'Data gagal disimpan/Status sudah tersedia!',
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
						$('.save_status').prop('disabled', false);
					}
				})
			}
		})
	}
})

$('.update_status').click(function () {
	id = $(this).data("id");

	if ($('#updateStatusModal #status_name').val() == '') {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Nama status wajib diisi!',
			type: 'error'
		})
	} else {
		Swal.fire({
			title: "Apakah anda yakin ingin mengupdate produk " + $('#updateStatusModal #status_name').val() + "?",
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
						"master": 'status',
						'name': $('#updateStatusModal #status_name').val()
					},
					beforeSend: function () {
						$('.update_status').prop('disabled', true);
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
						} else if (obj === 'idnotvalid') {
							Swal.fire({
								timer: 3000,
								title: 'Gagal',
								text: '(ID: ' + id + ') Data tidak tersedia!',
								type: 'error',
								onClose: () => {
									location.reload()
								}
							})
						} else if (obj === 'dataexist' || obj === 'failed') {
							Swal.fire({
								timer: 3000,
								title: 'Gagal',
								text: 'Data gagal disimpan/Status sudah tersedia!',
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
								type: 'error',
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
						$('.update_status').prop('disabled', false);
					}
				})
			}
		})
	}
})