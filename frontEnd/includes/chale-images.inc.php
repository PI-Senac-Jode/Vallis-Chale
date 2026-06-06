<?php

function normalize_chale_key(string $value): string
{
    // Remove acentos quando possivel para facilitar comparacoes por palavra-chave.
    $normalized = function_exists('iconv') ? iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) : false;
    $normalized = $normalized !== false ? $normalized : $value;

    return strtolower($normalized);
}

function chale_image_options(): array
{
    // Banco de imagens locais usado quando o chale nao possui imagem cadastrada.
    return [
        'chale-maravilha.png',
        'chale-paraiso.png',
        'chale-sossego.png',
        'chale-saudade.png',
        'chale-com-piscina.png',
        'chale-3.png',
    ];
}

function get_chale_image(array $chale, string $assetPrefix = './'): string
{
    // Se o banco tiver imagem_url, ela tem prioridade sobre as escolhas automaticas.
    if (!empty($chale['imagem_url'])) {
        $imageUrl = trim($chale['imagem_url']);

        if (preg_match('/^https?:\/\//', $imageUrl)) {
            return $imageUrl;
        }

        return $assetPrefix . 'src/assets/img/' . basename($imageUrl);
    }

    $name = normalize_chale_key($chale['nome'] ?? '');
    $description = normalize_chale_key($chale['descricao'] ?? '');
    $category = normalize_chale_key($chale['categoria_nome'] ?? '');
    $text = $name . ' ' . $description . ' ' . $category;

    // Associa imagens locais a palavras encontradas no nome, descricao ou categoria.
    $keywordImages = [
        'piscina' => 'chale-com-piscina.png',
        'lago' => 'chale-com-piscina.png',
        'luxo' => 'chale-paraiso.png',
        'premium' => 'chale-paraiso.png',
        'master' => 'chale-paraiso.png',
        'montanha' => 'chale-maravilha.png',
        'horizonte' => 'chale-maravilha.png',
        'vista' => 'chale-3.png',
        'flores' => 'chale-saudade.png',
        'romantico' => 'chale-saudade.png',
        'bosque' => 'chale-sossego.png',
        'aconchego' => 'chale-sossego.png',
        'rustic' => 'chale-saudade.png',
        'cabana' => 'chale-saudade.png',
    ];

    foreach ($keywordImages as $keyword => $image) {
        if (str_contains($text, $keyword)) {
            return $assetPrefix . 'src/assets/img/' . $image;
        }
    }

    $images = chale_image_options();
    $index = max((int) ($chale['id'] ?? 1) - 1, 0) % count($images);

    // Ultimo fallback: distribui as imagens pela ordem do ID do chale.
    return $assetPrefix . 'src/assets/img/' . $images[$index];
}

function get_chale_excerpt(?string $description, int $limit = 135): string
{
    // Cria um resumo curto para os cards da pagina inicial.
    $description = trim((string) $description);

    if ($description === '') {
        return 'Um refugio acolhedor para descansar com conforto, privacidade e contato direto com a natureza.';
    }

    if (function_exists('mb_strlen') && mb_strlen($description, 'UTF-8') > $limit) {
        return mb_substr($description, 0, $limit - 3, 'UTF-8') . '...';
    }

    if (!function_exists('mb_strlen') && strlen($description) > $limit) {
        return substr($description, 0, $limit - 3) . '...';
    }

    return $description;
}
