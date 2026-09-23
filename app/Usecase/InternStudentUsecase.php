<?php

namespace App\Usecase;

use App\Constants\DatabaseConst;
use App\Constants\ResponseConst;
use App\Constants\UserConst;
use App\Http\Presenter\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InternStudentUsecase extends Usecase
{
    public function __construct() {}

    /**
     * Get all intern students with filters and pagination.
     * Scoped for Pembimbing (only shows their supervised students).
     */
    public function getAll(array $filterData = []): array
    {
        try {
            $user = Auth::user();
            $isPembimbing = $user?->access_type == UserConst::PEMBIMBING;

            $query = DB::table(DatabaseConst::INTERN().' as i')
                ->leftJoin(DatabaseConst::USER().' as u', 'i.user_id', '=', 'u.id')
                ->leftJoin(DatabaseConst::INSTITUTION().' as inst', 'i.institution_id', '=', 'inst.id')
                ->leftJoin(DatabaseConst::MENTOR().' as m', 'i.mentor_id', '=', 'm.id')
                ->select(
                    'i.*',
                    'u.name as student_name',
                    'u.email as student_email',
                    'inst.name as institution_name',
                    'm.name as mentor_name'
                )
                ->whereNull('i.deleted_at');

            // Scoping for Pembimbing
            if ($isPembimbing) {
                $mentors = DB::table(DatabaseConst::MENTOR())
                    ->where('user_id', $user->id)
                    ->whereNull('deleted_at')
                    ->get();

                $mentorIds = $mentors->pluck('id')->toArray();
                $institutionIds = $mentors->pluck('institution_id')->filter()->toArray();

                if (! empty($mentorIds) || ! empty($institutionIds)) {
                    $query->where(function ($q) use ($mentorIds, $institutionIds) {
                        if (! empty($mentorIds)) {
                            $q->whereIn('i.mentor_id', $mentorIds);
                        }
                        if (! empty($institutionIds)) {
                            $q->orWhere(function ($sub) use ($institutionIds, $mentorIds) {
                                $sub->whereIn('i.institution_id', $institutionIds);
                                if (! empty($mentorIds)) {
                                    $sub->where(function ($s) use ($mentorIds) {
                                        $s->whereIn('i.mentor_id', $mentorIds)
                                            ->orWhereNull('i.mentor_id');
                                    });
                                }
                            });
                        }
                    });
                } else {
                    $query->whereRaw('1 = 0'); // No mentor assigned
                }
            } else {
                if (! empty($filterData['institution_id'])) {
                    $query->where('i.institution_id', $filterData['institution_id']);
                }
                if (! empty($filterData['mentor_id'])) {
                    $query->where('i.mentor_id', $filterData['mentor_id']);
                }
            }

            if (! empty($filterData['status']) && $filterData['status'] !== 'all') {
                $query->where('i.status', $filterData['status']);
            }

            if (! empty($filterData['keywords'])) {
                $keywords = $filterData['keywords'];
                $query->where(function ($q) use ($keywords) {
                    $q->where('u.name', 'like', '%'.$keywords.'%')
                        ->orWhere('u.email', 'like', '%'.$keywords.'%')
                        ->orWhere('i.nis_nim', 'like', '%'.$keywords.'%')
                        ->orWhere('i.major', 'like', '%'.$keywords.'%')
                        ->orWhere('inst.name', 'like', '%'.$keywords.'%')
                        ->orWhere('m.name', 'like', '%'.$keywords.'%');
                });
            }

            $query->orderBy('i.created_at', 'desc');

            $data = empty($filterData['no_pagination'])
                ? $query->paginate(20)
                : $query->get();

            if (! empty($filterData) && method_exists($data, 'appends')) {
                $data->appends($filterData);
            }

            return Response::buildSuccess(
                [
                    'list' => $data,
                ],
                ResponseConst::HTTP_SUCCESS
            );
        } catch (Exception $e) {
            Log::error(
                message: $e->getMessage(),
                context: ['method' => __METHOD__]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Get Intern details by ID.
     */
    public function getByID(int $id): array
    {
        try {
            $user = Auth::user();
            $isPembimbing = $user?->access_type == UserConst::PEMBIMBING;

            $query = DB::table(DatabaseConst::INTERN().' as i')
                ->leftJoin(DatabaseConst::USER().' as u', 'i.user_id', '=', 'u.id')
                ->leftJoin(DatabaseConst::INSTITUTION().' as inst', 'i.institution_id', '=', 'inst.id')
                ->leftJoin(DatabaseConst::MENTOR().' as m', 'i.mentor_id', '=', 'm.id')
                ->select(
                    'i.*',
                    'u.name as student_name',
                    'u.email as student_email',
                    'inst.name as institution_name',
                    'm.name as mentor_name',
                    'm.phone as mentor_phone',
                    'm.identity_number as mentor_identity_number'
                )
                ->where('i.id', $id)
                ->whereNull('i.deleted_at');

            if ($isPembimbing) {
                $mentors = DB::table(DatabaseConst::MENTOR())
                    ->where('user_id', $user->id)
                    ->whereNull('deleted_at')
                    ->get();

                $mentorIds = $mentors->pluck('id')->toArray();
                $institutionIds = $mentors->pluck('institution_id')->filter()->toArray();

                if (empty($mentorIds) && empty($institutionIds)) {
                    return Response::buildErrorNotFound();
                }

                $query->where(function ($q) use ($mentorIds, $institutionIds) {
                    if (! empty($mentorIds)) {
                        $q->whereIn('i.mentor_id', $mentorIds);
                    }
                    if (! empty($institutionIds)) {
                        $q->orWhere(function ($sub) use ($institutionIds, $mentorIds) {
                            $sub->whereIn('i.institution_id', $institutionIds);
                            if (! empty($mentorIds)) {
                                $sub->where(function ($s) use ($mentorIds) {
                                    $s->whereIn('i.mentor_id', $mentorIds)
                                        ->orWhereNull('i.mentor_id');
                                });
                            }
                        });
                    }
                });
            }

            $data = $query->first();

            if (! $data) {
                return Response::buildErrorNotFound();
            }

            // Recent 5 log books
            $recentLogs = DB::table(DatabaseConst::DAILY_LOG())
                ->where('user_id', $data->user_id)
                ->whereNull('deleted_at')
                ->orderBy('log_date', 'desc')
                ->limit(5)
                ->get();

            return Response::buildSuccess([
                'intern' => collect($data)->toArray(),
                'recent_logs' => $recentLogs,
            ]);
        } catch (Exception $e) {
            Log::error(
                message: $e->getMessage(),
                context: ['method' => __METHOD__]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Get options for form creation/edit (Institutions, Mentors, Student Users).
     */
    public function getFormData(?int $selectedInstitutionId = null): array
    {
        $institutions = DB::table(DatabaseConst::INSTITUTION())
            ->whereNull('deleted_at')
            ->orderBy('name', 'asc')
            ->select('id', 'name')
            ->get();

        $mentorsQuery = DB::table(DatabaseConst::MENTOR())
            ->whereNull('deleted_at')
            ->orderBy('name', 'asc')
            ->select('id', 'name', 'institution_id');

        if ($selectedInstitutionId) {
            $mentorsQuery->where('institution_id', $selectedInstitutionId);
        }
        $mentors = $mentorsQuery->get();

        // Users with role ANGGOTA
        $students = DB::table(DatabaseConst::USER())
            ->where('access_type', UserConst::ANGGOTA)
            ->whereNull('deleted_at')
            ->orderBy('name', 'asc')
            ->select('id', 'name', 'email')
            ->get();

        return [
            'institutions' => $institutions,
            'mentors' => $mentors,
            'students' => $students,
        ];
    }

    /**
     * Create new intern student.
     */
    public function create(Request $data): array
    {
        $validator = Validator::make($data->all(), [
            'student_source' => 'required|in:existing,new',
            'user_id' => 'required_if:student_source,existing|nullable|exists:users,id',
            'new_student_name' => 'required_if:student_source,new|nullable|string|max:255',
            'new_student_email' => 'required_if:student_source,new|nullable|email|unique:users,email|max:255',
            'institution_id' => 'required|exists:institutions,id',
            'mentor_id' => 'required|exists:mentors,id',
            'nis_nim' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:150',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:aktif,selesai,keluar',
            'notes' => 'nullable|string',
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            $userId = $data['user_id'] ?? null;

            if ($data['student_source'] === 'new') {
                $userId = DB::table(DatabaseConst::USER())->insertGetId([
                    'name' => $data['new_student_name'],
                    'email' => $data['new_student_email'],
                    'access_type' => UserConst::ANGGOTA,
                    'password' => UserConst::DEFAULT_PASSWORD,
                    'is_active' => 1,
                    'created_by' => Auth::user()?->id,
                    'created_at' => now(),
                ]);
            }

            // Check if user already registered as intern
            $existing = DB::table(DatabaseConst::INTERN())
                ->where('user_id', $userId)
                ->whereNull('deleted_at')
                ->first();

            if ($existing) {
                DB::rollback();
                return Response::buildErrorService('Siswa ini sudah terdaftar sebagai peserta magang.');
            }

            $internId = DB::table(DatabaseConst::INTERN())->insertGetId([
                'user_id' => $userId,
                'institution_id' => $data['institution_id'],
                'mentor_id' => $data['mentor_id'],
                'nis_nim' => $data['nis_nim'] ?? null,
                'major' => $data['major'] ?? null,
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'status' => $data['status'] ?? 'aktif',
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::user()?->id,
                'created_at' => now(),
            ]);

            DB::commit();

            return Response::buildSuccessCreated(['id' => $internId]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error(
                message: $e->getMessage(),
                context: ['method' => __METHOD__]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Update intern student data.
     */
    public function update(Request $data, int $id): array
    {
        $validator = Validator::make($data->all(), [
            'institution_id' => 'required|exists:institutions,id',
            'mentor_id' => 'required|exists:mentors,id',
            'nis_nim' => 'nullable|string|max:100',
            'major' => 'nullable|string|max:150',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:aktif,selesai,keluar',
            'notes' => 'nullable|string',
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            DB::table(DatabaseConst::INTERN())
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                    'institution_id' => $data['institution_id'],
                    'mentor_id' => $data['mentor_id'],
                    'nis_nim' => $data['nis_nim'] ?? null,
                    'major' => $data['major'] ?? null,
                    'start_date' => $data['start_date'] ?? null,
                    'end_date' => $data['end_date'] ?? null,
                    'status' => $data['status'],
                    'notes' => $data['notes'] ?? null,
                    'updated_by' => Auth::user()?->id,
                    'updated_at' => now(),
                ]);

            DB::commit();

            return Response::buildSuccess(
                message: ResponseConst::SUCCESS_MESSAGE_UPDATED
            );
        } catch (Exception $e) {
            DB::rollback();
            Log::error(
                message: $e->getMessage(),
                context: ['method' => __METHOD__]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Soft delete intern student.
     */
    public function delete(int $id): array
    {
        DB::beginTransaction();
        try {
            $delete = DB::table(DatabaseConst::INTERN())
                ->where('id', $id)
                ->update([
                    'deleted_by' => Auth::user()?->id,
                    'deleted_at' => now(),
                ]);

            if (! $delete) {
                DB::rollback();
                throw new Exception('Gagal menghapus data peserta magang');
            }

            DB::commit();

            return Response::buildSuccess(
                message: ResponseConst::SUCCESS_MESSAGE_DELETED
            );
        } catch (Exception $e) {
            DB::rollback();
            Log::error(
                message: $e->getMessage(),
                context: ['method' => __METHOD__]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }
}
