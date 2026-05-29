<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome_chale'];
    $valor = $_POST['valor_chale'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];

    // Lógica simples para upload de imagem
    if (isset($_FILES['imagem'])) {
        $diretorio = "uploads/";
        $arquivo_nome = basename($_FILES["imagem"]["name"]);
        $caminho_final = $diretorio . $arquivo_nome;
        
        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho_final)) {
            echo "Chalé '$nome' publicado com sucesso!";
        } else {
            echo "Erro ao fazer upload da imagem.";
        }
    }
}
?>