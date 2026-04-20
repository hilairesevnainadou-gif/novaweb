<?php
// app/Http/Controllers/Admin/FaqController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::ordered()->paginate(10);
        $categories = Faq::select('category')->distinct()->pluck('category');
        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $categories = [
            'general' => 'Général',
            'services' => 'Services',
            'tarifs' => 'Tarifs',
            'technique' => 'Technique',
            'support' => 'Support'
        ];
        return view('admin.faqs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        Faq::create($request->all());

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ créée avec succès');
    }

    public function edit(Faq $faq)
    {
        $categories = [
            'general' => 'Général',
            'services' => 'Services',
            'tarifs' => 'Tarifs',
            'technique' => 'Technique',
            'support' => 'Support'
        ];
        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'category' => 'required|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $faq->update($request->all());

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ mise à jour avec succès');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ supprimée avec succès');
    }

    public function toggleActive(Faq $faq)
    {
        $faq->update(['is_active' => !$faq->is_active]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.faqs.index')
            ->with('success', $faq->is_active ? 'FAQ activée' : 'FAQ désactivée');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:faqs,id'
        ]);

        foreach ($request->order as $index => $faqId) {
            Faq::where('id', $faqId)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
