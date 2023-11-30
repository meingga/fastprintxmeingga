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
				url: siteUrl + 'products/delete',
				data: {
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

$('.getData').click(function () {
	$text = $('.getData').text();
	$.ajax({
		type: 'GET',
		url: siteUrl + 'products/add_data_api_fastprint',
		beforeSend: function () {
			$('.getData').prop('disabled', true);
			$('.getData').text('Loading...');
		},
		success: function (msg) {
			data = JSON.parse(msg)
			if (data == 'success') {
				Swal.fire({
					timer: 3000,
					title: 'Data berhasil ditambahkan!',
					type: 'success',
				}).then(function () {
					location.reload();
				});
			} else if (data == 'failed') {
				Swal.fire({
					timer: 3000,
					title: 'Oops!',
					text: 'Gagal menyimpan data!',
					type: 'error'
				})
			} else if (data == 'notnewdata') {
				Swal.fire({
					timer: 3000,
					title: 'Oops!',
					text: 'Tidak ada nama produk baru, Semua data nama produk sudah tersedia dalam database!',
					type: 'error'
				})
			} else {
				Swal.fire({
					timer: 3000,
					title: 'Oops!',
					text: 'Please check your internet connection/tidak dapat menghubungi api fastprint',
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
			$('.getData').prop('disabled', false);
			$('.getData').text($text);
		}
	});
})

$('#productModal').on('shown.bs.modal', function () {
	$('#product_name').val('');
	$('#product_name').trigger('focus');
	$('#product_price').val('');
	$('#product_category').val('');
	$('#product_status').val('');

	$.ajax({
		type: 'GET',
		url: siteUrl + 'master/get_data_category',
		success: function (msg) {
			data = JSON.parse(msg)
			$('#product_category')
				.find('option')
				.remove()
				.end()
				.append($('<option>', {
					value: '',
					text: '-- PILIH KATEGORI --'
				}))

			if (data.length > 0) {
				data.forEach(e => {
					$('#product_category').append($('<option>', {
						value: e.id,
						text: e.name.toUpperCase()
					}))
				});
			}
		},
		error: function (msg) {
		}
	});

	$.ajax({
		type: 'GET',
		url: siteUrl + 'master/get_data_status',
		success: function (msg) {
			data = JSON.parse(msg)
			$('#product_status')
				.find('option')
				.remove()
				.end()
				.append($('<option>', {
					value: '',
					text: '-- PILIH STATUS --'
				}))

			if (data.length > 0) {
				data.forEach(e => {
					$('#product_status').append($('<option>', {
						value: e.id,
						text: e.name.toUpperCase()
					}))
				});
			}
		},
		error: function (msg) {
		}
	});
});

$('.btn_update').click(function () {
	id = $(this).data("id");
	$('.update_product').attr("data-id", id);

	$.ajax({
		type: 'GET',
		url: siteUrl + 'products/get_data_by_id',
		data: {
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
				if (data.category.length > 0) {
					$('#updateProductModal #product_category')
						.find('option')
						.remove()
						.end()
						.append($('<option>', {
							value: '',
							text: '-- PILIH KATEGORI --'
						}))

					data.category.forEach(e => {
						$('#updateProductModal #product_category').append($('<option>', {
							value: e.id,
							text: e.name.toUpperCase()
						}))
					});

				}

				if (data.status.length > 0) {
					$('#updateProductModal #product_status')
						.find('option')
						.remove()
						.end()
						.append($('<option>', {
							value: '',
							text: '-- PILIH STATUS --'
						}))

					data.status.forEach(e => {
						$('#updateProductModal #product_status').append($('<option>', {
							value: e.id,
							text: e.name.toUpperCase()
						}))
					});
				}

				if (data.product) {
					$('#updateProductModal #product_name').val(data.product.name);
					$('#updateProductModal #product_category').val(data.product.category_id);
					$('#updateProductModal #product_price').val(formatRupiah(data.product.price, 'Rp. '));
					$('#updateProductModal #product_status').val(data.product.status_id);
				}
			}
		},
		error: function (msg) {
		}
	});
});

$('.save_product').click(function () {
	price = $("#product_price").val() === '' ? '' : formatAngka($("#product_price").val());
	if ($('#product_name').val() == '') {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Nama Product wajib diisi!',
			type: 'error'
		})
	} else if ($('#product_category').find(":selected").val() == '') {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Kategori Product wajib diisi!',
			type: 'error'
		})
	} else if (!$.isNumeric(price) || price.toString().length > 9) {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Harga Product wajib diisi dan wajib angka(maksimum 9)!',
			type: 'error'
		})
	} else if ($('#product_status').find(":selected").val() == '') {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Status Product wajib diisi!',
			type: 'error'
		})
	} else {
		Swal.fire({
			title: "Apakah anda yakin ingin menyimpan produk " + $('#product_name').val() + "?",
			showCancelButton: true,
			confirmButtonText: "Ya",
			cancelButtonText: "Tidak",
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.value) {
				$.ajax({
					type: 'POST',
					url: siteUrl + 'products/add',
					data: {
						'name': $('#product_name').val(),
						'price': price,
						'category': $('#product_category').find(":selected").val(),
						'status': $('#product_status').find(":selected").val()
					},
					beforeSend: function () {
						$('.save_product').prop('disabled', true);
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
								text: 'Data gagal disimpan/Produk sudah tersedia!',
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
						$('.save_product').prop('disabled', false);
					}
				})
			}
		})
	}
})

$('.update_product').click(function () {
	id = $(this).data("id");
	price = formatAngka($("#updateProductModal #product_price").val()) === '' ? '' : formatAngka($("#updateProductModal #product_price").val());
	if ($('#updateProductModal #product_name').val() == '') {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Nama Product wajib diisi!',
			type: 'error'
		})
	} else if ($('#updateProductModal #product_category').find(":selected").val() == '') {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Kategori Product wajib diisi!',
			type: 'error'
		})
	} else if (!$.isNumeric(price) || price.toString().length > 9) {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Harga Product wajib diisi dan wajib angka(maksimum 9)!',
			type: 'error'
		})
	} else if ($('#updateProductModal #product_status').find(":selected").val() == '') {
		Swal.fire({
			timer: 5000,
			title: 'Gagal',
			text: 'Status Product wajib diisi!',
			type: 'error'
		})
	} else {
		Swal.fire({
			title: "Apakah anda yakin ingin mengupdate produk " + $('#updateProductModal #product_name').val() + "?",
			showCancelButton: true,
			confirmButtonText: "Ya",
			cancelButtonText: "Tidak",
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.value) {
				$.ajax({
					type: 'POST',
					url: siteUrl + 'products/update/' + id,
					data: {
						'name': $('#updateProductModal #product_name').val(),
						'price': price,
						'category': $('#updateProductModal #product_category').find(":selected").val(),
						'status': $('#updateProductModal #product_status').find(":selected").val()
					},
					beforeSend: function () {
						$('.update_product').prop('disabled', true);
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
								text: 'Data gagal disimpan/Produk sudah tersedia!',
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
						$('.update_product').prop('disabled', false);
					}
				})
			}
		})
	}
})

$('#updateProductModal #product_price').keyup(function () {
	$("#updateProductModal #product_price").val(formatRupiah(this.value, 'Rp. '));
});

$('#productModal #product_price').keyup(function () {
	$("#productModal #product_price").val(formatRupiah(this.value, 'Rp. '));
});

function formatRupiah(angka, prefix) {
	var number_string = angka.replace(/[^,\d]/g, '').toString(),
		split = number_string.split(','),
		sisa = split[0].length % 3,
		rupiah = split[0].substr(0, sisa),
		ribuan = split[0].substr(sisa).match(/\d{3}/gi)

	// tambahkan titik jika yang di input sudah menjadi angka ribuan
	if (ribuan) {
		separator = sisa ? '.' : '';
		rupiah += separator + ribuan.join('.');
	}

	rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
	return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}


function formatAngka(rupiah) {
	return parseInt(rupiah.replace(/,.*|[^0-9]/g, ''), 10);
}