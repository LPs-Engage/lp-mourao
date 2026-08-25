(function () {
  const header = document.querySelector("header");
  const toggle = document.querySelector(".menu-toggle");

  if (!header || !toggle) return;

  toggle.addEventListener("click", () => {
    const open = header.classList.toggle("is-open");
    toggle.setAttribute("aria-expanded", String(open));
    toggle.setAttribute("aria-label", open ? "Fechar menu" : "Abrir menu");
  });

  header.querySelectorAll("nav a").forEach((link) => {
    link.addEventListener("click", () => {
      header.classList.remove("is-open");
      toggle.setAttribute("aria-expanded", "false");
      toggle.setAttribute("aria-label", "Abrir menu");
    });
  });
})();

(function () {
  document.querySelectorAll("#projetos figure, #redes figure").forEach((frame) => {
    const video = frame.querySelector("video");
    const playBtn = frame.querySelector("button");

    if (!video || !playBtn) return;

    const hasSource = () => Boolean(video.currentSrc || video.src || video.querySelector("source"));

    playBtn.addEventListener("click", () => {
      if (!hasSource()) return;
      video.setAttribute("controls", "");
      video.play();
      frame.classList.add("is-playing");
    });

    video.addEventListener("pause", () => {
      frame.classList.remove("is-playing");
    });

    video.addEventListener("ended", () => {
      frame.classList.remove("is-playing");
      video.removeAttribute("controls");
    });
  });
})();

(function () {
  const section = document.getElementById("entregas");
  if (!section) return;

  const buttons = section.querySelectorAll("[data-filter]");
  const cards = section.querySelectorAll("article");
  let status = "todos";
  let tema = "todos";

  const applyFilter = () => {
    cards.forEach((card) => {
      const okStatus = status === "todos" || card.dataset.status === status;
      const okTema = tema === "todos" || card.dataset.tema === tema;
      card.hidden = !(okStatus && okTema);
    });
  };

  buttons.forEach((btn) => {
    btn.addEventListener("click", () => {
      const type = btn.dataset.filter;
      const value = btn.dataset.value;

      if (type === "status") status = value;
      if (type === "tema") tema = value;

      buttons.forEach((other) => {
        if (other.dataset.filter === type) {
          other.setAttribute("aria-pressed", other === btn ? "true" : "false");
        }
      });

      applyFilter();
    });
  });
})();

(function () {
  const section = document.getElementById("agenda");
  if (!section) return;

  section.querySelectorAll("article").forEach((card) => {
    card.setAttribute("tabindex", "0");

    const toggle = () => {
      const open = card.classList.toggle("is-flipped");
      card.setAttribute("aria-pressed", String(open));
    };

    card.addEventListener("click", (e) => {
      if (e.target.closest("a")) return;
      toggle();
    });

    card.addEventListener("keydown", (e) => {
      if (e.key !== "Enter" && e.key !== " ") return;
      e.preventDefault();
      toggle();
    });
  });
})();
