<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "conexao.php";

try {

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

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo json_encode([
            "sucesso" => false,
            "mensagem" => "Digite um email válido."
        ]);

        exit;
    }

    $sql = "INSERT INTO pets
            (pet, tutor, endereco, email, telefone)
            VALUES
            (:pet, :tutor, :endereco, :email, :telefone)";

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

    http_response_code(500);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro ao cadastrar o pet."
    ]);

}

?>