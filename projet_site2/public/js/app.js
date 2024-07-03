document.addEventListener('DOMContentLoaded', function() {
    const burger = document.getElementById('burger');
    const navLinks = document.getElementById('nav-links');
    const header = document.querySelector('header');

    burger.addEventListener('click', function() {
        navLinks.classList.toggle('open');
    });

    window.addEventListener('scroll', function() {
        if (window.innerWidth > 820) {
            header.classList.toggle('sticky', window.scrollY > 20);
        } else {
            header.classList.remove('sticky');
        }
    });
});
