<?php
$imagens = [
    ['id' => 1, 'url' => 'assets/chale1.jpg', 'titulo' => 'Vista Externa'],
    ['id' => 2, 'url' => 'assets/chale2.jpg', 'titulo' => 'Interior Luxo'],
    ['id' => 3, 'url' => 'assets/chale3.jpg', 'titulo' => 'Piscina'],
];

foreach ($imagens as $img) {
    echo '
    <div class="gallery-item" onclick="selecionarImagem(this, ' . $img['id'] . ')">
        <img src="' . $img['url'] . '" alt="' . $img['titulo'] . '">
    </div>';
}
?>