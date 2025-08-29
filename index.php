<?php
session_start();
include 'dialogflow.php';
include 'config.php'; // untuk koneksi database (opsional untuk cek user)
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<link rel="stylesheet" href="fontawesome-free-6.2.1-web/css/all.css">

	<title>LIFE TOURS</title>
	<meta content="" name="description">
	<meta content="" name="keywords">


	<link href="lifetour.jpg" rel="icon">
	<link
		href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
		rel="stylesheet">
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Bootstrap Icons (untuk ikon hamburger) -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
	<link href="assets/vendor/aos/aos.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
	<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
	<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
	<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
	<link href="style.css" rel="stylesheet">
	<style>
		.chatbot-input {
			display: flex;
			align-items: center;
			padding: 10px 15px;
			background-color: #f8f9fa;
			border-top: 1px solid #dee2e6;
			gap: 10px;
		}

		.chatbot-input input {
			flex: 1;
			padding: 10px 15px;
			border: 1px solid #ced4da;
			border-radius: 25px;
			outline: none;
			font-size: 14px;
			box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
			transition: border-color 0.3s ease, box-shadow 0.3s ease;
		}

		.chatbot-input input:focus {
			border-color: #007bff;
			box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
		}

		.chatbot-input .send-btn {
			display: flex;
			align-items: center;
			justify-content: center;
			width: 40px;
			height: 40px;
			background-color: #007bff;
			border: none;
			color: white;
			border-radius: 50%;
			cursor: pointer;
			transition: background-color 0.3s ease, transform 0.2s ease;
			font-size: 16px;
		}

		.chatbot-input .send-btn:hover {
			background-color: #0056b3;
			transform: scale(1.05);
		}

		.chatbot-input .send-btn:active {
			transform: scale(0.95);
		}

		#chatbot-button {
			position: fixed;
			bottom: 20px;
			right: 20px;
			cursor: pointer;
			z-index: 1000;
			border-radius: 50%;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		}

		#chatbot-frame {
			position: fixed;
			bottom: 90px;
			right: 20px;
			width: 350px;
			height: 500px;
			border-radius: 12px;
			background: white;
			box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
			z-index: 1000;
			display: flex;
			flex-direction: column;
			overflow: hidden;
		}

		.chatbot-header {
			background: #007bff;
			color: white;
			padding: 15px;
			font-weight: bold;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.close-btn {
			font-size: 24px;
			cursor: pointer;
		}

		.chatbot-messages {
			flex: 1;
			padding: 15px;
			overflow-y: auto;
			background-color: #f1f1f1;
			display: flex;
			flex-direction: column;
			gap: 10px;
		}

		.message {
			padding: 10px 15px;
			border-radius: 15px;
			max-width: 80%;
			line-height: 1.4;
		}

		.bot {
			background-color: #e9ecef;
			align-self: flex-start;
			border-bottom-left-radius: 5px;
		}

		.user {
			background-color: #007bff;
			color: white;
			align-self: flex-end;
			border-bottom-right-radius: 5px;
		}

		.faq-btn {
			cursor: pointer;
			padding: 10px;
			margin: 5px 0;
			background-color: #e9ecef;
			border-radius: 8px;
			font-size: 14px;
			font-weight: 500;
		}

		.faq-btn:hover {
			background-color: #dde2e6;
		}

		.action-btn {
			display: inline-block;
			margin: 5px 0;
			padding: 8px 12px;
			background-color: #007bff;
			color: white;
			border-radius: 8px;
			cursor: pointer;
			font-size: 14px;
			text-decoration: none;
			text-align: center;
		}

		.action-btn:hover {
			background-color: #0056b3;
		}
	</style>
</head>

<body>


	<section id="topbar" class="d-flex align-items-center">
		<div class="container d-flex justify-content-center justify-content-md-between">
			<div class="contact-info d-flex align-items-center">
				<i class="bi bi-envelope d-flex align-items-center"><a
						href="mailto:contact@example.com">lifetoursandtravel@gmail.com</a></i>
				<i class="bi bi-phone d-flex align-items-center ms-4"><span>0878-5565-8800</span></i>
			</div>
	</section>

	<header id="header" class="d-flex align-items-center">
		<div class="container d-flex align-items-center justify-content-between">
			<div class="logo d-flex align-items-center">
				<img src="lifetour.jpg" alt="Logo" width="40" height="40" class="me-2">
				<h1><a href="login.php" class="text-decoration-none">LIFE TOURS</a></h1>
			</div>

			<nav id="navbar" class="navbar">
				<ul>
					<li><a class="nav-link scrollto active" href="#hero">Home</a></li>
					<li><a class="nav-link scrollto" href="#about">About Life Tours</a></li>
					<li><a class="nav-link scrollto" href="#Destiny">Destinations</a></li>
					<li><a class="nav-link scrollto" href="#testimonials">Testimonials</a></li>
					<li><a class="nav-link scrollto" href="#contact">Contact</a></li>

					<?php if (isset($_SESSION['user_id'])): ?>
						<!-- Jika sudah login -->
						<li><a class="nav-link scrollto" href="my-orders.php">My Orders</a></li>
						<li>
							<a class="logout" href="logout.php" onclick="return confirm('Apakah anda yakin akan logout?')">
								Keluar
							</a>
						</li>
					<?php else: ?>
						<!-- Jika belum login -->
						<li><a class="nav-link scrollto" href="login.php">Login</a></li>
					<?php endif; ?>
				</ul>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav>
		</div>
	</header>


	<section id="hero" class="d-flex flex-column justify-content-center align-items-center">
		<div class="container" data-aos="fade-in">
			<h1>Welcome to Life Tours</h1>
			<h2>Your Trusted Destination for Travel Ticket Booking</h2>
			<div class="d-flex align-items-center">
				<i class="bx bxs-right-arrow-alt get-started-icon"></i>
				<a href="#about" class="btn-get-started scrollto">Book Now</a>
			</div>
		</div>
	</section>

	<main id="main">


		<section id="why-us" class="why-us">
			<div class="container">

				<div class="row">
					<div class="col-xl-4 col-lg-5" data-aos="fade-up">
						<div class="content text-bg-dark">
							<h3>Why Life Tours ?</h3>
							<p>
								At Life Tour and Travel, we do more than just book tickets — we create meaningful travel
								experiences.
							</p>
							<div class="text-center">
								<a href="#" class="more-btn">Learn More <i class="bx bx-chevron-right"></i></a>
							</div>
						</div>
					</div>
					<div class="col-xl-8 col-lg-7 d-flex">
						<div class="icon-boxes d-flex flex-column justify-content-center">
							<div class="row">
								<div class="col-xl-4 d-flex align-items-stretch" data-aos="fade-up"
									data-aos-delay="100">
									<div class="icon-box mt-4 mt-xl-0">
										<i class="fa-sharp fa-solid fa-globe"></i>
										<h4>Our Vision</h4>
										<p>To become the most trusted and customer-centric travel partner, delivering
											seamless journeys and
											unforgettable travel experiences across Indonesia and beyond</p>
									</div>
								</div>
								<div class="col-xl-4 d-flex align-items-stretch" data-aos="fade-up"
									data-aos-delay="200">
									<div class="icon-box mt-4 mt-xl-0 text-bg-dark">
										<i class="fa-regular fa-folder-open"></i>
										<h4>Background</h4>
										<p>Life Tour and Travel was founded with a mission to simplify and enhance the
											way people travel.
										</p>
									</div>
								</div>
								<div class="col-xl-4 d-flex align-items-stretch" data-aos="fade-up"
									data-aos-delay="300">
									<div class="icon-box mt-4 mt-xl-0">
										<i class="fa-solid fa-tag"></i>
										<h4>Our Moto</h4>
										<p>We believe every trip should be meaningful — whether for business, family, or
											adventure. With
											every destination, we bring dedication, care, and heart.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</section>
		<section id="about" class="about section-bg">
			<div class="container">

				<div class="row">
					<div class="col-xl-5 col-lg-6 video-box d-flex justify-content-center align-items-stretch position-relative"
						data-aos="fade-right">
						<a href="https://www.youtube.com/watch?v=aqcjCgJhasE" class="glightbox play-btn mb-4"></a>
					</div>

					<div
						class="col-xl-7 col-lg-6 icon-boxes d-flex flex-column align-items-stretch justify-content-center py-5 px-lg-5">
						<h4 data-aos="fade-up">About Us</h4>
						<h3 data-aos="fade-up">Life tours and travel</h3>
						<p data-aos="fade-up">At Life Tours and Travel, we believe that innovation is a journey—just
							like life
							itself. Founded by four passionate and visionary individuals, our mission goes beyond
							travel. We're
							committed to making impactful creations that improve how people live, move, and connect with
							the world.
						</p>

						<div class="icon-box" data-aos="fade-up">
							<div class="icon"><i class="bx bx-fingerprint"></i></div>
							<h4 class="title"><a href="">Personalized Travel Services</a></h4>
							<p class="description">We tailor every trip to match your needs, preferences, and
								dreams—ensuring every
								journey feels uniquely yours.</p>
						</div>

						<div class="icon-box" data-aos="fade-up" data-aos-delay="100">
							<div class="icon"><i class="bx bx-gift"></i></div>
							<h4 class="title"><a href="">Affordable & Flexible Booking</a></h4>
							<p class="description">We believe that traveling should be accessible to everyone. Our
								packages are
								affordable, with flexible options to suit every budget and schedule.</p>
						</div>

						<div class="icon-box" data-aos="fade-up" data-aos-delay="200">
							<div class="icon"><i class="bx bx-atom"></i></div>
							<h4 class="title"><a href="">Customer-Centered Support</a></h4>
							<p class="description">Whether you're planning a holiday or need help during your trip, our
								friendly team
								is always ready to assist you with care and professionalism.</p>
						</div>

					</div>
				</div>

			</div>
		</section>

		<section id="Destiny" class="portfolio">
			<div class="container">

				<div class="section-title">
					<h2 data-aos="fade-up">Travel Package</h2>
					<p data-aos="fade-up">Here are our latest travel packages.</p>
				</div>

				<div class="row" data-aos="fade-up" data-aos-delay="100">
					<div class="col-lg-12 d-flex justify-content-center">
						<ul id="portfolio-flters">
							<li data-filter="*" class="filter-active">All</li>
							<li data-filter=".filter-web">Available</li>
						</ul>
					</div>
				</div>

				<!-- Ganti bagian portfolio-container -->
				<div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">
					<?php
					include 'config.php';
					$result = $conn->query("SELECT * FROM packages WHERE status = 'Available' ORDER BY id");
					while ($pkg = $result->fetch_assoc()):
						?>
						<div class="col-lg-4 col-md-6 portfolio-item filter-web">
							<img src="assets/img/<?= htmlspecialchars($pkg['image']) ?>" class="img-fluid"
								alt="<?= $pkg['package_name'] ?>">
							<div class="portfolio-info">
								<h4><?= htmlspecialchars($pkg['package_name']) ?></h4>
								<p><?= htmlspecialchars($pkg['destination']) ?> | Rp
									<?= number_format($pkg['price'], 0, ',', '.') ?>
								</p>

								<a href="assets/img/<?= htmlspecialchars($pkg['image']) ?>" data-gallery="portfolioGallery"
									class="portfolio-lightbox preview-link"
									title="<?= htmlspecialchars($pkg['package_name']) ?>">
									<i class="bx bx-plus"></i>
								</a>

								<a href="detail-paket.php?id=<?= $pkg['id'] ?>" class="btn btn-sm btn-outline-primary mt-2">
									<i class="bi bi-info-circle"></i> Lihat Detail
								</a>
							</div>
						</div>
					<?php endwhile; ?>
				</div>

			</div>
		</section>

		<div class="section-title">
			<h2 data-aos="fade-up">Testimoni</h2>
			<p data-aos="fade-up">
				What they said about us ?
			</p>
		</div>
		<section id="testimonials" class="testimonials">
			<div class="container position-relative" data-aos="fade-up">

				<div class="testimonials-slider swiper" data-aos="fade-up" data-aos-delay="100">
					<div class="swiper-wrapper">

						<div class="swiper-slide">
							<div class="testimonial-item">
								<img src="testi1.jpg" class="testimonial-img" alt="">
								<h3>Humam Akmal Juanda</h3>
								<h4>Funfact</h4>
								<p>
									<i class="bx bxs-quote-alt-left quote-icon-left"></i>
									Sangat memuaskan.
									<i class="bx bxs-quote-alt-right quote-icon-right"></i>
								</p>
							</div>
						</div>

						<div class="swiper-slide">
							<div class="testimonial-item">
								<img src="testi2.jpg" class="testimonial-img" alt="">
								<h3>Salsabila Aulia</h3>
								<h4>Traveler</h4>
								<p>
									<i class="bx bxs-quote-alt-left quote-icon-left"></i>
									Traveling membuatku lebih mengenal dunia.
									<i class="bx bxs-quote-alt-right quote-icon-right"></i>
								</p>
							</div>
						</div>

						<div class="swiper-slide">
							<div class="testimonial-item">
								<img src="testi3.jpg" class="testimonial-img" alt="">
								<h3>Rizky Ramadhan</h3>
								<h4>Customer</h4>
								<p>
									<i class="bx bxs-quote-alt-left quote-icon-left"></i>
									Pelayanan sangat memuaskan dan cepat.
									<i class="bx bxs-quote-alt-right quote-icon-right"></i>
								</p>
							</div>
						</div>

					</div>

					<div class="swiper-pagination"></div>
				</div>

			</div>
		</section>
		</div>

		<section id="contact" class="team section-bg">
			<div class="container text-center">
				<div class="section-title">
					<h2 data-aos="fade-up">Contact Us</h2>
					<p data-aos="fade-up">
						Here are all of our contact details.
					</p>
				</div>

				<div class="row justify-content-center">
					<div class="col-lg-3 col-md-6 d-flex align-items-stretch mx-auto" data-aos="fade-up"
						data-aos-delay="300">
						<div class="member">
							<div class="member-img">
								<img src="kontaktour.jpg" class="img-fluid" alt="">
								<div class="social">
									<a href=""><i class="bi bi-twitter"></i></a>
									<a href=""><i class="bi bi-facebook"></i></a>
									<a href="https://www.instagram.com/lifetoursandtravel/" target="_blank"><i
											class="bi bi-instagram"></i></a>
									<a href=""><i class="bi bi-linkedin"></i></a>
								</div>
							</div>
							<div class="member-info">
								<h4>lifetoursandtravel@gmail.com</h4>
								<span>0878-5565-8800</span>
							</div>
						</div>
					</div>
				</div>

			</div>
		</section>
	</main>
    <div id="chatbot-button" onclick="toggleChatbot()">
		<img src="chatour.jpg" alt="Chatbot" width="60" height="60">
	</div>

	<!-- Chatbot Frame -->
	<div id="chatbot-frame" style="display: none;">
		<div class="chatbot-header">
			<span>Asisten LIFE TOURS</span>
			<span class="close-btn" onclick="toggleChatbot()">&times;</span>
		</div>
		<div id="chatbot-messages" class="chatbot-messages">
			<div class="message bot">
				Halo! Saya asisten virtual LIFE TOURS. 😊<br><br>
				<strong>Apa yang bisa saya bantu?</strong>
			</div>
			<!-- Opsi FAQ akan muncul di sini -->
		</div>
		<div class="chatbot-input" style="display: none;">
			<input type="text" id="user-input" placeholder="Ketik pesan..." onkeypress="handleKeyPress(event)">
			<button onclick="sendMessage()" class="send-btn">
				<i class="fas fa-paper-plane"></i>
			</button>
		</div>
	</div>
    <?php include 'assets/js/main2.php';?>
	<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
			class="bi bi-arrow-up-short"></i></a>

	<script src="assets/vendor/aos/aos.js"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
	<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
	<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
	<script src="assets/vendor/php-email-form/validate.js"></script>
	<script src="assets/js/main.js"></script>
</body>

</html>