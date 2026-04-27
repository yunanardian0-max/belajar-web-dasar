const navLinks = document.querySelectorAll("nav a");

navLinks.forEach(link => {
    link.addEventListener("click", () => {
        navLinks.forEach(l => l.style.color = "#666");
        link.style.color = "#e8ff47";
    });
});

const cta = document.querySelector(".cta");

if (cta) {
    cta.addEventListener("click", () => {
        cta.innerHTML = "Scrolling... ↓";
    });
}

const form = document.querySelector(".contact-form");

const form = document.getElementById("contactForm");

if (form) {
    form.addEventListener("submit", function(e) {
        const nama = document.getElementById("nama").value.trim();
        const email = document.getElementById("email").value.trim();
        const pesan = document.getElementById("pesan").value.trim();

        if (nama === "" || email === "" || pesan === "") {
            e.preventDefault();
            alert("Harap isi semua field!");
        }
    });
}
const cards = document.querySelectorAll(".video-card");

cards.forEach(card => {
    card.addEventListener("mouseenter", () => {
        card.style.transform = "scale(1.02)";
    });

    card.addEventListener("mouseleave", () => {
        card.style.transform = "scale(1)";
    });
});

const sections = document.querySelectorAll("section");

sections.forEach(sec => {
    sec.style.opacity = "0";
    sec.style.transform = "translateY(40px)";
    sec.style.transition = "all 0.6s ease";
});

window.addEventListener("scroll", () => {
    sections.forEach(sec => {
        const top = sec.getBoundingClientRect().top;
        if (top < window.innerHeight - 100) {
            sec.style.opacity = "1";
            sec.style.transform = "translateY(0)";
        }
    });
});