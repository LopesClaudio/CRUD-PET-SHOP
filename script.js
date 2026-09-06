const formulario = document.getElementById("formCadastro");
const mensagem = document.getElementById("mensagem");

const campoBusca = document.getElementById("campoBusca");
const botaoBuscar = document.getElementById("botaoBuscar");
const resultadoBusca = document.getElementById("resultadoBusca");


// CREATE

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


// READ - PESQUISA

botaoBuscar.addEventListener("click", async function() {

    const busca = campoBusca.value.trim();

    if (busca === "") {

        resultadoBusca.textContent = "Digite o nome de um pet.";

        return;
    }

    try {

        const resposta = await fetch(
            "listar.php?busca=" + encodeURIComponent(busca)
        );

        const resultado = await resposta.json();

        resultadoBusca.innerHTML = "";

        if (resultado.pets.length === 0) {

            resultadoBusca.textContent = "Nenhum pet encontrado.";

            return;
        }

        resultado.pets.forEach(function(pet) {

            const div = document.createElement("div");

            div.innerHTML = `
                <p><strong>Pet:</strong> ${pet.pet}</p>
                <p><strong>Tutor:</strong> ${pet.tutor}</p>
                <p><strong>Endereço:</strong> ${pet.endereco}</p>
                <p><strong>Email:</strong> ${pet.email}</p>
                <p><strong>Telefone:</strong> ${pet.telefone}</p>
                <hr>
            `;

            resultadoBusca.appendChild(div);

        });

    } catch (erro) {

        console.error(erro);

        resultadoBusca.textContent = "Erro ao buscar o pet.";

    }

});



