// -----------------navbar on small screen----------
let navbar = document.querySelector('.header .navbar');
document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
}
window.addEventListener('scroll', calHeight)
function calHeight(){
    if(window.scrollY > 250){
        navbar.classList.remove('active');
    }
}

// -----------------Js For showing active faq( frequently asked question) tab-------------------
document.querySelectorAll('.contact .row .faq .box h3').forEach(faqBox => {
    faqBox.onclick = () => {
        faqBox.parentElement.classList.toggle('active');
    }
});

document.querySelectorAll('input[type="number"]').forEach(inputNumber => {
    inputNumber.oninput = () =>{
       if(inputNumber.value.length > inputNumber.maxLength) inputNumber.value = inputNumber.value.slice(0, inputNumber.maxLength);
    }
 });


// -------------Swiper Js for home------------------
var swiper = new Swiper(".home-slider", {
    loop: true,
    effect: "coverflow",
    spaceBetween: 30,
    grabCursor: true,
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: false,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});

// -------------Swiper Js for gallery------------------
var swiper = new Swiper(".gallery-slider", {
    loop: true,
    effect: "coverflow",
    slidesPerView: "auto",
    centeredSlides: true,
    grabCursor: true,
    coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 100,
        modifier: 2,
        slideShadows: true,
    },
    pagination: {
        el: ".swiper-pagination",
    },
});

// -----------------Swiper Js for sliding of reviews------------------
var swiper = new Swiper(".reviews-slider", {
    loop: true,
    slidesPerView: "auto",
    grabCursor: true,
    spaceBetween: 30,
    pagination: {
        el: ".swiper-pagination",
    },
    breakpoints: {
        768: {
          slidesPerView: 1,
        },
        991: {
          slidesPerView: 2,
        },
      },
});

// <!-- --------------------Js for Scroll up button------------------------ -->
const scrollTopBtn = document.querySelector('.scroll-up-btn');
window.addEventListener('scroll', checkHeight)

function checkHeight(){
    if(window.scrollY > 500){
        scrollTopBtn.style.display = "block";
    }else{
        scrollTopBtn.style.display ="none";
    }
}
scrollTopBtn.addEventListener('click', () =>{
    window.scrollTo({
        top: 0
    });
});
