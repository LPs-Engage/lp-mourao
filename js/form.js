(function () {
  const WEBHOOK_URL =
    "https://n8n.engageagencia.com.br/webhook-test/eff85629-040e-41e8-bd47-2cfa0cf4666b";
  const REDIRECT_URL =
    "https://chat.whatsapp.com/Ie741WiwhRqLFu7kyYrD16?mode=gi_t";

  const setHidden = (id, value) => {
    const el = document.getElementById(id);
    if (el) el.value = value ?? "";
  };

  const fillTrackingFields = () => {
    const params = new URLSearchParams(window.location.search);
    setHidden("page_url", window.location.href);
    setHidden("utm_source", params.get("utm_source"));
    setHidden("utm_medium", params.get("utm_medium"));
    setHidden("utm_campaign", params.get("utm_campaign"));
    setHidden("utm_term", params.get("utm_term"));
    setHidden("utm_content", params.get("utm_content"));
  };

  const initPhoneMask = (input) => {
    if (!input) return;

    input.addEventListener("input", () => {
      let digits = input.value.replace(/\D/g, "");

      if (!digits.startsWith("55")) {
        digits = "55" + digits;
      }

      digits = digits.slice(0, 13);

      let formatted = "+55 ";
      if (digits.length > 2) {
        const ddd = digits.slice(2, 4);
        formatted += `(${ddd}) `;
      }
      if (digits.length > 4) {
        const part1 = digits.slice(4, 9);
        formatted += part1;
      }
      if (digits.length > 9) {
        const part2 = digits.slice(9);
        formatted += `-${part2}`;
      }

      input.value = formatted;
    });
  };

  const initFormSubmit = (form, phoneInput, onSuccess) => {
    if (!form || !phoneInput) return;

    form.action = WEBHOOK_URL;
    form.method = "POST";

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      const digits = phoneInput.value.replace(/\D/g, "");
      if (digits.length !== 13) {
        alert("Por favor, preencha o número completo de telefone.");
        return;
      }

      fillTrackingFields();

      const formData = new FormData(form);
      const submitBtn = form.querySelector('[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;

      try {
        const response = await fetch(form.action, {
          method: form.method,
          body: formData,
          headers: { Accept: "application/json" },
        });

        if (!response.ok) {
          alert("Ocorreu um erro ao enviar. Tente novamente.");
          return;
        }
      } catch (err) {
        console.warn("Erro ao enviar formulário:", err);
        alert("Erro ao enviar o formulário.");
        return;
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }

      form.reset();
      phoneInput.value = "+55 ";
      onSuccess?.();
      window.location.href = REDIRECT_URL;
    });
  };

  // --- formulário inline (#form) ---
  const contactForm = document.getElementById("contact-form");
  const telefoneInput = document.getElementById("telefone");

  if (contactForm && telefoneInput) {
    initPhoneMask(telefoneInput);
    initFormSubmit(contactForm, telefoneInput);
  }

  // --- popup (opcional, só se existir no DOM) ---
  const modal = document.getElementById("modal");
  const overlay = document.getElementById("modal-overlay");
  const popupForm = document.getElementById("popup-form");
  const phoneInput = document.getElementById("phone");

  if (!modal || !overlay || !popupForm || !phoneInput) return;

  const closeBtn = document.getElementById("close-modal-btn");
  const openBtns = document.querySelectorAll(".form");

  overlay.style.display = "none";
  modal.style.display = "none";

  openBtns.forEach((btn) => {
    if (!btn) return;
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      overlay.style.display = "block";
      modal.style.display = "flex";
    });
  });

  const closeModal = () => {
    overlay.style.display = "none";
    modal.style.display = "none";
  };

  closeBtn?.addEventListener("click", closeModal);
  overlay.addEventListener("click", closeModal);

  initPhoneMask(phoneInput);
  initFormSubmit(popupForm, phoneInput, closeModal);
})();
