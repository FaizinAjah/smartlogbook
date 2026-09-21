<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ResponseConst;
use App\Http\Controllers\Controller;
use App\Usecase\InstitutionUsecase;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InstitutionController extends Controller
{
    protected array $page = [
        'route' => 'institutions',
        'title' => 'Institusi',
    ];

    protected string $baseRedirect;

    public function __construct(
        protected InstitutionUsecase $usecase
    ) {
        $this->baseRedirect = 'admin/'.$this->page['route'];
    }

    public function index(Request $request): View|Response
    {
        $data = $this->usecase->getAll([
            'keywords' => $request->get('keywords'),
            'type' => $request->get('type'),
        ]);
        $data = $data['data']['list'] ?? [];

        return view('_admin.institutions.index', [
            'data' => $data,
            'page' => $this->page,
            'keywords' => $request->get('keywords'),
            'type' => $request->get('type'),
        ]);
    }

    public function add(): View|Response
    {
        return view('_admin.institutions.add', [
            'page' => $this->page,
        ]);
    }

    public function doCreate(Request $request): RedirectResponse
    {
        $process = $this->usecase->create(
            data: $request,
        );

        if ($process['success']) {
            $newInstitutionId = $process['data']['id'] ?? null;
            if ($newInstitutionId) {
                return redirect()
                    ->route('admin.institutions.mentors.add', $newInstitutionId)
                    ->with('success', 'Data institusi berhasil ditambahkan. Silakan tambahkan pembimbing untuk institusi ini.');
            }

            return redirect()
                ->route('admin.institutions.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_CREATED);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function addMentor(int $id): View|RedirectResponse|Response
    {
        $response = $this->usecase->getByID($id);

        if (empty($response['data'])) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $institution = (object) ($response['data']['institution'] ?? []);

        return view('_admin.institutions.add_mentor', [
            'institution' => $institution,
            'page' => $this->page,
        ]);
    }

    public function detail(int $id): View|RedirectResponse|Response
    {
        $response = $this->usecase->getByID($id);

        if (empty($response['data'])) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $institution = (object) ($response['data']['institution'] ?? []);
        $mentors = $response['data']['mentors'] ?? [];

        return view('_admin.institutions.detail', [
            'institution' => $institution,
            'mentors' => $mentors,
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

        $institution = (object) ($response['data']['institution'] ?? []);

        return view('_admin.institutions.update', [
            'data' => $institution,
            'institutionId' => $id,
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
                ->route('admin.institutions.index')
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
                ->route('admin.institutions.index')
                ->with('success', ResponseConst::SUCCESS_MESSAGE_DELETED);
        } else {
            return redirect()
                ->route('admin.institutions.index')
                ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function createMentor(int $id, Request $request): RedirectResponse
    {
        $process = $this->usecase->createMentor(
            institutionId: $id,
            data: $request,
        );

        if ($process['success']) {
            return redirect()
                ->route('admin.institutions.detail', $id)
                ->with('success', 'Data pembimbing berhasil ditambahkan.');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function editMentor(int $mentorId): View|RedirectResponse|Response
    {
        $response = $this->usecase->getMentorByID($mentorId);

        if (empty($response['data'])) {
            return redirect()
                ->intended($this->baseRedirect)
                ->with('error', ResponseConst::DEFAULT_ERROR_MESSAGE);
        }

        $mentor = (object) ($response['data']['mentor'] ?? []);
        $institution = (object) ($response['data']['institution'] ?? []);

        return view('_admin.institutions.edit_mentor', [
            'mentor' => $mentor,
            'institution' => $institution,
            'page' => $this->page,
        ]);
    }

    public function updateMentor(int $mentorId, Request $request): RedirectResponse
    {
        $process = $this->usecase->updateMentor(
            mentorId: $mentorId,
            data: $request,
        );

        if ($process['success']) {
            $institutionId = $process['data']['institution_id'] ?? null;
            if ($institutionId) {
                return redirect()
                    ->route('admin.institutions.detail', $institutionId)
                    ->with('success', 'Data pembimbing berhasil diperbarui.');
            }

            return redirect()
                ->back()
                ->with('success', 'Data pembimbing berhasil diperbarui.');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
        }
    }

    public function deleteMentor(int $mentorId): RedirectResponse
    {
        $process = $this->usecase->deleteMentor(mentorId: $mentorId);

        if ($process['success']) {
            return redirect()
                ->back()
                ->with('success', 'Data pembimbing berhasil dihapus.');
        } else {
            return redirect()
                ->back()
                ->with('error', $process['message'] ?? ResponseConst::DEFAULT_ERROR_MESSAGE);
        }
    }
}
