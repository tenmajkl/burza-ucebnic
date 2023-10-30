<?php


function findTODOs(string $folder): array
{
    $todos = [];
    foreach (scandir($folder) as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }

        if (is_dir($folder.'/'.$file)) {
            $todos = [...$todos, ...findTODOs($folder.'/'.$file)];
            continue;
        }

        $content = file_get_contents($folder.'/'.$file);

        if (preg_match('~//\s*(TODO|todo)\s*(.+)~', $content, $matches)) {
            $todos[] = $folder.'/'.$file;
        }
    }

    return $todos;
}

print_r(findTODOs($argv[1]));
