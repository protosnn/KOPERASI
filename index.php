<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Koperasi - Login</title>
  <link rel="shortcut icon" type="image/png" href="./template/assets/images/logos/favicon.svg" />
  <link rel="stylesheet" href="./template/assets/libs/owl.carousel/dist/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="./template/assets/libs/aos-master/dist/aos.css">
  <link rel="stylesheet" href="./template/assets/css/styles.css" />
</head>

<body>

  <!--  Page Wrapper -->
  <div class="page-wrapper overflow-hidden">

    <!--  Get in touch Section -->
    <section
      class="bg-light-gray border-top border-primary border-4 d-flex align-items-center justify-content-center min-vh-100">
      <div class="container py-3">
        <div class="sign-in card mx-auto shadow-lg">
          <div class="card-body py-8 px-lg-5">
            <a href="index.php" class="mb-8 hstack justify-content-center">
              <img src="./template/assets/images/logos/logo-dark.svg" alt="logo-dark" class="img-fluid">
            </a>
            <form action="proses_login.php" method="POST" class="d-flex flex-column gap-3">
              <div>
                <input type="text" name="username" class="form-control border-bottom" placeholder="Username" required>
              </div>
              <div>
                <input type="password" name="password" class="form-control border-bottom" placeholder="Password" required>
              </div>

              <button type="submit" class="btn btn-dark w-100 justify-content-center py-2 fw-medium my-7 fs-4 lh-lg">
                Log in
              </button>
            </form>
            <a class="text-center mb-1 d-block text-dark fw-medium" href="#">Forget Password?</a>
            <p class="mb-0 fw-medium text-center">Not a member yet? <a class="text-dark" href="sign-up.html">Regristasi</a>
            </p>
          </div>
        </div>
      </div>
    </section>

  </div>

  <div class="get-template hstack gap-2">
    
    <button class="btn bg-primary p-2 round-52 rounded-circle hstack justify-content-center flex-shrink-0"
      id="scrollToTopBtn">
      <iconify-icon icon="lucide:arrow-up" class="fs-7 text-dark"></iconify-icon>
    </button>
  </div>


  <script src="./template/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="./template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./template/assets/libs/owl.carousel/dist/owl.carousel.min.js"></script>
  <script src="./template/assets/libs/aos-master/dist/aos.js"></script>
  <script src="./template/assets/js/custom.js"></script>
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>