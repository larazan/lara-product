<?php

namespace App\Http\Controllers\Admin;

use App\Models\CategoryLabel;
use App\Models\Label;
use App\Models\Product;
use App\Models\ProductLabel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProductLabelController extends Controller
{
    //
    public function edit(Product $product)
    {
        $categoryLabels = CategoryLabel::query()
            ->with(['label.group'])
            ->where('category_id', $product->category_id)
            ->where('is_active', true)
            ->whereHas('label', function ($query) {
                $query->where('is_active', true);
            })
            ->orderBy('sort_order')
            ->get();

        $productLabels = $product->labels()
            ->get()
            ->keyBy('id');

        $labels = $categoryLabels->map(function ($categoryLabel) use ($productLabels) {
            $label = $categoryLabel->label;
            $existing = $productLabels->get($label->id);

            return [
                'label_id' => $label->id,
                'name' => $label->getTranslations('name'),
                'slug' => $label->slug,
                'icon' => $label->icon,
                'label_type' => $label->label_type,
                'severity' => $label->severity,

                'group' => $label->group
                    ? $label->group->getTranslations('name')
                    : null,

                'is_required' => $categoryLabel->is_required,

                'selected' => $categoryLabel->is_required
                    || (bool) $existing,

                'is_reviewed' => (bool) $existing,
                'is_present' => $existing
                    ? (bool) $existing->pivot->is_present
                    : false,

                'value' => $existing?->pivot->value ?? '',
                'notes' => $existing?->pivot->notes ?? '',
                'source' => $existing?->pivot->source ?? '',

                'is_verified' => $existing
                    ? (bool) $existing->pivot->is_verified
                    : false,
            ];
        });

        return Inertia::render('Admin/Product/Labels', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
            ],
            'categoryLabels' => $labels,
            'locales' => ['en', 'id'],
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'labels' => ['present', 'array'],

            'labels.*.label_id' => [
                'required',
                'integer',
                'distinct',
            ],

            'labels.*.selected' => [
                'required',
                'boolean',
            ],

            'labels.*.is_reviewed' => [
                'required',
                'boolean',
            ],

            'labels.*.is_present' => [
                'required',
                'boolean',
            ],

            'labels.*.value' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'labels.*.notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'labels.*.source' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $categoryLabels = CategoryLabel::query()
            ->where('category_id', $product->category_id)
            ->where('is_active', true)
            ->whereHas('label', function ($query) {
                $query->where('is_active', true);
            })
            ->get()
            ->keyBy('label_id');

        $submittedLabels = collect($validated['labels']);

        // Pastikan semua label yang dikirim memang tersedia
        // pada konfigurasi kategori produk ini.
        foreach ($submittedLabels as $item) {
            if (!$categoryLabels->has($item['label_id'])) {
                throw ValidationException::withMessages([
                    'labels' => 'Terdapat label yang tidak tersedia pada kategori produk ini.',
                ]);
            }
        }

        // Semua label wajib harus dikirim, termasuk yang
        // sudah diperiksa tetapi tidak dimiliki produk.
        $submittedIds = $submittedLabels
            ->pluck('label_id')
            ->map(fn($id) => (int) $id);

        $missingRequired = $categoryLabels
            ->filter(fn($item) => $item->is_required)
            ->pluck('label_id')
            ->diff($submittedIds);

        if ($missingRequired->isNotEmpty()) {
            throw ValidationException::withMessages([
                'labels' => 'Semua label wajib harus diperiksa.',
            ]);
        }

        DB::transaction(function () use (
            $product,
            $submittedLabels,
            $categoryLabels
        ) {
            foreach ($submittedLabels as $item) {
                $labelId = (int) $item['label_id'];
                $categoryLabel = $categoryLabels->get($labelId);

                $selected = (bool) $item['selected'];

                if (!$selected && !$categoryLabel->is_required) {
                    // Opsional dan tidak dipilih: hapus record lama.
                    ProductLabel::query()
                        ->where('product_id', $product->id)
                        ->where('label_id', $labelId)
                        ->delete();

                    continue;
                }

                $existing = ProductLabel::query()
                    ->where('product_id', $product->id)
                    ->where('label_id', $labelId)
                    ->first();

                $attributes = [
                    'is_present' => (bool) $item['is_present'],
                    'value' => $item['value'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'source' => $item['source'] ?? null,
                ];

                $changed = $existing && collect($attributes)->contains(
                    fn($value, $key) => $existing->{$key} != $value
                );

                if ($existing) {
                    $existing->update([
                        ...$attributes,

                        // Perubahan data membatalkan verifikasi lama.
                        'is_verified' => $changed
                            ? false
                            : $existing->is_verified,

                        'verified_at' => $changed
                            ? null
                            : $existing->verified_at,
                    ]);
                } else {
                    ProductLabel::create([
                        'product_id' => $product->id,
                        'label_id' => $labelId,
                        ...$attributes,
                        'is_verified' => false,
                        'verified_at' => null,
                    ]);
                }
            }
        });

        return back()->with(
            'success',
            'Product labels berhasil disimpan.'
        );
    }
}
