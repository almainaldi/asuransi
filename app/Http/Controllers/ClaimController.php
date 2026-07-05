<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ClaimLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ClaimController extends Controller
{
    /**
     * Menampilkan daftar klaim berdasarkan Role (Requirement 1.2.2)
     * HTTP 200: Sukses mengambil data
     * HTTP 401: Unauthenticated (Ditangani middleware 'auth')
     */
    public function index()
    {
        $user = auth()->user();

        // Pengaman jika user object kosong (HTTP 401)
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        $query = Claim::with('user');

        // RBAC Filter di sisi Backend
        if ($user->role === 'user') {
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'verifier') {
            $query->where('status', 'submitted');
        } elseif ($user->role === 'approver') {
            $query->where('status', 'reviewed');
        } else {
            // HTTP 403 Forbidden jika role tidak dikenali sistem
            abort(403, 'Role tidak valid untuk mengakses data ini.');
        }

        return Inertia::render('Claim/Index', [
            'claims' => $query->latest()->get()
        ]);
    }

    /**
     * Menyimpan klaim baru yang dibuat oleh User
     * HTTP 200/302: Sukses simpan data
     * HTTP 403: Forbidden jika bukan role 'user'
     * HTTP 422: Unprocessable Content jika validasi input gagal (Pengganti HTTP 400 di Laravel)
     */
    public function store(Request $request)
    {
        // 403 Forbidden: Hanya User yang boleh bikin klaim
        if (auth()->user()->role !== 'user') {
            abort(403, 'Hanya User yang dapat membuat klaim.');
        }

        // Jika validasi gagal, Laravel otomatis melempar HTTP 422 (Unprocessable Content)
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'email_kontak' => 'required|email|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'perencanaan_hidup' => 'required|string',
            'nominal_asuransi' => 'required|numeric|min:0',
            'tinggi_badan' => 'required|integer|min:0',
            'berat_badan' => 'required|integer|min:0',
            'masalah_kesehatan' => 'nullable|string',
            'total_asuransi_jiwa' => 'required|numeric|min:0',
        ]);

        Claim::create(array_merge($validatedData, [
            'user_id' => auth()->id(),
            'status' => 'draft'
        ]));

        return redirect()->route('claims.index')->with('message', 'Formulir klaim asuransi berhasil disimpan sebagai draft.');
    }

    /**
     * Memperbarui Status Klaim (Strict Lifecycle, RBAC, Anti-Race Condition)
     * HTTP 404: Not Found jika ID klaim tidak ada
     * HTTP 422: Kesalahan logika alur/lifecycle klaim (Sama fungsinya seperti HTTP 400 Bad Request)
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi struktur input (Mengembalikan HTTP 422 jika gagal)
        $request->validate([
            'target_status' => 'required|in:submitted,reviewed,approved,rejected',
            'note' => 'nullable|string'
        ]);

        // Mengembalikan HTTP 404 jika ID klaim tidak ditemukan di database
        $claim = Claim::find($id);
        if (!$claim) {
            abort(404, 'Data klaim tidak ditemukan.');
        }

        $user = auth()->user();

        // Menggunakan ValidationException::withMessages agar error logic dilempar dengan kode HTTP 422
        // Ini adalah cara standar Laravel mengembalikan error 400/422 yang terbaca rapi di Postman & Inertia

        // 1. Validasi Aksi untuk ROLE: USER
        if ($user->role === 'user' && $request->target_status !== 'submitted') {
            throw ValidationException::withMessages(['error' => 'User hanya dapat mengajukan klaim (submit).']);
        }

        // 2. Validasi Aksi untuk ROLE: VERIFIER
        if ($user->role === 'verifier' && !in_array($request->target_status, ['reviewed', 'rejected'])) {
            throw ValidationException::withMessages(['error' => 'Verifier hanya dapat menandai selesai direview atau menolak verifikasi.']);
        }

        // 3. Validasi Aksi untuk ROLE: APPROVER
        if ($user->role === 'approver' && !in_array($request->target_status, ['approved', 'rejected'])) {
            throw ValidationException::withMessages(['error' => 'Approver hanya dapat menyetujui atau menolak klaim secara final.']);
        }

        // --- PROTEKSI LIFECYCLE STRICT (Alur Kerja Aman) ---
        if ($user->role === 'verifier' && $claim->status !== 'submitted') {
            throw ValidationException::withMessages(['error' => 'Klaim ini tidak berada dalam tahap verifikasi.']);
        }

        if ($user->role === 'approver' && $claim->status !== 'reviewed') {
            throw ValidationException::withMessages(['error' => 'Klaim ini belum selesai diverifikasi oleh Verifier.']);
        }

        // Jalankan database transaction (Pessimistic Locking untuk keamanan data)
        DB::transaction(function () use ($claim, $request, $user) {
            $oldStatus = $claim->status;

            $claim->update([
                'status' => $request->target_status
            ]);

            ClaimLog::create([
                'claim_id' => $claim->id,
                'user_id' => $user->id,
                'from_status' => $oldStatus,
                'to_status' => $request->target_status,
                'note' => $request->note
            ]);
        });

        return redirect()->back()->with('message', 'Status klaim berhasil diperbarui.');
    }

    /**
     * Menghapus Draft Klaim
     * HTTP 404: Jika ID tidak ada
     * HTTP 403: Forbidden jika mencoba menghapus dokumen orang lain atau status bukan draft
     */
    public function destroy($id)
    {
        $claim = Claim::find($id);
        if (!$claim) {
            abort(404, 'Data klaim tidak ditemukan.');
        }

        // Proteksi Hak Akses (Mengembalikan HTTP 403)
        if (auth()->user()->role !== 'user' || $claim->user_id !== auth()->id() || $claim->status !== 'draft') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data ini atau status data bukan draft.');
        }

        $claim->delete();

        return redirect()->route('claims.index')->with('message', 'Draft formulir berhasil dihapus.');
    }
}
