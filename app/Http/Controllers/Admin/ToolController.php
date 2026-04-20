<?php
// app/Http/Controllers/Admin/ToolController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ToolController extends Controller
{
    public function index()
    {
        $tools = Tool::ordered()->paginate(10);
        return view('admin.tools.index', compact('tools'));
    }

    public function create()
    {
        $icons = [
            'fab fa-laravel' => 'Laravel',
            'fab fa-react' => 'React',
            'fab fa-vuejs' => 'Vue.js',
            'fab fa-js' => 'JavaScript',
            'fab fa-php' => 'PHP',
            'fab fa-python' => 'Python',
            'fab fa-node' => 'Node.js',
            'fab fa-wordpress' => 'WordPress',
            'fab fa-figma' => 'Figma',
            'fab fa-git-alt' => 'Git',
            'fab fa-docker' => 'Docker',
            'fab fa-aws' => 'AWS',
            'fas fa-database' => 'Base de données',
            'fas fa-cloud' => 'Cloud',
            'fas fa-mobile-alt' => 'Mobile',
            'fas fa-globe' => 'Web',
            'fas fa-shield-alt' => 'Sécurité',
            'fas fa-chart-line' => 'Analytics',
            'fas fa-search' => 'SEO',
            'fas fa-envelope' => 'Email',
            'fas fa-users' => 'Collaboration',
            'fas fa-code' => 'Développement',
            'fas fa-paint-brush' => 'Design',
            'fas fa-cogs' => 'Outils'
        ];

        $categories = [
            'frontend' => 'Frontend',
            'backend' => 'Backend',
            'database' => 'Base de données',
            'devops' => 'DevOps',
            'design' => 'Design',
            'mobile' => 'Mobile',
            'seo' => 'SEO',
            'marketing' => 'Marketing',
            'productivite' => 'Productivité'
        ];

        $iconColors = [
            'indigo' => 'Indigo (#6366f1)',
            'cyan' => 'Cyan (#06b6d4)',
            'emerald' => 'Emeraude (#10b981)',
            'rose' => 'Rose (#f43f5e)',
            'amber' => 'Ambre (#f59e0b)',
            'violet' => 'Violet (#8b5cf6)',
            'blue' => 'Bleu (#3b82f6)',
            'red' => 'Rouge (#ef4444)',
            'green' => 'Vert (#22c55e)',
            'orange' => 'Orange (#f97316)'
        ];

        return view('admin.tools.create', compact('icons', 'categories', 'iconColors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'icon_color' => 'nullable|string',
            'category' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'website_url' => 'nullable|url',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('tools', 'public');
            $data['logo'] = $path;
        }

        Tool::create($data);

        return redirect()->route('admin.tools.index')
            ->with('success', 'Outil créé avec succès');
    }

    public function edit(Tool $tool)
    {
        $icons = [
            'fab fa-laravel' => 'Laravel',
            'fab fa-react' => 'React',
            'fab fa-vuejs' => 'Vue.js',
            'fab fa-js' => 'JavaScript',
            'fab fa-php' => 'PHP',
            'fab fa-python' => 'Python',
            'fab fa-node' => 'Node.js',
            'fab fa-wordpress' => 'WordPress',
            'fab fa-figma' => 'Figma',
            'fab fa-git-alt' => 'Git',
            'fab fa-docker' => 'Docker',
            'fab fa-aws' => 'AWS',
            'fas fa-database' => 'Base de données',
            'fas fa-cloud' => 'Cloud',
            'fas fa-mobile-alt' => 'Mobile',
            'fas fa-globe' => 'Web',
            'fas fa-shield-alt' => 'Sécurité',
            'fas fa-chart-line' => 'Analytics',
            'fas fa-search' => 'SEO',
            'fas fa-envelope' => 'Email',
            'fas fa-users' => 'Collaboration',
            'fas fa-code' => 'Développement',
            'fas fa-paint-brush' => 'Design',
            'fas fa-cogs' => 'Outils'
        ];

        $categories = [
            'frontend' => 'Frontend',
            'backend' => 'Backend',
            'database' => 'Base de données',
            'devops' => 'DevOps',
            'design' => 'Design',
            'mobile' => 'Mobile',
            'seo' => 'SEO',
            'marketing' => 'Marketing',
            'productivite' => 'Productivité'
        ];

        $iconColors = [
            'indigo' => 'Indigo (#6366f1)',
            'cyan' => 'Cyan (#06b6d4)',
            'emerald' => 'Emeraude (#10b981)',
            'rose' => 'Rose (#f43f5e)',
            'amber' => 'Ambre (#f59e0b)',
            'violet' => 'Violet (#8b5cf6)',
            'blue' => 'Bleu (#3b82f6)',
            'red' => 'Rouge (#ef4444)',
            'green' => 'Vert (#22c55e)',
            'orange' => 'Orange (#f97316)'
        ];

        return view('admin.tools.edit', compact('tool', 'icons', 'categories', 'iconColors'));
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'icon_color' => 'nullable|string',
            'category' => 'nullable|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'website_url' => 'nullable|url',
            'order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            if ($tool->logo) {
                Storage::disk('public')->delete($tool->logo);
            }
            $path = $request->file('logo')->store('tools', 'public');
            $data['logo'] = $path;
        }

        $tool->update($data);

        return redirect()->route('admin.tools.index')
            ->with('success', 'Outil mis à jour avec succès');
    }

    public function destroy(Tool $tool)
    {
        if ($tool->logo) {
            Storage::disk('public')->delete($tool->logo);
        }
        $tool->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.tools.index')
            ->with('success', 'Outil supprimé avec succès');
    }

    public function toggleActive(Tool $tool)
    {
        $tool->update(['is_active' => !$tool->is_active]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.tools.index')
            ->with('success', $tool->is_active ? 'Outil activé' : 'Outil désactivé');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:tools,id'
        ]);

        foreach ($request->order as $index => $toolId) {
            Tool::where('id', $toolId)->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}
