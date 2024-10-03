<?php

spl_autoload_register(function ($class) {
    $prefix = 'Bot\\';
    $base_dir = __DIR__ . '/class/';

    // Controlla se la classe usa il namespace "Bot"
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Se non usa "Bot", passa a null
        return;
    }

    // Prendi il nome relativo della classe
    $relative_class = substr($class, $len);

    // Sostituisci i namespace con le directory, aggiungi ".php"
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Se il file esiste, caricalo
    if (file_exists($file)) {
        require $file;
    }
});
