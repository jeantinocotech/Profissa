<?php

$modelsDir = __DIR__ . '/app/Models';
$projectDir = __DIR__ . '/app';

// Pega todos os modelos reais (nomes de arquivos com extensão .php)
$actualModels = array_map(function($f) {
    return pathinfo($f, PATHINFO_FILENAME);
}, glob($modelsDir . '/*.php'));

$actualModelsMap = array_flip($actualModels); // para busca rápida

echo "Verificando imports de App\\Models\\...\n\n";

// Escaneia todos os arquivos do projeto
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($projectDir));

foreach ($rii as $file) {
    if (!$file->isFile() || pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
        continue;
    }

    $path = $file->getPathname();
    $lines = file($path);

    foreach ($lines as $i => $line) {
        if (preg_match('/use\s+App\\\\Models\\\\([a-zA-Z0-9_]+);/', $line, $matches)) {
            $imported = $matches[1];
            $correct = in_array($imported, $actualModels);

            if (!$correct) {
                // Verifica se o nome correto com outro case existe
                foreach ($actualModels as $realModel) {
                    if (strtolower($imported) === strtolower($realModel) && $imported !== $realModel) {
                        echo "⚠️  Caso incorreto em $path (linha ".($i+1)."): use App\\Models\\$imported;\n";
                        echo "   ➤ Correto seria: use App\\Models\\$realModel;\n\n";
                        break;
                    }
                }
            }
        }
    }
}

echo "✅ Verificação finalizada.\n";
