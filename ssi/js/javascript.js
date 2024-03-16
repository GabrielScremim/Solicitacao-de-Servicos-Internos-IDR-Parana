document.addEventListener("DOMContentLoaded", function (event) {

    const mostrarBarraNavegacao = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
            nav = document.getElementById(navId),
            bodypd = document.getElementById(bodyId),
            headerpd = document.getElementById(headerId)

        // Valide se todas as variáveis existem
        if (toggle && nav && bodypd && headerpd) {
            toggle.addEventListener('click', () => {
                // mostrar a barra de navegação
                nav.classList.toggle('show')
                // adicionar preenchimento ao corpo
                bodypd.classList.toggle('body-pd')
                // adicionar preenchimento ao cabeçalho
                headerpd.classList.toggle('body-pd')
            })
        }
    }

    mostrarBarraNavegacao('header-toggle', 'nav-bar', 'body-pd', 'header')
});

document.addEventListener("DOMContentLoaded", function () {
    var btnMostrarFormulario = document.getElementById("mostrarFormulario");
    var formularioServico = document.getElementById("formularioServico");

    btnMostrarFormulario.addEventListener("click", function () {
        // Alternar entre exibir e ocultar o formulário ao clicar no botão "+"
        if (formularioServico.style.display === "none" || formularioServico.style.display === "") {
            formularioServico.style.display = "block"; // Exibe o formulário
        } else {
            formularioServico.style.display = "none"; // Oculta o formulário
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    var btnMostrarFormularioPeca = document.getElementById("mostrarFormularioPeca");
    var formularioPeca = document.getElementById("formularioPeca");

    btnMostrarFormularioPeca.addEventListener("click", function () {
        // Alternar entre exibir e ocultar o formulário ao clicar no botão "+"
        if (formularioPeca.style.display === "none" || formularioPeca.style.display === "") {
            formularioPeca.style.display = "block"; // Exibe o formulário
        } else {
            formularioPeca.style.display = "none"; // Oculta o formulário
        }
    });
});


// Função para recarregar a página após o envio do formulário
function recarregarPagina() {
    window.location.reload(true);
}

