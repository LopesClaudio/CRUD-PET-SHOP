<?php

header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$banco = "crud_petshop";
$usuario = "root";
$senha = "SUA_SENHA_DO_BANCO";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

    $pet = trim($_POST["pet"] ?? "");
    $tutor = trim($_POST["tutor"] ?? "");
    $endereco = trim($_POST["endereco"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");

    if (
        !$id ||
        $pet === "" ||
        $tutor === "" ||
        $endereco === "" ||
        $email === "" ||
        $telefone === ""
    ) {
        echo json_encode([
            "sucesso" => false,
            "mensagem" => "Preencha todos os campos corretamente."
        ]);
        exit;
    }

    $verifica = $pdo->prepare(
        "SELECT id FROM pets WHERE id = :id"
    );

    $verifica->execute([
        ":id" => $id
    ]);

    if (!$verifica->fetch()) {
        echo json_encode([
            "sucesso" => false,
            "mensagem" => "Pet não encontrado."
        ]);
        exit;
    }

    $sql = "UPDATE pets SET
                pet = :pet,
                tutor = :tutor,
                endereco = :endereco,
                email = :email,
                telefone = :telefone
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":pet" => $pet,
        ":tutor" => $tutor,
        ":endereco" => $endereco,
        ":email" => $email,
        ":telefone" => $telefone,
        ":id" => $id
    ]);

    echo json_encode([
        "sucesso" => true,
        "mensagem" => "Pet atualizado com sucesso!"
    ]);

} catch (PDOException $e) {
    http_response_code(500);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro ao atualizar o pet."
    ]);
}
