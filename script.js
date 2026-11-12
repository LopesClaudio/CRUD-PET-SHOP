const formulario = document.getElementById("formCadastro");
const mensagem = document.getElementById("mensagem");

const campoId = document.getElementById("id");
const botaoSalvar = document.getElementById("botaoSalvar");
const botaoCancelar = document.getElementById("botaoCancelar");

const campoBusca = document.getElementById("campoBusca");
const botaoBuscar = document.getElementById("botaoBuscar");
const resultadoBusca = document.getElementById("resultadoBusca");

// CREATE E UPDATE

formulario.addEventListener("submit", async function(event) {

    event.preventDefault();

    const editando = campoId.value !== "";

    const arquivo = editando
        ? "atualizar.php"
        : "cadastrar.php";

    const dados = new FormData(formulario);

    try {

        const resposta = await fetch(arquivo, {
            method: "POST",
            body: dados
        });

        const resultado = await resposta.json();

        mensagem.textContent = resultado.mensagem;
        mensagem.style.color = resultado.sucesso ? "green" : "red";

        if (resultado.sucesso) {

            formulario.reset();

            campoId.value = "";

            botaoSalvar.textContent = "Cadastrar";
            botaoCancelar.hidden = true;

            if (campoBusca.value.trim() !== "") {
                await buscarPets();
            }

        }

    } catch (erro) {

        console.error(erro);

        mensagem.textContent = "Erro ao conectar com o servidor.";
        mensagem.style.color = "red";

    }

});

// CANCELAR EDIÇÃO

botaoCancelar.addEventListener("click", function() {

    formulario.reset();

    campoId.value = "";

    botaoSalvar.textContent = "Cadastrar";
    botaoCancelar.hidden = true;

    mensagem.textContent = "";

});

// READ - PESQUISA

async function buscarPets() {

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

        resultadoBusca.replaceChildren();

        if (!resultado.sucesso) {

            resultadoBusca.textContent =
                resultado.mensagem || "Erro ao buscar os pets.";

            return;
        }

        if (resultado.pets.length === 0) {

            resultadoBusca.textContent = "Nenhum pet encontrado.";

            return;
        }

        resultado.pets.forEach(function(pet) {

            const div = document.createElement("div");

            // ADICIONAR DADOS DO PET

            function adicionarCampo(rotulo, valor) {

                const p = document.createElement("p");

                const strong = document.createElement("strong");

                strong.textContent = rotulo + ": ";

                p.appendChild(strong);

                p.appendChild(
                    document.createTextNode(valor ?? "")
                );

                div.appendChild(p);

            }

            adicionarCampo("Pet", pet.pet);
            adicionarCampo("Tutor", pet.tutor);
            adicionarCampo("Endereço", pet.endereco);
            adicionarCampo("Email", pet.email);
            adicionarCampo("Telefone", pet.telefone);

            // BOTÃO EDITAR

            const botaoEditar = document.createElement("button");

            botaoEditar.type = "button";
            botaoEditar.textContent = "Editar";
            botaoEditar.classList.add("botaoEditar");

            botaoEditar.addEventListener("click", function() {

                campoId.value = pet.id;

                document.getElementById("pet").value = pet.pet;
                document.getElementById("tutor").value = pet.tutor;
                document.getElementById("endereco").value = pet.endereco;
                document.getElementById("email").value = pet.email;
                document.getElementById("telefone").value = pet.telefone;

                botaoSalvar.textContent = "Salvar alterações";

                botaoCancelar.hidden = false;

                mensagem.textContent =
                    "Editando o cadastro de " + pet.pet;

                mensagem.style.color = "#d97706";

                formulario.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });

            });

            div.appendChild(botaoEditar);

            // BOTÃO EXCLUIR

            const botaoExcluir = document.createElement("button");

            botaoExcluir.type = "button";
            botaoExcluir.textContent = "Excluir";
            botaoExcluir.classList.add("botaoExcluir");

            botaoExcluir.addEventListener("click", async function() {

                const confirmar = confirm(
                    "Tem certeza que deseja excluir o pet " +
                    pet.pet + "?"
                );

                if (!confirmar) {
                    return;
                }

                try {

                    const dados = new FormData();

                    dados.append("id", pet.id);

                    const resposta = await fetch("excluir.php", {
                        method: "POST",
                        body: dados
                    });

                    const resultado = await resposta.json();

                    mensagem.textContent = resultado.mensagem;

                    mensagem.style.color =
                        resultado.sucesso ? "green" : "red";

                    if (resultado.sucesso) {

                        // LIMPAR FORMULÁRIO SE ESTIVER EDITANDO O PET

                        if (campoId.value == pet.id) {

                            formulario.reset();

                            campoId.value = "";

                            botaoSalvar.textContent = "Cadastrar";

                            botaoCancelar.hidden = true;

                        }

                        // ATUALIZAR PESQUISA

                        await buscarPets();

                    }

                } catch (erro) {

                    console.error(erro);

                    mensagem.textContent = "Erro ao excluir o pet.";

                    mensagem.style.color = "red";

                }

            });

            div.appendChild(botaoExcluir);

            div.appendChild(document.createElement("hr"));

            resultadoBusca.appendChild(div);

        });

    } catch (erro) {

        console.error(erro);

        resultadoBusca.textContent = "Erro ao buscar o pet.";

    }

}

// BOTÃO BUSCAR

botaoBuscar.addEventListener("click", buscarPets);

// PESQUISAR PRESSIONANDO ENTER

campoBusca.addEventListener("keydown", function(event) {

    if (event.key === "Enter") {

        event.preventDefault();

        buscarPets();

    }

});
