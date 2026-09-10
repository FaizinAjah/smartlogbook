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

class InstitutionUsecase extends Usecase
{
    public function __construct() {}

    /**
     * Get all institutions with pagination and filter.
     */
    public function getAll(array $filterData = []): array
    {
        try {
            $query = DB::table(DatabaseConst::INSTITUTION().' as inst')
                ->select(
                    'inst.*',
                    DB::raw('(SELECT COUNT(*) FROM '.DatabaseConst::MENTOR().' as m WHERE m.institution_id = inst.id AND m.deleted_at IS NULL) as mentors_count'),
                    DB::raw('(SELECT COUNT(*) FROM '.DatabaseConst::INTERN().' as i WHERE i.institution_id = inst.id AND i.deleted_at IS NULL) as interns_count')
                )
                ->whereNull('inst.deleted_at')
                ->when($filterData['keywords'] ?? false, function ($query, $keywords) {
                    return $query->where(function ($q) use ($keywords) {
                        $q->where('inst.name', 'like', '%'.$keywords.'%')
                            ->orWhere('inst.email', 'like', '%'.$keywords.'%')
                            ->orWhere('inst.phone', 'like', '%'.$keywords.'%')
                            ->orWhere('inst.address', 'like', '%'.$keywords.'%');
                    });
                })
                ->when($filterData['type'] ?? false, function ($query, $type) {
                    if ($type !== 'all') {
                        return $query->where('inst.type', $type);
                    }
                })
                ->orderBy('inst.name', 'asc');

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
                context: [
                    'method' => __METHOD__,
                ]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Get Institution by ID along with its mentors/supervisors.
     */
    public function getByID(int $id): array
    {
        try {
            $institution = DB::table(DatabaseConst::INSTITUTION())
                ->whereNull('deleted_at')
                ->where('id', $id)
                ->first();

            if (! $institution) {
                return Response::buildErrorNotFound();
            }

            $mentors = DB::table(DatabaseConst::MENTOR().' as m')
                ->leftJoin(DatabaseConst::USER().' as u', 'm.user_id', '=', 'u.id')
                ->select(
                    'm.*',
                    'u.email as user_email',
                    DB::raw('(SELECT COUNT(*) FROM '.DatabaseConst::INTERN().' as i WHERE i.mentor_id = m.id AND i.deleted_at IS NULL) as interns_count')
                )
                ->where('m.institution_id', $id)
                ->whereNull('m.deleted_at')
                ->orderBy('m.name', 'asc')
                ->get();

            return Response::buildSuccess(
                data: [
                    'institution' => collect($institution)->toArray(),
                    'mentors' => $mentors,
                ]
            );
        } catch (Exception $e) {
            Log::error(
                message: $e->getMessage(),
                context: [
                    'method' => __METHOD__,
                ]
            );

            return Response::buildErrorService($e->getMessage());
        }
    }

    /**
     * Create new institution.
     */
    public function create(Request $data): array
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            $id = DB::table(DatabaseConst::INSTITUTION())->insertGetId([
                'name' => $data['name'],
                'type' => $data['type'] ?? 'SMK',
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'website' => $data['website'] ?? null,
                'address' => $data['address'] ?? null,
                'is_active' => 1,
                'created_by' => Auth::user()?->id,
                'created_at' => now(),
            ]);

            DB::commit();

            return Response::buildSuccessCreated(['id' => $id]);
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
     * Update existing institution.
     */
    public function update(Request $data, int $id): array
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            DB::table(DatabaseConst::INSTITUTION())
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->update([
                    'name' => $data['name'],
                    'type' => $data['type'] ?? 'SMK',
                    'email' => $data['email'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'website' => $data['website'] ?? null,
                    'address' => $data['address'] ?? null,
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
     * Soft delete institution.
     */
    public function delete(int $id): array
    {
        DB::beginTransaction();
        try {
            $delete = DB::table(DatabaseConst::INSTITUTION())
                ->where('id', $id)
                ->update([
                    'deleted_by' => Auth::user()?->id,
                    'deleted_at' => now(),
                ]);

            if (! $delete) {
                DB::rollback();
                throw new Exception('FAILED DELETE DATA');
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

    /**
     * Create mentor/supervisor under an institution.
     * Automatically creates a user account with role PEMBIMBING if one does not exist.
     */
    public function createMentor(int $institutionId, Request $data): array
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'identity_number' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:100',
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            // Check if user exists
            $user = DB::table(DatabaseConst::USER())
                ->where('email', $data['email'])
                ->whereNull('deleted_at')
                ->first();

            $userId = null;
            if ($user) {
                $userId = $user->id;
                if ($user->access_type != UserConst::SUPERADMIN && $user->access_type != UserConst::PEMBIMBING) {
                    DB::table(DatabaseConst::USER())
                        ->where('id', $userId)
                        ->update([
                            'access_type' => UserConst::PEMBIMBING,
                            'updated_by' => Auth::user()?->id,
                            'updated_at' => now(),
                        ]);
                }
            } else {
                $userId = DB::table(DatabaseConst::USER())->insertGetId([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'access_type' => UserConst::PEMBIMBING,
                    'password' => UserConst::DEFAULT_PASSWORD,
                    'is_active' => 1,
                    'created_by' => Auth::user()?->id,
                    'created_at' => now(),
                ]);
            }

            $mentorId = DB::table(DatabaseConst::MENTOR())->insertGetId([
                'institution_id' => $institutionId,
                'user_id' => $userId,
                'name' => $data['name'],
                'identity_number' => $data['identity_number'] ?? null,
                'phone' => $data['phone'] ?? null,
                'position' => $data['position'] ?? 'Pembimbing',
                'is_active' => 1,
                'created_by' => Auth::user()?->id,
                'created_at' => now(),
            ]);

            DB::commit();

            return Response::buildSuccessCreated(['id' => $mentorId]);
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
     * Update mentor/supervisor.
     */
    public function updateMentor(int $mentorId, Request $data): array
    {
        $validator = Validator::make($data->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'identity_number' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:100',
        ]);

        $validator->validate();

        DB::beginTransaction();
        try {
            $mentor = DB::table(DatabaseConst::MENTOR())
                ->where('id', $mentorId)
                ->whereNull('deleted_at')
                ->first();

            if (! $mentor) {
                DB::rollback();
                throw new Exception('Pembimbing tidak ditemukan');
            }

            DB::table(DatabaseConst::MENTOR())
                ->where('id', $mentorId)
                ->update([
                    'name' => $data['name'],
                    'identity_number' => $data['identity_number'] ?? null,
                    'phone' => $data['phone'] ?? null,
                    'position' => $data['position'] ?? null,
                    'updated_by' => Auth::user()?->id,
                    'updated_at' => now(),
                ]);

            if ($mentor->user_id) {
                DB::table(DatabaseConst::USER())
                    ->where('id', $mentor->user_id)
                    ->update([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'updated_by' => Auth::user()?->id,
                        'updated_at' => now(),
                    ]);
            }

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
     * Soft delete mentor.
     */
    public function deleteMentor(int $mentorId): array
    {
        DB::beginTransaction();
        try {
            $delete = DB::table(DatabaseConst::MENTOR())
                ->where('id', $mentorId)
                ->update([
                    'deleted_by' => Auth::user()?->id,
                    'deleted_at' => now(),
                ]);

            if (! $delete) {
                DB::rollback();
                throw new Exception('Gagal menghapus data pembimbing');
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
