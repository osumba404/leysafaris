<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\FooterLink;
use App\Models\HeroSlide;
use App\Models\NavItem;
use App\Models\Package;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReorderController extends Controller
{
    /** @var array<string, array{model: class-string<Model>, table: string}> */
    private const RESOURCES = [
        'hero-slides' => ['model' => HeroSlide::class, 'table' => 'hero_slides'],
        'nav-items' => ['model' => NavItem::class, 'table' => 'nav_items'],
        'footer-links' => ['model' => FooterLink::class, 'table' => 'footer_links'],
        'packages' => ['model' => Package::class, 'table' => 'packages'],
        'destinations' => ['model' => Destination::class, 'table' => 'destinations'],
        'experiences' => ['model' => Experience::class, 'table' => 'experiences'],
        'testimonials' => ['model' => Testimonial::class, 'table' => 'testimonials'],
    ];

    public function __construct()
    {
        $this->middleware('admin');
    }

    public function __invoke(Request $request, string $resource): JsonResponse
    {
        $config = self::RESOURCES[$resource] ?? abort(404);

        /** @var class-string<Model> $modelClass */
        $modelClass = $config['model'];
        $table = $config['table'];

        $validated = $request->validate([
            'order' => ['required', 'array', 'min:1'],
            'order.*' => ['integer', Rule::exists($table, 'id')],
            'group' => ['nullable', 'string', 'max:100'],
        ]);

        $ids = $validated['order'];
        $query = $modelClass::query()->whereIn('id', $ids);

        if ($resource === 'footer-links' && ! empty($validated['group'])) {
            $query->where('group', $validated['group']);
        }

        if ($query->count() !== count($ids)) {
            abort(422, 'Invalid sort order.');
        }

        foreach ($ids as $index => $id) {
            $modelClass::query()->whereKey($id)->update(['sort_order' => $index]);
        }

        return response()->json(['ok' => true]);
    }
}
