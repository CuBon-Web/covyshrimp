<?php

use Illuminate\Foundation\Inspiring;
use App\models\product\Product;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('products:clean-unicode {--dry-run : Preview only, no database writes}', function () {
    $isDryRun = (bool) $this->option('dry-run');
    $jsonFields = ['images', 'description', 'content', 'size', 'variant', 'preserve', 'tags', 'species'];
    $plainFields = ['name', 'seo_title', 'meta_description', 'focus_keyword'];
    $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

    $decodeUnicodeEscapes = function ($value) {
        if (!is_string($value) || $value === '' || strpos($value, '\u') === false) {
            return $value;
        }

        $decoded = json_decode('"' . addcslashes($value, "\\\"/\n\r\t\f\b") . '"');
        return is_string($decoded) ? $decoded : $value;
    };

    $decodeRecursive = function ($value) use (&$decodeRecursive, $decodeUnicodeEscapes) {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = $decodeRecursive($v);
            }
            return $value;
        }
        if (is_object($value)) {
            foreach ($value as $k => $v) {
                $value->{$k} = $decodeRecursive($v);
            }
            return $value;
        }
        return $decodeUnicodeEscapes($value);
    };

    $scanned = 0;
    $changed = 0;
    $updated = 0;

    Product::query()
        ->select(array_merge(['id'], $jsonFields, $plainFields))
        ->orderBy('id')
        ->chunkById(200, function ($products) use (
            &$scanned,
            &$changed,
            &$updated,
            $isDryRun,
            $jsonFields,
            $plainFields,
            $jsonFlags,
            $decodeUnicodeEscapes,
            $decodeRecursive
        ) {
            foreach ($products as $product) {
                $scanned++;
                $dirty = false;

                foreach ($plainFields as $field) {
                    $original = $product->{$field};
                    if (!is_string($original) || $original === '') {
                        continue;
                    }

                    $decoded = $decodeUnicodeEscapes($original);
                    if ($decoded !== $original) {
                        $product->{$field} = $decoded;
                        $dirty = true;
                    }
                }

                foreach ($jsonFields as $field) {
                    $original = $product->{$field};
                    if (!is_string($original) || trim($original) === '') {
                        continue;
                    }

                    $decodedJson = json_decode($original, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $decodedString = $decodeUnicodeEscapes($original);
                        if ($decodedString !== $original) {
                            $product->{$field} = $decodedString;
                            $dirty = true;
                        }
                        continue;
                    }

                    $normalized = $decodeRecursive($decodedJson);
                    $reEncoded = json_encode($normalized, $jsonFlags);
                    if (is_string($reEncoded) && $reEncoded !== $original) {
                        $product->{$field} = $reEncoded;
                        $dirty = true;
                    }
                }

                if ($dirty) {
                    $changed++;
                    if (!$isDryRun) {
                        $product->save();
                        $updated++;
                    }
                }
            }
        });

    $this->info('Done cleaning product unicode escapes.');
    $this->line('Scanned: ' . $scanned);
    $this->line('Changed: ' . $changed);
    $this->line('Updated: ' . ($isDryRun ? 0 : $updated));
    $this->line('Mode: ' . ($isDryRun ? 'dry-run' : 'write'));
})->describe('Decode legacy unicode escape sequences in products table');
