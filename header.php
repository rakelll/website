<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="style.css">

    <!-- ===== Boxicons CSS ===== -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/4.0.20/fullpage.min.css" integrity="sha512-otONSJGv1l0LjJXcgyqi2MeKXtnthkiXAleeQjFJOKYcVD5u+mOuPb7xCnlsb74Ld+N0ONbP7KYRmW2/OWrvaA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ===== Google Font Import - Poppins ===== */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');

*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
    transition: all 0.4s ease;;
}


/* ===== Colours ===== */
:root{
    --body-color: #E4E9F7;
    --nav-color: #4070F4;
    --side-nav: #010718;
    --text-color: #FFF;
    --search-bar: #F2F2F2;
    --search-text: #010718;
}

body{
    height: 100vh;
    background-color: var(--body-color);
}

body.dark{
    --body-color: #18191A;
    --nav-color: #242526;
    --side-nav: #242526;
    --text-color: #CCC;
    --search-bar: #242526;
}

nav{
    position: fixed;
    top: 0;
    left: 0;
    height: 70px;
    width: 100%;
    background-color: var(--nav-color);
    z-index: 100;
}

body.dark nav{
    border: 1px solid #393838;

}

nav .nav-bar{
    position: relative;
    height: 100%;
    max-width: 1000px;
    width: 100%;
    background-color: var(--nav-color);
    margin: 0 auto;
    padding: 0 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

nav .nav-bar .sidebarOpen{
    color: var(--text-color);
    font-size: 25px;
    padding: 5px;
    cursor: pointer;
    display: none;
}

nav .nav-bar .logo a{
    font-size: 25px;
    font-weight: 500;
    color: var(--text-color);
    text-decoration: none;
}

.menu .logo-toggle{
    display: none;
}

.nav-bar .nav-links{
    display: flex;
    align-items: center;
}

.nav-bar .nav-links li{
    margin: auto;
    list-style: none;
}

.nav-links li a{
    position: relative;
    font-size: 17px;
    font-weight: 400;
    color: var(--text-color);
    text-decoration: none;
    padding: 10px;
}

.nav-links li a::before{
    content: '';
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%);
    height: 6px;
    width: 6px;
    border-radius: 50%;
    background-color: var(--text-color);
    opacity: 0;
    transition: all 0.3s ease;
}

.nav-links li:hover a::before{
    opacity: 1;
}

.nav-bar .darkLight-searchBox{
    display: flex;
    align-items: center;
}

.darkLight-searchBox .dark-light,
.darkLight-searchBox .searchToggle{
    height: 40px;
    width: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 5px;
}

.dark-light i,
.searchToggle i{
    position: absolute;
    color: var(--text-color);
    font-size: 22px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.dark-light i.sun{
    opacity: 0;
    pointer-events: none;
}

.dark-light.active i.sun{
    opacity: 1;
    pointer-events: auto;
}

.dark-light.active i.moon{
    opacity: 0;
    pointer-events: none;
}

.searchToggle i.cancel{
    opacity: 0;
    pointer-events: none;
}

.searchToggle.active i.cancel{
    opacity: 1;
    pointer-events: auto;
}

.searchToggle.active i.search{
    opacity: 0;
    pointer-events: none;
}

.searchBox{
    position: relative;
}

.searchBox .search-field{
    position: absolute;
    bottom: -85px;
    right: 5px;
    height: 50px;
    width: 300px;
    display: flex;
    align-items: center;
    background-color: var(--nav-color);
    padding: 3px;
    border-radius: 6px;
    box-shadow: 0 5px 5px rgba(0, 0, 0, 0.1);
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
}

.searchToggle.active ~ .search-field{
    bottom: -74px;
    opacity: 1;
    pointer-events: auto;
}

.search-field::before{
    content: '';
    position: absolute;
    right: 14px;
    top: -4px;
    height: 12px;
    width: 12px;
    background-color: var(--nav-color);
    transform: rotate(-45deg);
    z-index: -1;
}

.search-field input{
    height: 100%;
    width: 100%;
    padding: 0 45px 0 15px;
    outline: none;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 400;
    color: var(--search-text);
    background-color: var(--search-bar);
}

body.dark .search-field input{
    color: var(--text-color);
}

.search-field i{
    position: absolute;
    color: var(--nav-color);
    right: 15px;
    font-size: 22px;
    cursor: pointer;
}

body.dark .search-field i{
    color: var(--text-color);
}

@media (max-width: 790px) {
    nav .nav-bar .sidebarOpen{
        display: block;
    }

    .menu{
        position: fixed;
        height: 100%;
        width: 320px;
        left: -100%;
        top: 0;
        padding: 20px;
        background-color: var(--side-nav);
        z-index: 100;
        transition: all 0.4s ease;
    }

    nav.active .menu{
        left: -0%;
    }

    nav.active .nav-bar .navLogo a{
        opacity: 0;
        transition: all 0.3s ease;
    }

    .menu .logo-toggle{
        display: block;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .logo-toggle .siderbarClose{
        color: var(--text-color);
        font-size: 24px;
        cursor: pointer;
    }

    .nav-bar .nav-links{
        flex-direction: column;
        padding-top: 30px;
    }

    .nav-links li a{
        display: block;
        margin-top: 20px;
    }
}

.carousel-item img {
  width: 100%;
  height: auto; /* Adjust the height as needed */
  object-fit: cover;
}

  .carousel-item {
    position: relative;
  }

  .carousel-item .content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: white;
    z-index: 100;
  }

  .carousel-item h2 {
    margin-bottom: 10px;
    color: black;
    text-align: center;
    font-weight: bold;
    font-family: Arial, sans-serif;
  }

  .carousel-item p {
    margin-bottom: 0;
    color: black;
    text-align: center;
    font-family: Arial, sans-serif;
  }

  .carousel-control-prev,
  .carousel-control-next {
    width: 10%;
    position: center;
  }

  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    background-color: rgba(0, 0, 0, 0.5);
  }

  .carousel-indicators {
    bottom: 10px;
    display: flex;
    justify-content: center;
  }

  .carousel-indicators li {
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    width: 12px;
    height: 12px;
    margin: 0 5px;
    cursor: pointer;
  }

  .carousel-indicators .active {
    background-color: rgba(0, 0, 0, 0.9);
  }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
    <nav>
        <div class="nav-bar">
            <i class='bx bx-menu sidebarOpen' ></i>
            <span class="logo navLogo"><a href="#home-section">P.G.D</a></span>

            <div class="menu">
                <div class="logo-toggle">
                    <span class="logo"><a href="#home-section">P.G.D</a></span>
                    <i class='bx bx-x siderbarClose'></i>
                </div>

                <ul class="nav-links">
					<li><a href="#home-section" class="active">Home</a></li>
					<li><a href="#about-section">About Us</a></li>
					<li><a href="#services-section">Services</a></li>
					<li><a href="#contact-section">Contact Us</a></li>
					<li><a href="login.php">Client Services</a></li>
                </ul>
        </div>
    </nav>
    <div class="carousel-container" id="home-section">
  <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="3000">
    <ol class="carousel-indicators">
      <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
      <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
      <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="d-block w-100" src="images/slider/slider-01.jpeg" alt="First slide">
        <!-- <div class="content"> -->
          <!--   <h2>PREMIUM GOLDEN DATA</h2> -->
          <!--   <p>We empower customers to innovate, differentiate & win in the digital worl</p> -->
          <!-- </div> -->
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="images/slider/slider-02.jpeg" alt="Second slide">
        <!-- <div class="content"> -->
          <!--   <h2>P.G.D.</h2> -->
          <!--   <p>We will add value for our customers by anticipating and identifying<br> their needs and providing cost-effective solutions </p> -->
          <!-- </div> -->
      </div>
      <div class="carousel-item">
        <img class="d-block w-100" src="images/slider/slider-03.jpeg" alt="Third slide">
        <!-- <div class="content"> -->
          <!--   <h2>Golden Data System</h2> -->
          <!--   <p>GDS designs, develops and enhances business software systems.<br> We specialize in MYSQL-based systems, with consultants who bring technical expertise to your team </p> -->
          <!-- </div> -->
      </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>
<script>

const body = document.querySelector("body"),
      nav = document.querySelector("nav"),
      modeToggle = document.querySelector(".dark-light"),
      searchToggle = document.querySelector(".searchToggle"),
      sidebarOpen = document.querySelector(".sidebarOpen"),
      siderbarClose = document.querySelector(".siderbarClose");

      let getMode = localStorage.getItem("mode");
          if(getMode && getMode === "dark-mode"){
            body.classList.add("dark");
          }

//   js code to toggle sidebar
sidebarOpen.addEventListener("click" , () =>{
    nav.classList.add("active");
});

body.addEventListener("click" , e =>{
    let clickedElm = e.target;

    if(!clickedElm.classList.contains("sidebarOpen") && !clickedElm.classList.contains("menu")){
        nav.classList.remove("active");
    }
});
</script>
</body>
</html>
