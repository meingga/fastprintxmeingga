	           </div>
	           </div>
	           </div>
	           </div>
	           </div>

	           <!-- Required Jquery -->
	           <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
	           <script type="text/javascript" src="<?= base_url() ?>assets/js/datatables.min.js"></script>
	           <script type="text/javascript" src="<?= base_url() ?>assets/js/popper.min.js"></script>
	           <script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
	           <!-- modernizr js -->
	           <!-- <script type="text/javascript" src="<?= base_url() ?>assets/js/modernizr.js"></script> -->
	           <!-- swal -->
	           <script type="text/javascript" src="<?= base_url(); ?>assets/js/sweetalert2.all.min.js"></script>
	           <!-- Custom js -->
	           <!-- <script type="text/javascript" src="<?= base_url() ?>assets/js/ckeditor/ckeditor.js"></script> -->
	           <script type="text/javascript" src="<?= base_url() ?>assets/js/pages/<?= $content ?>/index.js"></script>
	           <script type="text/javascript" src="<?= base_url() ?>assets/js/script.js"></script>
	           <script type="text/javascript" src="<?= base_url() ?>assets/js/pcoded.min.js"></script>
	           <script type="text/javascript" src="<?= base_url() ?>assets/js/vartical-demo.js"></script>
	           <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>

	           <script type="text/javascript">
	           	siteUrl = <?= "'" . base_url() . "'" ?>;

	           	window.setTimeout("waktu()", 1000);

	           	function waktu() {
	           		var waktu = new Date();
	           		setTimeout("waktu()", 1000);

	           		var month = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
	           		for (var i = 0; i < month.length; i++) {
	           			if (i == waktu.getMonth()) {
	           				nowMonth = month[i];
	           				break;
	           			}
	           		}

	           		if (waktu.getHours() < 10) {
	           			var nowHour = '0' + waktu.getHours();
	           		} else {
	           			var nowHour = waktu.getHours();
	           		}

	           		if (waktu.getMinutes() < 10) {
	           			var nowMinutes = '0' + waktu.getMinutes();
	           		} else {
	           			var nowMinutes = waktu.getMinutes();
	           		}

	           		if (waktu.getSeconds() < 10) {
	           			var nowSecond = '0' + waktu.getSeconds();
	           		} else {
	           			var nowSecond = waktu.getSeconds();
	           		}

	           		$('#date-now').html(
	           			waktu.getDate() + ' ' + nowMonth + ' ' + waktu.getFullYear() + ' ' + nowHour + ':' + nowMinutes + ':' + nowSecond +
	           			' WIB');
	           	}

	           	$(document).ready(function() {
	           		ready();
	           	});

	           	function hanyaAngka(evt) {
	           		var charCode = (evt.which) ? evt.which : event.keyCode
	           		if (charCode > 31 && (charCode < 48 || charCode > 57))
	           			return false;
	           		return true;
	           	}
	           </script>
	           </body>

	           </html>