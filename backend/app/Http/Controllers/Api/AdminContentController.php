<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminContentController extends Controller
{
    // Dashboard Stats Overview
    public function getDashboardStats()
    {
        $totalNews = DB::table('news_articles')->count();
        $publishedNews = DB::table('news_articles')->where('status', 'published')->count();
        $pendingDrafts = DB::table('news_articles')->where('status', 'pending')->count();
        
        $activeTenders = DB::table('tenders')->where('status', 'open')->count();
        $totalOffices = DB::table('offices')->count();
        $unreadMessages = DB::table('contact_messages')->where('status', 'unread')->count();
        $totalProjects = DB::table('projects')->count();

        return response()->json([
            'news_count' => $totalNews,
            'published_news' => $publishedNews,
            'pending_drafts' => $pendingDrafts,
            'active_tenders' => $activeTenders,
            'offices_count' => $totalOffices,
            'unread_messages' => $unreadMessages,
            'projects_count' => $totalProjects,
        ]);
    }

    // Citizen Inquiries Management
    public function getContactMessages(Request $request)
    {
        $query = DB::table('contact_messages');
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        $messages = $query->orderBy('created_at', 'desc')->paginate(15);
        return response()->json($messages);
    }

    public function updateMessageStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:unread,read,resolved,archived']);
        DB::table('contact_messages')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'Status updated']);
    }

    // Staff Users Management (Super Admin only)
    public function getStaffUsers()
    {
        $users = User::select('id', 'name', 'email', 'role', 'phone', 'avatar_path', 'is_active', 'created_at')->get();
        return response()->json($users);
    }

    public function createStaffUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:super_admin,editor,author',
            'phone' => 'nullable|string|max:30',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        return response()->json(['message' => 'Staff user created successfully', 'user' => $user], 201);
    }

    public function toggleStaffStatus($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json(['message' => 'Status updated', 'is_active' => $user->is_active]);
    }

    public function adminResetStaffPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        // Invalidate all existing sessions
        $user->tokens()->delete();

        return response()->json([
            'message' => "Password for {$user->name} ({$user->email}) has been successfully reset. Any previous active sessions have been revoked."
        ]);
    }

    // News Article Create / Update
    public function storeNews(Request $request)
    {
        $validated = $request->validate([
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'category' => 'required|string',
            'content_am' => 'required|string',
            'content_en' => 'nullable|string',
            'content_om' => 'nullable|string',
            'summary_am' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'summary_om' => 'nullable|string',
            'thumbnail_path' => 'nullable|string',
            'status' => 'required|in:draft,pending,published',
        ]);

        $slug = Str::slug($validated['title_en'] ?? $validated['title_am']) . '-' . rand(100, 999);

        // Auto-generate summary from content if not provided
        $summaryAm = $validated['summary_am'] ?? mb_substr(strip_tags($validated['content_am']), 0, 160) . '...';
        $summaryEn = $validated['summary_en'] ?? (!empty($validated['content_en']) ? mb_substr(strip_tags($validated['content_en']), 0, 160) . '...' : null);

        $user = $request->user();
        $status = $validated['status'];

        // Role Permission Enforcement: Authors cannot publish live directly
        if ($user->role === 'author' && $status === 'published') {
            $status = 'pending'; // Automatically submit for editorial review
        }

        $id = DB::table('news_articles')->insertGetId([
            'author_id' => $user->id,
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'slug' => $slug,
            'category' => $validated['category'],
            'summary_am' => $summaryAm,
            'summary_en' => $summaryEn,
            'summary_om' => $validated['summary_om'] ?? null,
            'content_am' => $validated['content_am'],
            'content_en' => $validated['content_en'] ?? null,
            'content_om' => $validated['content_om'] ?? null,
            'thumbnail_path' => $validated['thumbnail_path'] ?? null,
            'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $msg = ($user->role === 'author' && $validated['status'] === 'published')
            ? 'Article submitted for editorial approval.'
            : 'Article saved successfully';

        return response()->json(['message' => $msg, 'id' => $id, 'status' => $status], 201);
    }

    public function updateNews(Request $request, $id)
    {
        $validated = $request->validate([
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'category' => 'required|string',
            'content_am' => 'required|string',
            'content_en' => 'nullable|string',
            'content_om' => 'nullable|string',
            'summary_am' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'summary_om' => 'nullable|string',
            'thumbnail_path' => 'nullable|string',
            'status' => 'required|in:published,draft,pending,archived',
        ]);

        $user = $request->user();
        $existing = DB::table('news_articles')->where('id', $id)->first();
        if (!$existing) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        $targetStatus = $validated['status'];

        // Role Permission Enforcement per Municipal Matrix:
        if ($user->role === 'author') {
            // Authors cannot edit other authors' content
            if ($existing->author_id && (int)$existing->author_id !== (int)$user->id) {
                return response()->json(['message' => 'Forbidden: Authors can only edit their own content.'], 403);
            }
            // Authors cannot modify published content
            if ($existing->status === 'published') {
                return response()->json(['message' => 'Forbidden: Published articles can only be edited or unpublished by an Editor or Super Admin.'], 403);
            }
            // Authors cannot publish directly
            if ($targetStatus === 'published') {
                $targetStatus = 'pending';
            }
        }

        $summaryAm = $validated['summary_am'] ?? mb_substr(strip_tags($validated['content_am']), 0, 160) . '...';
        $summaryEn = $validated['summary_en'] ?? (!empty($validated['content_en']) ? mb_substr(strip_tags($validated['content_en']), 0, 160) . '...' : null);

        $updateData = [
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'category' => $validated['category'],
            'summary_am' => $summaryAm,
            'summary_en' => $summaryEn,
            'summary_om' => $validated['summary_om'] ?? null,
            'content_am' => $validated['content_am'],
            'content_en' => $validated['content_en'] ?? null,
            'content_om' => $validated['content_om'] ?? null,
            'thumbnail_path' => $validated['thumbnail_path'] ?? null,
            'status' => $targetStatus,
            'updated_at' => now(),
        ];

        if ($targetStatus === 'published' && empty($existing->published_at)) {
            $updateData['published_at'] = now();
        }

        DB::table('news_articles')->where('id', $id)->update($updateData);

        return response()->json(['message' => 'Article updated successfully', 'status' => $targetStatus]);
    }

    public function deleteNews(Request $request, $id)
    {
        $user = $request->user();
        $existing = DB::table('news_articles')->where('id', $id)->first();
        if (!$existing) {
            return response()->json(['message' => 'Article not found'], 404);
        }

        // Author Role Enforcement: Own drafts only, subject to policy
        if ($user->role === 'author') {
            if ($existing->author_id && (int)$existing->author_id !== (int)$user->id) {
                return response()->json(['message' => 'Forbidden: Authors can only delete their own content.'], 403);
            }
            if ($existing->status === 'published') {
                return response()->json(['message' => 'Forbidden: Authors cannot delete published articles. Contact an Editor.'], 403);
            }
        }

        DB::table('news_articles')->where('id', $id)->delete();
        return response()->json(['message' => 'Article deleted successfully']);
    }

    // Tenders Management
    public function storeTender(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required|string',
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'category' => 'required|string',
            'bid_bond_amount' => 'nullable|numeric',
            'closing_datetime' => 'required|date',
            'status' => 'required|in:open,closed,cancelled',
            'document_pdf_path' => 'nullable|string',
        ]);

        $id = DB::table('tenders')->insertGetId([
            'reference_number' => $validated['reference_number'],
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'category' => $validated['category'],
            'bid_bond_amount' => $validated['bid_bond_amount'] ?? 0,
            'closing_datetime' => $validated['closing_datetime'],
            'status' => $validated['status'],
            'document_pdf_path' => $validated['document_pdf_path'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Tender published successfully', 'id' => $id], 201);
    }

    public function updateTender(Request $request, $id)
    {
        $validated = $request->validate([
            'reference_number' => 'required|string',
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'category' => 'required|string',
            'bid_bond_amount' => 'nullable|numeric',
            'closing_datetime' => 'required|date',
            'status' => 'required|in:open,closed,cancelled',
            'document_pdf_path' => 'nullable|string',
        ]);

        DB::table('tenders')->where('id', $id)->update([
            'reference_number' => $validated['reference_number'],
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'category' => $validated['category'],
            'bid_bond_amount' => $validated['bid_bond_amount'] ?? 0,
            'closing_datetime' => $validated['closing_datetime'],
            'status' => $validated['status'],
            'document_pdf_path' => $validated['document_pdf_path'] ?? null,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Tender updated successfully']);
    }

    public function deleteTender($id)
    {
        DB::table('tenders')->where('id', $id)->delete();
        return response()->json(['message' => 'Tender deleted successfully']);
    }

    // Vacancies Management
    public function storeVacancy(Request $request)
    {
        $validated = $request->validate([
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'position_code' => 'nullable|string',
            'employment_type' => 'required|string',
            'salary_grade' => 'nullable|string',
            'requirements_am' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $status = $request->input('status');
        if (!$status) {
            $status = (strtotime($validated['deadline']) < strtotime(date('Y-m-d'))) ? 'closed' : 'open';
        }

        $id = DB::table('vacancies')->insertGetId([
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'position_code' => $validated['position_code'] ?? 'KMB-CIV-2026',
            'employment_type' => $validated['employment_type'],
            'salary_grade' => $validated['salary_grade'] ?? 'Government Scale',
            'requirements_am' => $validated['requirements_am'],
            'deadline' => $validated['deadline'],
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Vacancy posted successfully', 'id' => $id], 201);
    }

    public function updateVacancy(Request $request, $id)
    {
        $validated = $request->validate([
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'position_code' => 'nullable|string',
            'employment_type' => 'required|string',
            'salary_grade' => 'nullable|string',
            'requirements_am' => 'required|string',
            'deadline' => 'required|date',
            'status' => 'nullable|in:open,closed',
        ]);

        $status = $validated['status'] ?? null;
        if (!$status) {
            $status = (strtotime($validated['deadline']) < strtotime(date('Y-m-d'))) ? 'closed' : 'open';
        }

        DB::table('vacancies')->where('id', $id)->update([
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'position_code' => $validated['position_code'] ?? 'KMB-CIV-2026',
            'employment_type' => $validated['employment_type'],
            'salary_grade' => $validated['salary_grade'] ?? 'Government Scale',
            'requirements_am' => $validated['requirements_am'],
            'deadline' => $validated['deadline'],
            'status' => $status,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Vacancy updated successfully']);
    }

    public function deleteVacancy($id)
    {
        DB::table('vacancies')->where('id', $id)->delete();
        return response()->json(['message' => 'Vacancy deleted successfully']);
    }

    // Services Management
    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'type' => 'required|in:text,step',
            'summary_am' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'summary_om' => 'nullable|string',
            'fee_amount' => 'nullable|numeric',
            'estimated_time_am' => 'nullable|string',
            'estimated_time_en' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['title_en'] ?? $validated['title_am']) . '-' . rand(100, 999);

        $id = DB::table('services')->insertGetId([
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'slug' => $slug,
            'type' => $validated['type'],
            'summary_am' => $validated['summary_am'] ?? null,
            'summary_en' => $validated['summary_en'] ?? null,
            'summary_om' => $validated['summary_om'] ?? null,
            'fee_amount' => $validated['fee_amount'] ?? 0,
            'estimated_time_am' => $validated['estimated_time_am'] ?? '1-3 Business Days',
            'estimated_time_en' => $validated['estimated_time_en'] ?? $validated['estimated_time_am'] ?? '1-3 Business Days',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Service created successfully', 'id' => $id], 201);
    }

    public function updateService(Request $request, $id)
    {
        $validated = $request->validate([
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'type' => 'required|in:text,step',
            'summary_am' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'summary_om' => 'nullable|string',
            'fee_amount' => 'nullable|numeric',
            'estimated_time_am' => 'nullable|string',
            'estimated_time_en' => 'nullable|string',
        ]);

        DB::table('services')->where('id', $id)->update([
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'type' => $validated['type'],
            'summary_am' => $validated['summary_am'] ?? null,
            'summary_en' => $validated['summary_en'] ?? null,
            'summary_om' => $validated['summary_om'] ?? null,
            'fee_amount' => $validated['fee_amount'] ?? 0,
            'estimated_time_am' => $validated['estimated_time_am'] ?? '1-3 Business Days',
            'estimated_time_en' => $validated['estimated_time_en'] ?? $validated['estimated_time_am'] ?? '1-3 Business Days',
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Service updated successfully']);
    }

    public function deleteService($id)
    {
        DB::table('services')->where('id', $id)->delete();
        return response()->json(['message' => 'Service deleted successfully']);
    }

    // Infrastructure Projects Management
    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'budget_etb' => 'nullable|numeric',
            'contractor_name' => 'nullable|string',
            'progress_percent' => 'required|integer|min:0|max:100',
            'status' => 'required|in:planning,in_progress,completed,halted',
        ]);

        $slug = Str::slug($validated['title_en'] ?? $validated['title_am']) . '-' . rand(100, 999);

        $id = DB::table('projects')->insertGetId([
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'slug' => $slug,
            'budget_etb' => $validated['budget_etb'] ?? 0,
            'contractor_name' => $validated['contractor_name'] ?? null,
            'progress_percent' => $validated['progress_percent'],
            'status' => $validated['status'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Project saved successfully', 'id' => $id], 201);
    }

    public function updateProject(Request $request, $id)
    {
        $validated = $request->validate([
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'budget_etb' => 'nullable|numeric',
            'contractor_name' => 'nullable|string',
            'progress_percent' => 'required|integer|min:0|max:100',
            'status' => 'required|in:planning,in_progress,completed,halted',
        ]);

        DB::table('projects')->where('id', $id)->update([
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'budget_etb' => $validated['budget_etb'] ?? 0,
            'contractor_name' => $validated['contractor_name'] ?? null,
            'progress_percent' => $validated['progress_percent'],
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Project updated successfully']);
    }

    public function updateProjectProgress(Request $request, $id)
    {
        $request->validate(['progress_percent' => 'required|integer|min:0|max:100']);
        DB::table('projects')->where('id', $id)->update([
            'progress_percent' => $request->progress_percent,
            'status' => $request->progress_percent == 100 ? 'completed' : 'in_progress',
            'updated_at' => now(),
        ]);
        return response()->json(['message' => 'Progress updated']);
    }

    public function deleteProject($id)
    {
        DB::table('projects')->where('id', $id)->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }

    // Resources & Directives Management
    public function storeResource(Request $request)
    {
        $validated = $request->validate([
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'category' => 'required|string',
            'year' => 'required|integer',
            'file_path' => 'nullable|string',
            'file_size_formatted' => 'nullable|string',
        ]);

        $id = DB::table('resources')->insertGetId([
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'category' => $validated['category'],
            'year' => $validated['year'],
            'file_path' => $validated['file_path'] ?: ('/documents/kombolcha_directive_' . rand(10, 99) . '.pdf'),
            'file_size_formatted' => $validated['file_size_formatted'] ?? '2.1 MB',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Resource directive cataloged successfully', 'id' => $id], 201);
    }

    public function updateResource(Request $request, $id)
    {
        $validated = $request->validate([
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'category' => 'required|string',
            'year' => 'required|integer',
            'file_path' => 'nullable|string',
            'file_size_formatted' => 'nullable|string',
        ]);

        $updateData = [
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'category' => $validated['category'],
            'year' => $validated['year'],
            'file_size_formatted' => $validated['file_size_formatted'] ?? '2.1 MB',
            'updated_at' => now(),
        ];
        if (!empty($validated['file_path'])) {
            $updateData['file_path'] = $validated['file_path'];
        }

        DB::table('resources')->where('id', $id)->update($updateData);

        return response()->json(['message' => 'Resource directive updated successfully']);
    }

    public function deleteResource($id)
    {
        DB::table('resources')->where('id', $id)->delete();
        return response()->json(['message' => 'Resource deleted successfully']);
    }

    // Mayor & Executive Profile Management
    public function updateMayorProfile(Request $request)
    {
        $validated = $request->validate([
            'name_am' => 'required|string',
            'name_en' => 'nullable|string',
            'name_om' => 'nullable|string',
            'welcome_message_am' => 'required|string',
            'welcome_message_en' => 'nullable|string',
            'welcome_message_om' => 'nullable|string',
            'biography_am' => 'nullable|string',
            'biography_en' => 'nullable|string',
            'biography_om' => 'nullable|string',
            'portrait_path' => 'nullable|string',
            'term_start' => 'nullable|string',
            'term_end' => 'nullable|string',
        ]);

        DB::table('mayors')->where('is_current', true)->update(array_merge($validated, [
            'updated_at' => now()
        ]));

        return response()->json(['message' => 'Mayor profile and keynote updated successfully']);
    }

    // Leadership Members Management
    public function storeLeadershipMember(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:cabinet,council',
            'name_am' => 'required|string',
            'name_en' => 'nullable|string',
            'name_om' => 'nullable|string',
            'position_am' => 'required|string',
            'position_en' => 'nullable|string',
            'position_om' => 'nullable|string',
            'photo_path' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $validated['name_en'] = !empty($validated['name_en']) ? $validated['name_en'] : $validated['name_am'];
        $validated['name_om'] = !empty($validated['name_om']) ? $validated['name_om'] : ($validated['name_en'] ?? $validated['name_am']);
        $validated['position_en'] = !empty($validated['position_en']) ? $validated['position_en'] : $validated['position_am'];
        $validated['position_om'] = !empty($validated['position_om']) ? $validated['position_om'] : ($validated['position_en'] ?? $validated['position_am']);

        $id = DB::table('leadership_members')->insertGetId(array_merge($validated, [
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return response()->json(['message' => 'Leadership member added successfully', 'id' => $id], 201);
    }

    public function updateLeadershipMember(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:cabinet,council',
            'name_am' => 'required|string',
            'name_en' => 'nullable|string',
            'name_om' => 'nullable|string',
            'position_am' => 'required|string',
            'position_en' => 'nullable|string',
            'position_om' => 'nullable|string',
            'photo_path' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        $validated['name_en'] = !empty($validated['name_en']) ? $validated['name_en'] : $validated['name_am'];
        $validated['name_om'] = !empty($validated['name_om']) ? $validated['name_om'] : ($validated['name_en'] ?? $validated['name_am']);
        $validated['position_en'] = !empty($validated['position_en']) ? $validated['position_en'] : $validated['position_am'];
        $validated['position_om'] = !empty($validated['position_om']) ? $validated['position_om'] : ($validated['position_en'] ?? $validated['position_am']);

        DB::table('leadership_members')->where('id', $id)->update(array_merge($validated, [
            'updated_at' => now()
        ]));

        return response()->json(['message' => 'Leadership member updated successfully']);
    }

    public function deleteLeadershipMember($id)
    {
        DB::table('leadership_members')->where('id', $id)->delete();
        return response()->json(['message' => 'Member deleted successfully']);
    }

    // Municipal Offices Management
    public function storeOffice(Request $request)
    {
        $validated = $request->validate([
            'name_am' => 'required|string',
            'name_en' => 'nullable|string',
            'name_om' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'building_location_am' => 'nullable|string',
            'room_number' => 'nullable|string',
        ]);

        $validated['name_en'] = !empty($validated['name_en']) ? $validated['name_en'] : $validated['name_am'];
        $validated['name_om'] = !empty($validated['name_om']) ? $validated['name_om'] : ($validated['name_en'] ?? $validated['name_am']);

        $slug = Str::slug($validated['name_en'] ?? $validated['name_am']) . '-' . rand(10, 99);

        $id = DB::table('offices')->insertGetId(array_merge($validated, [
            'slug' => $slug,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]));

        return response()->json(['message' => 'Municipal office registered successfully', 'id' => $id], 201);
    }

    public function updateOffice(Request $request, $id)
    {
        $validated = $request->validate([
            'name_am' => 'required|string',
            'name_en' => 'nullable|string',
            'name_om' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'building_location_am' => 'nullable|string',
            'room_number' => 'nullable|string',
        ]);

        $validated['name_en'] = !empty($validated['name_en']) ? $validated['name_en'] : $validated['name_am'];
        $validated['name_om'] = !empty($validated['name_om']) ? $validated['name_om'] : ($validated['name_en'] ?? $validated['name_am']);

        DB::table('offices')->where('id', $id)->update(array_merge($validated, [
            'updated_at' => now()
        ]));

        return response()->json(['message' => 'Municipal office updated successfully']);
    }

    public function deleteOffice($id)
    {
        DB::table('offices')->where('id', $id)->delete();
        return response()->json(['message' => 'Office deleted successfully']);
    }

    // Site Settings & Maintenance Mode
    public function getSiteSettings()
    {
        $settings = DB::table('site_settings')->first();
        if ($settings) {
            $settings->is_maintenance = (bool)$settings->is_maintenance;
        }
        return response()->json($settings);
    }

    public function updateSiteSettings(Request $request)
    {
        $data = $request->only([
            'site_name_am', 'site_name_en', 'site_name_om',
            'contact_phone', 'contact_email', 'emergency_notice_am',
            'emergency_notice_en', 'emergency_notice_om'
        ]);

        if ($request->has('is_maintenance')) {
            $data['is_maintenance'] = $request->boolean('is_maintenance') ? 1 : 0;
        }

        $data['updated_at'] = now();

        $exists = DB::table('site_settings')->first();
        if ($exists) {
            DB::table('site_settings')->where('id', $exists->id)->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('site_settings')->insert($data);
        }

        $updated = DB::table('site_settings')->first();
        if ($updated) {
            $updated->is_maintenance = (bool)$updated->is_maintenance;
        }

        return response()->json([
            'message' => 'Site settings and maintenance configuration updated',
            'settings' => $updated
        ]);
    }

    // Media File & Image/Video Upload Handler
    public function uploadMedia(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200' // 50MB max for video & high-res photos
        ]);

        $file = $request->file('file');
        $uploadDir = public_path('uploads');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = $file->getClientOriginalExtension();
        $filename = time() . '_' . Str::random(10) . '.' . $extension;
        $file->move($uploadDir, $filename);

        $path = '/uploads/' . $filename;
        $url = url($path);

        return response()->json([
            'message' => 'File uploaded successfully',
            'filename' => $filename,
            'path' => $path,
            'url' => $url,
            'original_name' => $file->getClientOriginalName()
        ], 201);
    }

    // Gallery Items Management (Images & Videos)
    public function getGallery()
    {
        $items = DB::table('gallery_items')
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($items);
    }

    public function storeGalleryItem(Request $request)
    {
        $request->validate([
            'media_path_or_url' => 'required|string',
            'media_type' => 'required|in:image,video',
            'caption_am' => 'required|string|max:255',
            'caption_en' => 'nullable|string|max:255',
            'caption_om' => 'nullable|string|max:255',
        ]);

        $id = DB::table('gallery_items')->insertGetId([
            'media_type' => $request->media_type,
            'media_path_or_url' => $request->media_path_or_url,
            'thumbnail_url' => $request->thumbnail_url ?: ($request->media_type === 'image' ? $request->media_path_or_url : '/assets/hero_industry.jpg'),
            'category' => $request->category ?: 'urban',
            'caption_am' => $request->caption_am,
            'caption_en' => $request->caption_en,
            'caption_om' => $request->caption_om,
            'order' => $request->order ?: 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Gallery media item created successfully', 'id' => $id], 201);
    }

    public function updateGalleryItem(Request $request, $id)
    {
        $request->validate([
            'media_path_or_url' => 'required|string',
            'media_type' => 'required|in:image,video',
            'caption_am' => 'required|string|max:255',
        ]);

        DB::table('gallery_items')->where('id', $id)->update([
            'media_type' => $request->media_type,
            'media_path_or_url' => $request->media_path_or_url,
            'thumbnail_url' => $request->thumbnail_url ?: ($request->media_type === 'image' ? $request->media_path_or_url : '/assets/hero_industry.jpg'),
            'category' => $request->category ?: 'urban',
            'caption_am' => $request->caption_am,
            'caption_en' => $request->caption_en,
            'caption_om' => $request->caption_om,
            'order' => $request->order ?: 0,
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Gallery media item updated successfully']);
    }

    public function deleteGalleryItem($id)
    {
        DB::table('gallery_items')->where('id', $id)->delete();
        return response()->json(['message' => 'Gallery media item deleted']);
    }

    // Hero Carousel Slides Management
    public function getHeroSlides()
    {
        $slides = DB::table('hero_slides')->orderBy('order', 'asc')->get();
        return response()->json($slides);
    }

    public function storeHeroSlide(Request $request)
    {
        $request->validate([
            'title_am' => 'required|string|max:255',
            'image_path' => 'required|string',
        ]);

        $id = DB::table('hero_slides')->insertGetId([
            'title_am' => $request->title_am,
            'title_en' => $request->title_en,
            'title_om' => $request->title_om,
            'subtitle_am' => $request->subtitle_am ?: $request->desc_am,
            'subtitle_en' => $request->subtitle_en ?: $request->desc_en,
            'subtitle_om' => $request->subtitle_om ?: $request->desc_om,
            'button_text_am' => $request->button_text_am ?: $request->cta_am,
            'button_text_en' => $request->button_text_en ?: $request->cta_en,
            'button_text_om' => $request->button_text_om ?: $request->cta_om,
            'button_url' => $request->button_url ?: ($request->cta_link ?: '/explore'),
            'image_path' => $request->image_path,
            'tag_am' => $request->tag_am,
            'tag_en' => $request->tag_en,
            'tag_om' => $request->tag_om,
            'order' => $request->order ?: 0,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Hero slide created successfully', 'id' => $id], 201);
    }

    public function updateHeroSlide(Request $request, $id)
    {
        $request->validate([
            'title_am' => 'required|string|max:255',
            'image_path' => 'required|string',
        ]);

        DB::table('hero_slides')->where('id', $id)->update([
            'title_am' => $request->title_am,
            'title_en' => $request->title_en,
            'title_om' => $request->title_om,
            'subtitle_am' => $request->subtitle_am ?: $request->desc_am,
            'subtitle_en' => $request->subtitle_en ?: $request->desc_en,
            'subtitle_om' => $request->subtitle_om ?: $request->desc_om,
            'button_text_am' => $request->button_text_am ?: $request->cta_am,
            'button_text_en' => $request->button_text_en ?: $request->cta_en,
            'button_text_om' => $request->button_text_om ?: $request->cta_om,
            'button_url' => $request->button_url ?: ($request->cta_link ?: '/explore'),
            'image_path' => $request->image_path,
            'tag_am' => $request->tag_am,
            'tag_en' => $request->tag_en,
            'tag_om' => $request->tag_om,
            'order' => $request->order ?: 0,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Hero slide updated successfully']);
    }

    public function deleteHeroSlide($id)
    {
        DB::table('hero_slides')->where('id', $id)->delete();
        return response()->json(['message' => 'Hero slide deleted']);
    }

    // Destinations & Tourism Spotlight Management
    public function getDestinations()
    {
        $items = DB::table('destinations')->orderBy('order', 'asc')->orderBy('id', 'desc')->get();
        return response()->json($items);
    }

    public function storeDestination(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'location_am' => 'nullable|string',
            'location_en' => 'nullable|string',
            'location_om' => 'nullable|string',
            'summary_am' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'summary_om' => 'nullable|string',
            'description_am' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_om' => 'nullable|string',
            'visiting_hours' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|string',
            'admission_fee' => 'nullable|string',
            'features' => 'nullable|string',
            'image_path' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $id = DB::table('destinations')->insertGetId([
            'category' => $validated['category'],
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'location_am' => $validated['location_am'] ?? null,
            'location_en' => $validated['location_en'] ?? null,
            'location_om' => $validated['location_om'] ?? null,
            'summary_am' => $validated['summary_am'] ?? null,
            'summary_en' => $validated['summary_en'] ?? null,
            'summary_om' => $validated['summary_om'] ?? null,
            'description_am' => $validated['description_am'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'description_om' => $validated['description_om'] ?? null,
            'visiting_hours' => $validated['visiting_hours'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'contact_email' => $validated['contact_email'] ?? null,
            'admission_fee' => $validated['admission_fee'] ?? null,
            'features' => $validated['features'] ?? null,
            'image_path' => $validated['image_path'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Destination created successfully', 'id' => $id], 201);
    }

    public function updateDestination(Request $request, $id)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'title_am' => 'required|string',
            'title_en' => 'nullable|string',
            'title_om' => 'nullable|string',
            'location_am' => 'nullable|string',
            'location_en' => 'nullable|string',
            'location_om' => 'nullable|string',
            'summary_am' => 'nullable|string',
            'summary_en' => 'nullable|string',
            'summary_om' => 'nullable|string',
            'description_am' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_om' => 'nullable|string',
            'visiting_hours' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|string',
            'admission_fee' => 'nullable|string',
            'features' => 'nullable|string',
            'image_path' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        DB::table('destinations')->where('id', $id)->update([
            'category' => $validated['category'],
            'title_am' => $validated['title_am'],
            'title_en' => $validated['title_en'] ?? null,
            'title_om' => $validated['title_om'] ?? null,
            'location_am' => $validated['location_am'] ?? null,
            'location_en' => $validated['location_en'] ?? null,
            'location_om' => $validated['location_om'] ?? null,
            'summary_am' => $validated['summary_am'] ?? null,
            'summary_en' => $validated['summary_en'] ?? null,
            'summary_om' => $validated['summary_om'] ?? null,
            'description_am' => $validated['description_am'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'description_om' => $validated['description_om'] ?? null,
            'visiting_hours' => $validated['visiting_hours'] ?? null,
            'contact_phone' => $validated['contact_phone'] ?? null,
            'contact_email' => $validated['contact_email'] ?? null,
            'admission_fee' => $validated['admission_fee'] ?? null,
            'features' => $validated['features'] ?? null,
            'image_path' => $validated['image_path'] ?? null,
            'order' => $validated['order'] ?? 0,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Destination updated successfully']);
    }

    public function deleteDestination($id)
    {
        DB::table('destinations')->where('id', $id)->delete();
        return response()->json(['message' => 'Destination deleted']);
    }

    // ==========================================
    // EMERGENCY CONTACTS & 24/7 HOTLINES
    // ==========================================
    public function getEmergencyContacts()
    {
        $contacts = DB::table('emergency_contacts')->orderBy('order')->get();
        return response()->json($contacts);
    }

    public function storeEmergencyContact(Request $request)
    {
        $validated = $request->validate([
            'name_am' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_om' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:100',
            'icon_name' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $id = DB::table('emergency_contacts')->insertGetId([
            'name_am' => $validated['name_am'],
            'name_en' => $validated['name_en'],
            'name_om' => $validated['name_om'] ?? $validated['name_en'],
            'phone_number' => $validated['phone_number'],
            'icon_name' => $validated['icon_name'] ?? 'PhoneCall',
            'badge_color' => $validated['badge_color'] ?? 'red',
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active') ? ($request->boolean('is_active') ? 1 : 0) : 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Emergency contact created', 'id' => $id]);
    }

    public function updateEmergencyContact(Request $request, $id)
    {
        $validated = $request->validate([
            'name_am' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'name_om' => 'nullable|string|max:255',
            'phone_number' => 'required|string|max:100',
            'icon_name' => 'nullable|string|max:50',
            'badge_color' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        DB::table('emergency_contacts')->where('id', $id)->update([
            'name_am' => $validated['name_am'],
            'name_en' => $validated['name_en'],
            'name_om' => $validated['name_om'] ?? $validated['name_en'],
            'phone_number' => $validated['phone_number'],
            'icon_name' => $validated['icon_name'] ?? 'PhoneCall',
            'badge_color' => $validated['badge_color'] ?? 'red',
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->has('is_active') ? ($request->boolean('is_active') ? 1 : 0) : 1,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Emergency contact updated successfully']);
    }

    public function deleteEmergencyContact($id)
    {
        DB::table('emergency_contacts')->where('id', $id)->delete();
        return response()->json(['message' => 'Emergency contact deleted successfully']);
    }

    // ==========================================
    // CIVIC OPINION POLLS MANAGEMENT
    // ==========================================
    public function getPolls()
    {
        $polls = DB::table('polls')->orderBy('created_at', 'desc')->get();

        foreach ($polls as $p) {
            $options = DB::table('poll_options')->where('poll_id', $p->id)->orderBy('order')->get();
            $p->options = $options;
            $p->total_votes = $options->sum('votes_count');
            $p->is_expired = !empty($p->expires_at) && Carbon::parse($p->expires_at)->endOfDay()->isPast();
        }

        return response()->json($polls);
    }

    public function storePoll(Request $request)
    {
        $validated = $request->validate([
            'question_am' => 'required|string|max:500',
            'question_en' => 'required|string|max:500',
            'question_om' => 'nullable|string|max:500',
            'description_am' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_om' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'options' => 'required|array|min:2',
            'options.*.option_text_am' => 'required|string|max:300',
            'options.*.option_text_en' => 'required|string|max:300',
            'options.*.option_text_om' => 'nullable|string|max:300',
        ]);

        $isActive = $request->has('is_active') ? $request->boolean('is_active') : true;

        if ($isActive) {
            DB::table('polls')->update(['is_active' => false]);
        }

        $pollId = DB::table('polls')->insertGetId([
            'question_am' => $validated['question_am'],
            'question_en' => $validated['question_en'],
            'question_om' => $validated['question_om'] ?? $validated['question_en'],
            'description_am' => $validated['description_am'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'description_om' => $validated['description_om'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => $isActive ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $order = 1;
        foreach ($validated['options'] as $opt) {
            DB::table('poll_options')->insert([
                'poll_id' => $pollId,
                'option_text_am' => $opt['option_text_am'],
                'option_text_en' => $opt['option_text_en'],
                'option_text_om' => $opt['option_text_om'] ?? $opt['option_text_en'],
                'votes_count' => 0,
                'order' => $order++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Civic poll created successfully', 'id' => $pollId]);
    }

    public function updatePoll(Request $request, $id)
    {
        $validated = $request->validate([
            'question_am' => 'required|string|max:500',
            'question_en' => 'required|string|max:500',
            'question_om' => 'nullable|string|max:500',
            'description_am' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_om' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'is_active' => 'nullable|boolean',
            'options' => 'required|array|min:2',
            'options.*.option_text_am' => 'required|string|max:300',
            'options.*.option_text_en' => 'required|string|max:300',
            'options.*.option_text_om' => 'nullable|string|max:300',
            'options.*.id' => 'nullable|integer',
        ]);

        $isActive = $request->has('is_active') ? $request->boolean('is_active') : false;

        if ($isActive) {
            DB::table('polls')->where('id', '!=', $id)->update(['is_active' => false]);
        }

        DB::table('polls')->where('id', $id)->update([
            'question_am' => $validated['question_am'],
            'question_en' => $validated['question_en'],
            'question_om' => $validated['question_om'] ?? $validated['question_en'],
            'description_am' => $validated['description_am'] ?? null,
            'description_en' => $validated['description_en'] ?? null,
            'description_om' => $validated['description_om'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'is_active' => $isActive ? 1 : 0,
            'updated_at' => now(),
        ]);

        $keptOptionIds = [];
        $order = 1;
        foreach ($validated['options'] as $opt) {
            if (!empty($opt['id'])) {
                DB::table('poll_options')->where('id', $opt['id'])->where('poll_id', $id)->update([
                    'option_text_am' => $opt['option_text_am'],
                    'option_text_en' => $opt['option_text_en'],
                    'option_text_om' => $opt['option_text_om'] ?? $opt['option_text_en'],
                    'order' => $order++,
                    'updated_at' => now(),
                ]);
                $keptOptionIds[] = $opt['id'];
            } else {
                $newId = DB::table('poll_options')->insertGetId([
                    'poll_id' => $id,
                    'option_text_am' => $opt['option_text_am'],
                    'option_text_en' => $opt['option_text_en'],
                    'option_text_om' => $opt['option_text_om'] ?? $opt['option_text_en'],
                    'votes_count' => 0,
                    'order' => $order++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $keptOptionIds[] = $newId;
            }
        }

        // Delete options no longer present
        if (!empty($keptOptionIds)) {
            DB::table('poll_options')->where('poll_id', $id)->whereNotIn('id', $keptOptionIds)->delete();
        }

        return response()->json(['message' => 'Civic poll updated successfully']);
    }

    public function togglePollActive($id)
    {
        $poll = DB::table('polls')->where('id', $id)->first();
        if (!$poll) {
            return response()->json(['message' => 'Poll not found'], 404);
        }

        $newActive = !$poll->is_active;

        if ($newActive) {
            DB::table('polls')->update(['is_active' => false]);
            DB::table('polls')->where('id', $id)->update(['is_active' => true, 'updated_at' => now()]);
        } else {
            DB::table('polls')->where('id', $id)->update(['is_active' => false, 'updated_at' => now()]);
        }

        return response()->json(['message' => 'Poll status updated', 'is_active' => $newActive]);
    }

    public function resetPollVotes($id)
    {
        DB::table('poll_votes')->where('poll_id', $id)->delete();
        DB::table('poll_options')->where('poll_id', $id)->update(['votes_count' => 0]);
        return response()->json(['message' => 'Poll votes and ballots reset successfully. Now accepting real votes.']);
    }

    public function deletePoll($id)
    {
        DB::table('polls')->where('id', $id)->delete();
        return response()->json(['message' => 'Poll deleted successfully']);
    }
}
