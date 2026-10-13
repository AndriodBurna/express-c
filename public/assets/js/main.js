/* =========================================================================
   EPHRAITECH UNIFIED SOLUTIONS - Front-end interactions (vanilla JS)
   Respects prefers-reduced-motion. No external dependencies.
   ========================================================================= */
(function () {
  "use strict";

  const reduceMotion = window.matchMedia(
    "(prefers-reduced-motion: reduce)",
  ).matches;
  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

  /* ---------- Theme: saved choice, otherwise follow the operating system ---------- */
  const themeButtons = $$("#themeToggle, #themeToggleMobile");
  const systemTheme = window.matchMedia("(prefers-color-scheme: dark)");

  function applyTheme(theme) {
    document.body.classList.remove("theme-dark", "theme-light");
    document.body.classList.add("theme-" + theme);
    document.documentElement.dataset.theme = theme;
    themeButtons.forEach((button) => {
      const light = theme === "dark";
      button.setAttribute(
        "aria-label",
        light ? "Switch to light mode" : "Switch to dark mode",
      );
      button.setAttribute("aria-pressed", String(!light));
      button.querySelector("i").className = light
        ? "fa-solid fa-sun"
        : "fa-solid fa-moon";
      const label = $(".theme-toggle-label", button);
      if (label) label.textContent = light ? "Light mode" : "Dark mode";
    });
  }

  function preferredTheme() {
    return (
      localStorage.getItem("ephraitech-theme") ||
      (systemTheme.matches ? "dark" : "light")
    );
  }
  applyTheme(preferredTheme());
  themeButtons.forEach((button) =>
    button.addEventListener("click", () => {
      const next = document.body.classList.contains("theme-dark")
        ? "light"
        : "dark";
      localStorage.setItem("ephraitech-theme", next);
      applyTheme(next);
    }),
  );
  systemTheme.addEventListener?.("change", (event) => {
    if (!localStorage.getItem("ephraitech-theme"))
      applyTheme(event.matches ? "dark" : "light");
  });

  /* ---------- Header: shrink + blur on scroll, progress bar ---------- */
  const header = $("#siteHeader");
  const progress = $(".scroll-progress");

  function onScroll() {
    const y = window.scrollY || document.documentElement.scrollTop;
    if (header) header.classList.toggle("is-scrolled", y > 24);
    if (progress) {
      const max = document.documentElement.scrollHeight - window.innerHeight;
      progress.style.width =
        max > 0 ? Math.min(100, (y / max) * 100) + "%" : "0%";
    }
  }
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  /* ---------- Mobile navigation ---------- */
  const toggle = $("#navToggle");
  const mobileNav = $("#mobileNav");

  function setNav(open) {
    if (!toggle || !mobileNav) return;
    toggle.classList.toggle("is-open", open);
    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute("aria-label", open ? "Close menu" : "Open menu");
    mobileNav.classList.toggle("is-open", open);
    mobileNav.setAttribute("aria-hidden", String(!open));
    document.body.classList.toggle("nav-open", open);
  }
  if (toggle && mobileNav) {
    toggle.addEventListener("click", () =>
      setNav(!mobileNav.classList.contains("is-open")),
    );
    $$("a", mobileNav).forEach((a) =>
      a.addEventListener("click", () => setNav(false)),
    );
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") setNav(false);
    });
    window.addEventListener("resize", () => {
      if (window.innerWidth > 1100) setNav(false);
    });
  }

  /* ---------- Scroll reveal (IntersectionObserver) ---------- */
  const revealEls = $$(".reveal, .process-track");
  if (revealEls.length) {
    if (reduceMotion || !("IntersectionObserver" in window)) {
      revealEls.forEach((el) => el.classList.add("is-visible"));
    } else {
      const io = new IntersectionObserver(
        (entries, obs) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add("is-visible");
              obs.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.12, rootMargin: "0px 0px -8% 0px" },
      );
      revealEls.forEach((el) => io.observe(el));
    }
  }

  /* Auto-stagger children inside [data-stagger] containers */
  $$("[data-stagger]").forEach((group) => {
    const step = parseFloat(group.dataset.stagger) || 0.08;
    $$(".reveal", group).forEach((el, i) => {
      el.style.setProperty("--delay", (i * step).toFixed(2) + "s");
    });
  });

  /* ---------- Animated counters ---------- */
  function animateCounter(el) {
    const target = parseFloat(el.dataset.count || "0");
    const decimals = (el.dataset.count || "").includes(".") ? 1 : 0;
    const duration = 1600;
    const start = performance.now();
    function frame(now) {
      const t = Math.min(1, (now - start) / duration);
      const eased = 1 - Math.pow(1 - t, 3);
      el.textContent = (target * eased)
        .toFixed(decimals)
        .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      if (t < 1) requestAnimationFrame(frame);
    }
    requestAnimationFrame(frame);
  }
  const counters = $$("[data-count]");
  if (counters.length) {
    if (reduceMotion || !("IntersectionObserver" in window)) {
      counters.forEach((el) => {
        el.textContent = el.dataset.count;
      });
    } else {
      const cio = new IntersectionObserver(
        (entries, obs) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              animateCounter(entry.target);
              obs.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.5 },
      );
      counters.forEach((el) => cio.observe(el));
    }
  }

  /* ---------- Subtle parallax for [data-parallax="speed"] ---------- */
  const parallaxEls = $$("[data-parallax]");
  if (parallaxEls.length && !reduceMotion && window.innerWidth > 860) {
    let ticking = false;
    function updateParallax() {
      const vh = window.innerHeight;
      parallaxEls.forEach((el) => {
        const rect = el.getBoundingClientRect();
        if (rect.bottom < 0 || rect.top > vh) return;
        const speed = parseFloat(el.dataset.parallax) || 0.1;
        const offset = (rect.top + rect.height / 2 - vh / 2) * speed;
        el.style.transform = "translate3d(0," + (-offset).toFixed(1) + "px,0)";
      });
      ticking = false;
    }
    window.addEventListener(
      "scroll",
      () => {
        if (!ticking) {
          requestAnimationFrame(updateParallax);
          ticking = true;
        }
      },
      { passive: true },
    );
    updateParallax();
  }

  /* ---------- Portfolio filters ---------- */
  const filterBar = $("[data-filters]");
  if (filterBar) {
    const cards = $$("[data-category]");
    filterBar.addEventListener("click", (e) => {
      const btn = e.target.closest(".filter-btn");
      if (!btn) return;
      $$(".filter-btn", filterBar).forEach((b) =>
        b.classList.remove("is-active"),
      );
      btn.classList.add("is-active");
      const f = btn.dataset.filter;
      cards.forEach((card) => {
        const show = f === "all" || card.dataset.category === f;
        card.classList.toggle("is-hidden", !show);
        if (show) {
          card.classList.remove("is-visible");
          requestAnimationFrame(() => card.classList.add("is-visible"));
        }
      });
    });
  }

  /* ---------- Smooth anchor scrolling with header offset ---------- */
  $$('a[href*="#"]').forEach((link) => {
    link.addEventListener("click", (e) => {
      const url = new URL(link.href, window.location.href);
      if (url.pathname !== window.location.pathname || !url.hash) return;
      const target = document.getElementById(url.hash.slice(1));
      if (!target) return;
      e.preventDefault();
      const offset = (header ? header.offsetHeight : 0) + 16;
      const top = target.getBoundingClientRect().top + window.scrollY - offset;
      window.scrollTo({ top, behavior: reduceMotion ? "auto" : "smooth" });
      history.pushState(null, "", url.hash);
    });
  });

  /* ---------- Button micro-interaction: cursor-follow highlight ---------- */
  if (!reduceMotion) {
    $$(".btn-primary").forEach((btn) => {
      btn.addEventListener("pointermove", (e) => {
        const r = btn.getBoundingClientRect();
        btn.style.setProperty(
          "--mx",
          (((e.clientX - r.left) / r.width) * 100).toFixed(1) + "%",
        );
        btn.style.setProperty(
          "--my",
          (((e.clientY - r.top) / r.height) * 100).toFixed(1) + "%",
        );
      });
    });
  }

  /* ---------- Lazy-load images that lack the attribute ---------- */
  $$("img:not([loading])").forEach((img) => {
    img.loading = "lazy";
  });
})();
