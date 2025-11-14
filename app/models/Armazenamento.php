<?php
class Armazenamento {
    private static function caminho(string $nome): string {
        $diretorio = __DIR__ . '/../../storage';
        if (!is_dir($diretorio)) {
            mkdir($diretorio, 0777, true);
        }
        return $diretorio . '/' . $nome . '.json';
    }

    public static function carregar(string $nome): array {
        $arquivo = self::caminho($nome);
        if (!file_exists($arquivo)) {
            return [];
        }
        $conteudo = file_get_contents($arquivo);
        $dados = json_decode($conteudo ?: '[]', true);
        return is_array($dados) ? $dados : [];
    }

    public static function salvar(string $nome, array $dados): void {
        $arquivo = self::caminho($nome);
        file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function proximoId(array $registros): int {
        $maior = 0;
        foreach ($registros as $registro) {
            if (($registro['id'] ?? 0) > $maior) {
                $maior = $registro['id'];
            }
        }
        return $maior + 1;
    }

    public static function agora(): string {
        return date('Y-m-d H:i:s');
    }
}