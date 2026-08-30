const formulario = document.getElementById("formCadastro");
const mensagem = document.getElementById("mensagem");

formulario.addEventListener("submit", async function(event) {

    event.preventDefault();

    const dados = new FormData(formulario);

    try {

        const resposta = await fetch("cadastrar.php", {
            method: "POST",
            body: dados
        });

        const resultado = await resposta.json();

        mensagem.textContent = resultado.mensagem;

        if (resultado.sucesso) {

            mensagem.style.color = "green";
            formulario.reset();

        } else {

            mensagem.style.color = "red";

        }

    } catch (erro) {

        console.error(erro);

        mensagem.textContent = "Erro ao conectar com o servidor.";
        mensagem.style.color = "red";

    }

});
