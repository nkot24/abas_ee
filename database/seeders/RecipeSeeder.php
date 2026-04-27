<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        Recipe::truncate();

        $xml = simplexml_load_file(
            'C:/Users/niksi/Downloads/abaskpintava.WordPress.2026-04-23.xml',
            'SimpleXMLElement',
            LIBXML_NOCDATA
        );

        $seen = [];

        foreach ($xml->channel->item as $item) {
            $wp = $item->children('wp', true);

            if ((string) $wp->post_type !== 'rc_recipe') continue;
            if ((string) $wp->status !== 'publish') continue;

            $lang = '';
            foreach ($item->category as $cat) {
                if ((string) $cat->attributes()['domain'] === 'language') {
                    $lang = (string) $cat;
                    break;
                }
            }
            if ($lang !== 'Latviešu valoda') continue;

            $title = trim((string) $item->title);
            $normalised = rtrim(mb_strtolower($title, 'UTF-8'), '.');
            if (isset($seen[$normalised])) continue;
            $seen[$normalised] = true;

            // ── category ──────────────────────────────────────────────
            $wpCats = [];
            foreach ($item->category as $cat) {
                if ((string) $cat->attributes()['domain'] === 'rc_recipe_category') {
                    $wpCats[] = mb_strtolower((string) $cat, 'UTF-8');
                }
            }

            $category = 'Marinatas';
            if (array_intersect(['zivis'], $wpCats)) {
                $category = 'Žuvis';
            } elseif (array_intersect(['burgers', 'malta gaļa'], $wpCats)) {
                $category = 'Pagrindinis';
            }

            // post title hints for category
            $lTitle = mb_strtolower($title, 'UTF-8');
            if (str_contains($lTitle, 'burgers') || str_contains($lTitle, 'kebabs') ||
                str_contains($lTitle, 'spārniņ') || str_contains($lTitle, 'vista č') ||
                str_contains($lTitle, 'čillī')) {
                $category = 'Pagrindinis';
            }
            if (str_contains($lTitle, 'zivs') || str_contains($lTitle, 'zivīm') ||
                str_contains($lTitle, 'zivīm') || str_contains($lTitle, 'karstā marināde zivīm')) {
                $category = 'Žuvis';
            }

            // ── raw content ────────────────────────────────────────────
            $contentEl = $item->children('content', true);
            $rawHtml   = (string) $contentEl->encoded;
            $rawText   = html_entity_decode(strip_tags($rawHtml), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $rawText   = preg_replace('/[ \t]+/', ' ', $rawText);
            $rawText   = preg_replace('/\n{3,}/', "\n\n", trim($rawText));

            // ── ingredients ────────────────────────────────────────────
            $ingredients = [];

            // Try structured postmeta first
            $structuredFound = false;
            foreach ($wp->postmeta as $meta) {
                if ((string) $meta->meta_key !== 'rc_recipe_ingredient_list') continue;
                $arr = @unserialize((string) $meta->meta_value);
                if (!$arr) continue;
                foreach ($arr as $ing) {
                    $name = trim($ing['ingredient_item'] ?? '');
                    $qty  = trim($ing['ingredient_quantity'] ?? '');
                    $unit = trim($ing['ingredient_unit'] ?? '');
                    if (!$name) continue;
                    $parts = array_filter([$name, $qty ?: null, $unit ?: null]);
                    $ingredients[] = ['item' => implode(' — ', $parts)];
                }
                $structuredFound = true;
                break;
            }

            // Fall back: parse "Sastāvdaļas" block from content
            if (!$structuredFound && $rawText) {
                if (preg_match('/Sast[āa]vda[ļl][āa]s[:\.]?\s*/u', $rawText, $m, PREG_OFFSET_CAPTURE)) {
                    $ingBlock = trim(substr($rawText, $m[0][1] + mb_strlen($m[0][0], 'UTF-8')));
                    $ingBlock = ltrim($ingBlock, ': ');
                    foreach (preg_split('/\n+/', $ingBlock) as $line) {
                        $line = trim($line, " \t\r\n:");
                        // Skip sub-headers like "Karamelizētiem sīpoliem:" and very short lines
                        if (mb_strlen($line, 'UTF-8') < 2) continue;
                        if (preg_match('/^[A-ZĀČĒĢĪĶĻŅŠŪŽ][^:]{2,40}:$/u', $line)) continue;
                        $ingredients[] = ['item' => $line];
                    }
                }
            }

            // ── steps ──────────────────────────────────────────────────
            $steps = [];

            // Determine the text that contains only steps (before Sastāvdaļas)
            $stepsText = $rawText;
            if (preg_match('/Sast[āa]vda[ļl][āa]s[:\.]?\s*/u', $rawText, $m, PREG_OFFSET_CAPTURE)) {
                $stepsText = trim(substr($rawText, 0, $m[0][1]));
            }

            if ($stepsText) {
                // Only split on step numbers that appear at the very start of a line
                $lines = preg_split('/\n/', $stepsText);
                $numbered = [];
                foreach ($lines as $line) {
                    if (preg_match('/^\s*\d{1,2}[\. ]\s*\S/u', $line)) {
                        $numbered[] = preg_replace('/^\s*\d{1,2}[\. ]\s*/u', '', $line);
                    }
                }

                if (count($numbered) >= 2) {
                    foreach ($numbered as $s) {
                        $s = trim($s);
                        if (mb_strlen($s, 'UTF-8') > 5) $steps[] = ['step' => $s];
                    }
                } else {
                    // Line-by-line split (each sentence is usually its own line)
                    foreach (preg_split('/\n+/', $stepsText) as $line) {
                        $line = trim($line);
                        if (mb_strlen($line, 'UTF-8') > 10) $steps[] = ['step' => $line];
                    }
                }
            }

            Recipe::create([
                'title'       => $title,
                'category'    => $category,
                'active'      => true,
                'ingredients' => $ingredients,
                'steps'       => $steps,
            ]);

            $this->command->info("Imported: {$title}");
        }
    }
}