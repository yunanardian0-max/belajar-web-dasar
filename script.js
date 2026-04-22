// ========================
// Smooth Scroll Highlight Nav
// ========================
const navLinks = document.querySelectorAll("nav a");

navLinks.forEach(link => {
    link.addEventListener("click", () => {
        navLinks.forEach(l => l.style.color = "#666");
        link.style.color = "#e8ff47";
    });
});


// ========================
// Hero CTA Click Effect
// ========================
const cta = document.querySelector(".cta");

cta.addEventListener("click", () => {
    cta.innerHTML = "Scrolling... ↓";
});


// ========================
// Form Validation + Feedback
// ========================
const form = document.querySelector(".contact-form");

form.addEventListener("submit", function(e) {
    e.preventDefault();

    const nama = document.getElementById("nama").value.trim();
    const email = document.getElementById("email").value.trim();
    const pesan = document.getElementById("pesan").value.trim();

    if (nama === "" || email === "" || pesan === "") {
        alert("Harap isi semua field!");
        return;
    }

    alert("Pesan berhasil dikirim 🚀");

    form.reset();
});


// ========================
// Hover Effect Video Card (JS Enhance)
// ========================
const cards = document.querySelectorAll(".video-card");

cards.forEach(card => {
    card.addEventListener("mouseenter", () => {
        card.style.transform = "scale(1.02)";
    });

    card.addEventListener("mouseleave", () => {
        card.style.transform = "scale(1)";
    });
});


// ========================
// Scroll Reveal Effect
// ========================
const sections = document.querySelectorAll("section");

window.addEventListener("scroll", () => {
    sections.forEach(sec => {
        const top = sec.getBoundingClientRect().top;
        if (top < window.innerHeight - 100) {
            sec.style.opacity = "1";
            sec.style.transform = "translateY(0)";
        }
    });
});

// initial state
sections.forEach(sec => {
    sec.style.opacity = "0";
    sec.style.transform = "translateY(40px)";
    sec.style.transition = "all 0.6s ease";
});