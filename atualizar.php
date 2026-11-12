<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "conexao.php";

try {

    $id = filter_var(
        $_POST["id"] ?? null,
        FILTER_VALIDATE_INT
    );

    $pet = trim($_POST["pet"] ?? "");
    $tutor = trim($_POST["tutor"] ?? "");
    $endereco = trim($_POST["endereco"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");

    if (!$id || $id <= 0) {

        echo json_encode([
            "sucesso" => false,
            "mensagem" => "ID inválido."
        ]);

        exit;
    }

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

    if ($stmt->rowCount() > 0) {

        echo json_encode([
            "sucesso" => true,
            "mensagem" => "Pet atualizado com sucesso!"
        ]);

    } else {

        $consulta = $pdo->prepare(
            "SELECT id FROM pets WHERE id = :id"
        );

        $consulta->execute([":id" => $id]);

        if ($consulta->fetch()) {

            echo json_encode([
                "sucesso" => true,
                "mensagem" => "Nenhuma alteração nos dados do pet."
            ]);

        } else {

            echo json_encode([
                "sucesso" => false,
                "mensagem" => "Pet não encontrado."
            ]);

        }

    }

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro ao atualizar o pet."
    ]);

}

?>
