// ══════════════════════════════════════════
//  City Community Services Portal - main.js
// ══════════════════════════════════════════

document.addEventListener('DOMContentLoaded', () => {

  // ── Hamburger Menu ──────────────────────
  const hamburger = document.querySelector('.hamburger');
  const navLinks  = document.querySelector('.nav-links');

  if (hamburger && navLinks) {
    hamburger.addEventListener('click', () => {
      navLinks.classList.toggle('open');
      const expanded = navLinks.classList.contains('open');
      hamburger.setAttribute('aria-expanded', expanded);
    });
    document.addEventListener('click', (e) => {
      if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
        navLinks.classList.remove('open');
      }
    });
  }

  // ── Active Nav Link ──────────────────────
  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a').forEach(link => {
    if (link.getAttribute('href') === currentPage) {
      link.classList.add('active');
    }
  });

  // ── Scroll-reveal animation ──────────────
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });

  document.querySelectorAll('.reveal').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(24px)';
    el.style.transition = 'opacity 0.55s ease, transform 0.55s ease';
    observer.observe(el);
  });

  // ── Complaint Form Validation ────────────
  const complaintForm = document.getElementById('complaintForm');
  if (complaintForm) {
    complaintForm.addEventListener('submit', function(e) {
      let valid = true;

      // Clear previous errors
      document.querySelectorAll('.form-control').forEach(fc => fc.classList.remove('error'));

      const fields = ['resName', 'contact', 'description', 'area'];
      fields.forEach(id => {
        const el = document.getElementById(id);
        if (el && !el.value.trim()) {
          el.classList.add('error');
          valid = false;
        }
      });

      // Contact number: numeric only, 10-15 digits
      const contact = document.getElementById('contact');
      if (contact && contact.value.trim()) {
        if (!/^\d{10,15}$/.test(contact.value.trim())) {
          contact.classList.add('error');
          const errEl = contact.nextElementSibling;
          if (errEl && errEl.classList.contains('form-error')) {
            errEl.textContent = 'Enter a valid phone number (10-15 digits).';
          }
          valid = false;
        }
      }

      // Category selection
      const selectedCat = document.querySelector('input[name="category"]:checked');
      if (!selectedCat) {
        document.getElementById('cat-error').style.display = 'block';
        valid = false;
      } else {
        document.getElementById('cat-error').style.display = 'none';
      }

      if (!valid) {
        e.preventDefault();
        const firstError = complaintForm.querySelector('.form-control.error');
        if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });

    // Real-time phone validation
    const contactEl = document.getElementById('contact');
    if (contactEl) {
      contactEl.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '');
      });
    }
  }

  // ── Services Search Filter ───────────────
  const searchInput = document.getElementById('servicesSearch');
  if (searchInput) {
    searchInput.addEventListener('input', function() {
      const q = this.value.toLowerCase().trim();
      const cards = document.querySelectorAll('.service-card');
      let visible = 0;
      cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        const show = text.includes(q);
        card.style.display = show ? '' : 'none';
        if (show) visible++;
      });
      const noResult = document.getElementById('no-results');
      if (noResult) noResult.style.display = visible === 0 ? 'block' : 'none';
    });
  }

  // ── Admin Sidebar Toggle (mobile) ────────
  const sidebarToggle = document.getElementById('sidebarToggle');
  const adminSidebar  = document.querySelector('.admin-sidebar');
  if (sidebarToggle && adminSidebar) {
    sidebarToggle.addEventListener('click', () => {
      adminSidebar.classList.toggle('open');
    });
  }

  // ── Dismiss Alerts ───────────────────────
  document.querySelectorAll('.alert-dismiss').forEach(btn => {
    btn.addEventListener('click', () => {
      btn.closest('.alert').style.display = 'none';
    });
  });

  // ── Current Year in footer ───────────────
  const yearSpan = document.getElementById('currentYear');
  if (yearSpan) yearSpan.textContent = new Date().getFullYear();

});
