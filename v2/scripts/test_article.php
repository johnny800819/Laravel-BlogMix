<?php
try {
    echo "Attempting to create Article...\n";
    $a = \App\Models\Article::create([
        'category_id' => 1,
        'title' => 'Test Article',
        'content' => 'Content',
        'is_published' => 1
    ]);
    echo "Created: " . $a->id . "\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if ($e instanceof \Illuminate\Database\QueryException) {
        echo "SQL: " . $e->getSql() . "\n";
    }
}
