<?php

header("Content-Type: application/json; charset=UTF-8");

require_once "conexao.php";

try {

    $id = filter_var(
        $_POST["id"] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$id || $id <= 0) {

        echo json_encode([
            "sucesso" => false,
            "mensagem" => "ID inválido."
        ]);

        exit;
    }

    $sql = "DELETE FROM pets WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":id" => $id
    ]);

    if ($stmt->rowCount() > 0) {

        echo json_encode([
            "sucesso" => true,
            "mensagem" => "Pet excluído com sucesso!"
        ]);

    } else {

        echo json_encode([
            "sucesso" => false,
            "mensagem" => "Pet não encontrado."
        ]);

    }

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Erro ao excluir o pet."
    ]);

}

?>