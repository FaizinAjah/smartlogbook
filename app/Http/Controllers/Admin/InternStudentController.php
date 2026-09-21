<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseConst;
use App\Constants\UserConst;
use App\Http\Controllers\Controller;
use App\Usecase\InternStudentUsecase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InternStudentController extends Controller
{
    protected array $page = [
        'route' => 'interns',
        'title' => 'Peserta Magang',
    ];

    protected string $baseRedirect;

    public function __construct(
        protected InternStudentUsecase $usecase
    ) {
        $this->baseRedirect = 'admin/'.$this->page['route'];
    }

    public function index(Request $request): View|Response
    {
        $filters = [
            'keywords' => $request->get('keywords'),
            'institution_id' => $request->get('institution_id'),
            'mentor_id' => $request->get('mentor_id'),
            'status' => $request->get('status'),
        ];

        $data = $this->usecase->getAll($filters);
        $data = $data['data']['list'] ?? [];
        $formData = $this->usecase->getFormData();

        $isSuperadmin = Auth::user()?->access_type == UserConst::SUPERADMIN;

        return view('_admin.interns.index', [
            'data' => $data,
            'page' => $this->page,
            'institutions' => $formData['institutions'],
            'mentors' => $formData['mentors'],
            'keywords' => $request->get('keywords'),
            'institution_id' => $request->get('institution_id'),
            'mentor_id' => $request->get('mentor_id'),
            'status' => $request->get('status'),
            'isSuperadmin' => $isSuperadmin,
        ]);
    }

    public function add(): View|Response
    {
        $formData = $this->usecase->getFormData();

        return view('_admin.interns.add', [
            'page' => $this->page,
            'institutions' => $formData['institutions'],
            'mentors' => $formData['mentors'],
            'students' => $formData['students'],
        ]);
    }

    public function doCreate(Request $request): RedirectResponse
    {
        $process = $this->usecase->create(
            data: $request,
        );

        if ($process['success']) {
            return redirect()
                ->route('admin.interns.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_CREATED);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function detail(int $id): View|RedirectResponse|Response
    {
        $response = $this->usecase->getByID($id);

        if (empty($response['data'])) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $intern = (object) ($response['data']['intern'] ?? []);
        $recentLogs = $response['data']['recent_logs'] ?? [];

        return view('_admin.interns.detail', [
            'data' => $intern,
            'recentLogs' => $recentLogs,
            'page' => $this->page,
        ]);
    }

    public function update(int $id): View|RedirectResponse|Response
    {
        $response = $this->usecase->getByID($id);

        if (empty($response['data'])) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $intern = (object) ($response['data']['intern'] ?? []);
        $formData = $this->usecase->getFormData($intern->institution_id ?? null);

        return view('_admin.interns.update', [
            'data' => $intern,
            'internId' => $id,
            'institutions' => $formData['institutions'],
            'mentors' => $formData['mentors'],
            'page' => $this->page,
        ]);
    }

    public function doUpdate(int $id, Request $request): RedirectResponse
    {
        $process = $this->usecase->update(
            data: $request,
            id: $id,
        );

        if ($process['success']) {
            return redirect()
                ->route('admin.interns.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_UPDATED);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function delete(int $id): RedirectResponse
    {
        $process = $this->usecase->delete(id: $id);

        if ($process['success']) {
            return redirect()
                ->route('admin.interns.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_DELETED);
        } else {
            return redirect()
                ->route('admin.interns.index')
                ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function getMentorsByInstitution(int $institutionId): JsonResponse
    {
        $formData = $this->usecase->getFormData($institutionId);

        return response()->json($formData['mentors']);
    }
}
