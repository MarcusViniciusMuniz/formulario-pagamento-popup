// Arquivo: assets/js/script.js

jQuery(document).ready(function ($) {
  // 🟢 Pega o nonce e ajax_url passados do PHP
  const nonce = fpp_data.nonce;
  const ajaxUrl = fpp_data.ajax_url;

  // 🟢 Quando o botão com classe 'fpp-abrir-popup' for clicado, mostra o popup correspondente
  $(document).on("click", ".fpp-abrir-popup", function (e) {
    e.preventDefault();

    const popupId = $(this).data("popup-id");
    $(`.fpp-popup-overlay[data-popup-id="${popupId}"]`).fadeIn();
  });

  // 🟢 Fecha o popup quando clica fora da área do formulário
  $(document).on("click", ".fpp-popup-overlay", function (e) {
    if ($(e.target).hasClass("fpp-popup-overlay")) {
      $(this).fadeOut();
    }
  });

  // 🟢 Envio do formulário
  $(document).on("submit", ".fpp-form", function (e) {
    e.preventDefault();

    const form = $(this);
    const nome = form.find(".fpp-nome").val().trim();
    const email = form.find(".fpp-email").val().trim();
    const telefone = form.find(".fpp-telefone").val().trim();
    const mensagem = form.find(".fpp-mensagem");
    const botao = form.find('button[type="submit"]');

    // Validação simples de e-mail
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!nome || !email || !telefone) {
      mensagem.text("Por favor, preencha todos os campos.").css("color", "red");
      return;
    }
    if (!emailRegex.test(email)) {
      mensagem.text("Digite um e-mail válido.").css("color", "red");
      return;
    }

    // Desativa o botão para evitar duplo clique
    botao.prop("disabled", true);
    mensagem.text("Processando...").css("color", "black");

    $.ajax({
      type: "POST",
      url: ajaxUrl,
      data: {
        action: "fpp_enviar_dados",
        nonce: nonce,
        nome: nome,
        email: email,
        telefone: telefone,
      },
      success: function (response) {
        if (response.success && response.data.url) {
          mensagem
            .text("Redirecionando para pagamento...")
            .css("color", "green");
          setTimeout(() => {
            window.location.href = response.data.url;
          }, 1000);
        } else {
          botao.prop("disabled", false);
          const erro =
            typeof response.data === "string"
              ? response.data
              : "Erro ao processar pagamento.";
          mensagem.text(erro).css("color", "red");
        }
      },
      error: function () {
        botao.prop("disabled", false);
        mensagem
          .text("Erro de comunicação com o servidor.")
          .css("color", "red");
      },
    });
  });
});
