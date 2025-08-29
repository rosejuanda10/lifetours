<script>
		const isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
		let selectedPackageId = null;
		let selectedPrice = 0;

		// Ambil data paket dari PHP
		const packages = [
			<?php
			include 'config.php';
			$result = $conn->query("SELECT id, package_name, destination, price FROM packages WHERE status = 'Available'");
			$pkgList = [];
			while ($pkg = $result->fetch_assoc()) {
				$pkgList[] = "{
        id: {$pkg['id']},
        name: '" . addslashes($pkg['package_name']) . "',
        destination: '" . addslashes($pkg['destination']) . "',
        price: {$pkg['price']}
      }";
			}
			echo implode(",", $pkgList);
			?>
		];

		function toggleChatbot() {
			const frame = document.getElementById("chatbot-frame");
			if (frame.style.display === "none" || frame.style.display === "") {
				frame.style.display = "flex";
				showMainMenu(); // Tampilkan menu utama saat dibuka
			} else {
				frame.style.display = "none";
			}
		}

		function appendMessage(text, sender) {
			const messages = document.getElementById("chatbot-messages");
			const msg = document.createElement("div");
			msg.classList.add("message", sender);
			msg.innerHTML = text;
			messages.appendChild(msg);
			messages.scrollTop = messages.scrollHeight;
		}

		function showMainMenu() {
			const messages = document.getElementById("chatbot-messages");
			messages.innerHTML = '';
			appendMessage("Halo! Saya asisten virtual LIFE TOURS. 😊<br><br>Silakan ketik pertanyaan seperti:<br><strong>pemesanan, login, bantuan</strong>", "bot");

			// Langsung tampilkan input
			document.querySelector('.chatbot-input').style.display = 'flex';
			document.getElementById('user-input').focus();
		}

		// === FAQ A: Pemesanan ===
		function showPemesanan() {
			appendMessage("A. Pertanyaan Seputar Cara Pemesanan", "user");
			appendMessage(`
		  <strong>Bagaimana cara memesan paket travel?</strong><br>
		  Kita menyediakan pemesanan tiket di website dengan fitur chatbot.<br><br>
	
		  <strong>Apakah bisa memesan paket melalui WhatsApp?</strong><br>
		  Tidak, kita hanya menyediakan WhatsApp untuk hal genting saja.<br><br>
	
		  <strong>Apakah harus datang ke kantor?</strong><br>
		  Tidak perlu, di website sudah disediakan.<br><br>
	
		  <strong>Apakah tersedia pemesanan online?</strong><br>
		  Ya, kami menyediakan fitur chatbot untuk pemesanan yang lebih mudah.<br><br>
	
		  <strong>Berapa lama konfirmasi pemesanan?</strong><br>
		  Maksimal 24 jam.<br><br>
	
		  <strong>Bisa pesan untuk orang lain?</strong><br>
		  Bisa, dengan memilih jumlah pesanan.<br><br>
	
		  <strong>Bisa pesan lebih dari satu paket?</strong><br>
		  Tidak bisa karena belum tentu semua paket tersedia.
		`, "bot");
			showBackButton();
		}

		// === FAQ B: Ketersediaan ===
		function showKetersediaan() {
			appendMessage("B. Pertanyaan Seputar Ketersediaan Paket", "user");
			appendMessage(`
		  <strong>Apakah paket ke [tempat] tersedia?</strong><br>
		  Paket yang tersedia silahkan klik "Pesan Sekarang".<br><br>
	
		  <strong>Kapan jadwal keberangkatan terdekat?</strong><br>
		  Untuk melihat paket yang tersedia, silahkan klik "Pesan Sekarang".<br><br>
	
		  <strong>Berapa jumlah peserta maksimal?</strong><br>
		  Untuk detail, silahkan klik "Pesan Sekarang".<br><br>
	
		  <strong>Bisa cek ketersediaan online?</strong><br>
		  Bisa, dengan klik "Pesan Sekarang".<br><br>
	
		  <strong>Apakah tersedia paket private?</strong><br>
		  Tidak.<br><br>
	
		  <strong>Bisa request tanggal sendiri?</strong><br>
		  Tidak bisa.<br><br>
	
		  <strong>Apakah tersedia paket keluarga?</strong><br>
		  Tidak semua paket tersedia untuk rombongan.
		`, "bot");
			showBackButton();
		}

		// === FAQ C: Pembayaran ===
		function showPembayaran() {
			appendMessage("C. Pertanyaan Seputar Metode Pembayaran", "user");
			appendMessage(`
		  <strong>Metode pembayaran apa saja?</strong><br>
		  Kami hanya menyediakan transfer bank BCA.<br><br>
	
		  <strong>Apakah bisa bayar pakai OVO/DANA/Gopay?</strong><br>
		  Tidak bisa.<br><br>
	
		  <strong>Apakah bisa cicilan atau DP?</strong><br>
		  Tidak bisa.<br><br>
	
		  <strong>Apakah bisa bayar setelah sampai?</strong><br>
		  Tidak bisa.<br><br>
	
		  <strong>Batas waktu pembayaran?</strong><br>
		  24 jam setelah pesan.<br><br>
	
		  <strong>Bagaimana konfirmasi pembayaran?</strong><br>
		  Kirim bukti transfer di halaman pemesanan.<br><br>
	
		  <strong>Apa saya dapat bukti pembayaran?</strong><br>
		  Resi akan dikirim via email dan WA maksimal 3 hari setelah konfirmasi.
		`, "bot");
			showBackButton();
		}

		// === FAQ D: Cancel & Refund ===
		function showCancel() {
			appendMessage("D. Pertanyaan Seputar Refund & Cancel", "user");
			appendMessage(`
		  <strong>Bagaimana cara membatalkan pemesanan?</strong><br>
		  Bisa klik "Batalkan" di halaman My Orders.<br><br>
	
		  <strong>Apakah bisa dapat refund?</strong><br>
		  Tidak, semua pesanan sudah di toleransi 24 jam sebelum dikonfirmasi admin.<br><br>
	
		  <strong>Apakah pembatalan bisa online?</strong><br>
		  Iya, melalui website kita.
		`, "bot");
			showBackButton();
		}

		// === Tombol Kembali ke Menu Utama ===
		function showBackButton() {
			setTimeout(() => {
				appendMessage(`
			<a href="javascript:showMainMenu()" class="action-btn">← Kembali ke Menu</a>
		  `, "bot");
			}, 500);
		}

		// === Mulai Pemesanan ===
		function startOrder() {
			if (!isLoggedIn) {
				appendMessage("Anda belum bisa memesan.<br>Silakan login terlebih dahulu untuk melanjutkan.", "bot");
				setTimeout(() => {
					appendMessage(`
				<a href="login.php" class="action-btn" target="_blank">
					<i class="fas fa-sign-in-alt"></i> Login Sekarang
				</a>
			`, "bot");
				}, 500);
				return;
			}

			// Jika sudah login, lanjutkan seperti biasa
			appendMessage("Anda ingin memesan paket wisata", "user");
			appendMessage("Silakan pilih paket:", "bot");
			packages.forEach(pkg => {
				const el = `<div class="faq-btn" onclick="selectPackage(${pkg.id}, ${pkg.price})">
			<strong>${pkg.name}</strong><br>
			${pkg.destination} | Rp ${pkg.price.toLocaleString()}
		  </div>`;
				appendMessage(el, "bot");
			});
		}

		function selectPackage(id, price) {
			selectedPackageId = id;
			selectedPrice = price;
			appendMessage(`Anda pilih paket ini. Masukkan jumlah pesanan:`, "bot");
			document.querySelector('.chatbot-input').style.display = 'flex';
			document.getElementById('user-input').focus();
		}

		// === Bantuan Lain ===
		function showHelp() {
			appendMessage("Bantuan Lain", "user");
			appendMessage(`
		  Anda bisa:<br>
		  • Klik kategori di atas untuk info lebih<br>
		  • Klik "Pesan Sekarang" untuk mulai<br>
		  • Hubungi kami via WhatsApp jika butuh bantuan langsung: 
		  <a href="https://wa.me/6287855658800" target="_blank" class="action-btn">Chat via WA</a>
		`, "bot");
			showBackButton();
		}
		function sendMessage() {
			const input = document.getElementById("user-input");
			const text = input.value.trim();
			if (text === '') return;

			appendMessage(text, "user");

			// Cek jika sedang dalam proses pilih jumlah
			if (selectedPackageId && /^\d+$/.test(text)) {
				const qty = parseInt(text);
				if (qty < 1) {
					appendMessage("Jumlah minimal 1.", "bot");
				} else {
					const total = selectedPrice * qty;
					const link = `<a href="order.php?id=${selectedPackageId}&qty=${qty}" target="_blank" class="action-btn">Lanjut ke Pembayaran (Total: Rp ${total.toLocaleString()})</a>`;
					appendMessage(`Jumlah: ${qty}<br>Total: Rp ${total.toLocaleString('id-ID')}<br><br>${link}`, "bot");
					selectedPackageId = null;
					document.querySelector('.chatbot-input').style.display = 'none';
				}
				input.value = '';
				return;
			}

			// Jika bukan input angka, proses sebagai pertanyaan
			handleResponse(text);
			input.value = '';
		}

		// Fungsi handleKeyPress hanya memanggil sendMessage saat Enter
		function handleKeyPress(e) {
			if (e.key === "Enter") {
				sendMessage();
			}
		}
		function handleResponse(text) {
			text = text.toLowerCase();
			const currentHour = new Date().getHours();
			let greeting = "";

			if (currentHour < 12) {
				greeting = "Pagi"; // 00.00 - 11.59
			} else if (currentHour < 15) {
				greeting = "Siang"; // 12.00 - 14.59
			} else if (currentHour < 18) {
				greeting = "Sore"; // 15.00 - 17.59
			} else {
				greeting = "Malam"; // 18.00 - 23.59
			}
			// --- A. PERTANYAAN SEPUTAR PEMESANAN ---
			if (text.includes("cara pesan") || text.includes("cara memesan") || text.includes("pemesanan") || text.includes("pesan") || text.includes("order")) {
				appendMessage(`
			<strong>Cara Pemesanan:</strong><br>
			1. Login ke akun Anda.<br>
			2. Klik "Pesan Sekarang" di menu atau ketik 'pesan'.<br>
			3. Pilih paket & masukkan jumlah.<br>
			4. Lanjut ke pembayaran.<br><br>
			<a href="javascript:startOrder()" class="action-btn">👉 Pesan Sekarang</a>
		`, "bot");
			}

			// Sub-pertanyaan A
			else if (text.includes("pesan lewat wa") || text.includes("pesan via whatsapp")) {
				appendMessage("Maaf, pemesanan tidak bisa dilakukan via WhatsApp. Kami hanya menyediakan WhatsApp untuk bantuan darurat atau konfirmasi penting.", "bot");
			}
			else if (text.includes("harus datang ke kantor") || text.includes("datang ke kantor")) {
				appendMessage("Tidak perlu datang ke kantor. Semua pemesanan dilakukan online melalui website ini.", "bot");
			}
			else if (text.includes("pemesanan online") || text.includes("bisa pesan online")) {
				appendMessage("Ya, Anda bisa memesan secara online melalui fitur chatbot ini atau di halaman Destinasi.", "bot");
			}
			else if (text.includes("konfirmasi pesanan") || text.includes("lama konfirmasi")) {
				appendMessage("Pemesanan akan dikonfirmasi maksimal 24 jam setelah Anda melakukan pembayaran.", "bot");
			}
			else if (text.includes("pesan untuk orang lain") || text.includes("pesan untuk teman")) {
				appendMessage("Bisa! Saat memesan, Anda bisa memilih jumlah pesanan sesuai kebutuhan.", "bot");
			}
			else if (text.includes("pesan lebih dari satu paket")) {
				appendMessage("Maaf, Anda hanya bisa memesan satu paket per transaksi karena ketersediaan paket yang terbatas.", "bot");
			}

			// --- B. PERTANYAAN SEPUTAR KETERSEDIAAN ---
			else if (text.includes("ketersediaan") || text.includes("ada paket ke") || text.includes("paket ke ") || text.includes("tersedia") || text.includes("jadwal keberangkatan")) {
				appendMessage(`
			Untuk melihat paket yang tersedia dan jadwal keberangkatan terdekat, silakan mulai pemesanan.<br><br>
			<a href="javascript:startOrder()" class="action-btn">🔍 Cek Paket Tersedia</a>
		`, "bot");
			}

			// Sub-pertanyaan B
			else if (text.includes("jumlah peserta") || text.includes("peserta maksimal")) {
				appendMessage(`
			Jumlah peserta maksimal tiap paket berbeda-beda. Untuk detailnya, silakan pilih paket terlebih dahulu.<br><br>
			<a href="javascript:startOrder()" class="action-btn">👁️ Lihat Detail Paket</a>
		`, "bot");
			}
			else if (text.includes("cek kursi") || text.includes("cek ketersediaan kursi")) {
				appendMessage("Anda bisa cek ketersediaan langsung di website dengan memulai pemesanan.", "bot");
			}
			else if (text.includes("paket private") || text.includes("paket pribadi")) {
				appendMessage("Maaf, saat ini kami belum menyediakan paket private.", "bot");
			}
			else if (text.includes("request tanggal") || text.includes("ganti tanggal")) {
				appendMessage("Maaf, Anda tidak bisa request tanggal keberangkatan sendiri. Hanya tersedia jadwal yang sudah ditentukan.", "bot");
			}
			else if (text.includes("paket keluarga") || text.includes("paket rombongan")) {
				appendMessage("Beberapa paket tersedia untuk keluarga atau rombongan. Silakan cek saat memilih paket.", "bot");
			}

			// --- C. PERTANYAAN SEPUTAR PEMBAYARAN ---
			else if (text.includes("pembayaran") || text.includes("bayar") || text.includes("metode bayar")) {
				appendMessage(`
			<strong>Metode Pembayaran:</strong><br>
			• Hanya transfer BCA<br>
			• Batas waktu: 24 jam setelah pesan<br>
			• Kirim bukti transfer di halaman pemesanan.<br><br>
			Setelah pembayaran dikonfirmasi, Anda akan menerima resi via email dan WhatsApp.
		`, "bot");
			}

			// Sub-pertanyaan C
			else if (text.includes("ovo") || text.includes("dana") || text.includes("gopay") || text.includes("ewallet")) {
				appendMessage("Maaf, kami tidak menerima pembayaran via OVO, DANA, atau Gopay.", "bot");
			}
			else if (text.includes("cicilan") || text.includes("dp") || text.includes("uang muka")) {
				appendMessage("Maaf, pembayaran harus lunas saat memesan. Tidak tersedia cicilan atau DP.", "bot");
			}
			else if (text.includes("bayar di tempat") || text.includes("bayar setelah sampai")) {
				appendMessage("Maaf, pembayaran harus dilakukan sebelum keberangkatan, tidak bisa bayar di tempat.", "bot");
			}
			else if (text.includes("batas waktu bayar") || text.includes("waktu pembayaran")) {
				appendMessage("Anda memiliki waktu 24 jam untuk menyelesaikan pembayaran setelah melakukan pemesanan.", "bot");
			}
			else if (text.includes("konfirmasi pembayaran") || text.includes("sudah bayar")) {
				appendMessage("Setelah transfer, kirimkan bukti pembayaran di halaman pemesanan Anda. Admin akan memproses dalam 24 jam.", "bot");
			}
			else if (text.includes("bukti pembayaran") || text.includes("resi") || text.includes("struk")) {
				appendMessage("Resi akan dikirim via email dan WhatsApp maksimal 3 hari setelah pembayaran dikonfirmasi.", "bot");
			}

			// --- D. PERTANYAAN SEPUTAR REFUND & CANCEL ---
			else if (text.includes("refund") || text.includes("cancel") || text.includes("batalkan") || text.includes("pembatalan")) {
				appendMessage(`
			<strong>Pembatalan & Refund:</strong><br>
			• Bisa dibatalkan di halaman <strong>My Orders</strong><br>
			• Tidak ada refund karena toleransi 24 jam sebelum konfirmasi<br>
			• Pembatalan bisa dilakukan online<br><br>
			<a href="my-orders.php" class="action-btn" target="_blank">🗑️ Lihat Pesanan Saya</a>
		`, "bot");
			}

			// Sub-pertanyaan D
			else if (text.includes("cara batalkan") || text.includes("cara cancel")) {
				appendMessage("Anda bisa membatalkan pesanan di halaman 'My Orders' selama belum dikonfirmasi admin.", "bot");
			}
			else if (text.includes("refund diterima") || text.includes("uang kembali")) {
				appendMessage("Maaf, kami tidak memberikan refund. Semua pesanan sudah masuk toleransi 24 jam sebelum dikonfirmasi.", "bot");
			}
			else if (text.includes("pembatalan online")) {
				appendMessage("Ya, Anda bisa membatalkan pesanan secara online melalui halaman 'My Orders'.", "bot");
			}

			// --- LOGIN ---
			else if (text.includes("login") || text.includes("masuk") || text.includes("akun")) {
				if (isLoggedIn) {
					appendMessage("Anda sudah login. Silakan lanjutkan pemesanan.", "bot");
				} else {
					appendMessage(`
				Silakan login terlebih dahulu untuk memesan.<br><br>
				<a href="login.php" class="action-btn" target="_blank">🔐 Login Sekarang</a>
			`, "bot");
				}
			}

			// --- KONTAK ---
			else if (text.includes("kontak") || text.includes("wa") || text.includes("whatsapp") || text.includes("hubungi")) {
				appendMessage(`
			Hubungi kami via WhatsApp:<br>
			<a href="https://wa.me/6287855658800" class="action-btn" target="_blank">
				<i class="fab fa-whatsapp"></i> Chat via WA
			</a>
		`, "bot");
			}

			// --- BANTUAN UMUM ---
			else if (text.includes("bantuan") || text.includes("help") || text.includes("info")) {
				appendMessage(`
			Saya bisa bantu dengan:<br>
			• Pemesanan<br>
			• Ketersediaan Paket<br>
			• Pembayaran<br>
			• Refund & Cancel<br><br>
			Coba tanyakan: <em>cara pesan, pembayaran, refund, dll</em>
		`, "bot");
			}

			// --- SALAM ---
			else if (text.includes("halo") || text.includes("hai") || text.includes("hello") || text.includes("hi")) {
				appendMessage("Halo! Ada yang bisa saya bantu? 😊", "bot");
			}
			else if (text.includes("pagi")) {
				appendMessage("Halo! Selamat pagi, ada yang bisa saya bantu? 😊", "bot");
			}
			else if (text.includes("siang")) {
				appendMessage("Halo! Selamat siang, ada yang bisa saya bantu? 😊", "bot");
			}
			else if (text.includes("sore")) {
				appendMessage("Halo! Selamat sore, ada yang bisa saya bantu? 😊", "bot");
			}
			else if (text.includes("malam")) {
				appendMessage("Halo! Selamat malam, ada yang bisa saya bantu? 😊", "bot");
			}

			// --- DEFAULT JIKA TIDAK DIMENGERTI ---
			else {
				appendMessage(`
			Maaf, saya tidak mengerti.<br><br>
			Coba tanyakan:<br>
			• <em>Cara pesan</em><br>
			• <em>Pembayaran</em><br>
			• <em>Refund</em><br>
			• <em>Login</em><br><br>
			Atau kembali ke menu: <a href="javascript:showMainMenu()" class="action-btn">🏠 Menu Utama</a>
		`, "bot");
			}
		}
	</script>