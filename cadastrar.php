<?php

header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$banco = "crud_petshop";
$usuario = "root";
$senha = "97b3@o519mJ";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pet = trim($_POST["pet"] ?? "");
    $tutor = trim($_POST["tutor"] ?? "");
    $endereco = trim($_POST["endereco"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");

    if (
        $pet === "" ||
        $tutor === "" ||
        $endereco === "" ||
        $email === "" ||
        $telefone === ""
    ) {

        echo json_encode([
            "sucesso" => false,
            "mensagem" => "Todos os campos são obrigatórios."
        ]);

        exit;
    }

    $sql = "INSERT INTO pets (pet, tutor, endereco, email, telefone)
            VALUES (:pet, :tutor, :endereco, :email, :telefone)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":pet" => $pet,
        ":tutor" => $tutor,
        ":endereco" => $endereco,
        ":email" => $email,
        ":telefone" => $telefone
    ]);

    echo json_encode([
        "sucesso" => true,
        "mensagem" => "Pet cadastrado com sucesso!"
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro ao cadastrar Pet: " . $e->getMessage()
    ]);
}
