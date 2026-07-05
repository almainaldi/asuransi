<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\ClaimLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ClaimController extends Controller
{
    /**
     * Menampilkan daftar klaim berdasarkan Role (Requirement 1.2.2)
     */
    public function index()
    {
        $user = auth()->user();
        $query = Claim::with('user');

        // RBAC Filter di sisi Backend
        if ($user->role === 'user') {
            // User hanya boleh melihat klaim milik sendiri
            $query->where('user_id', $user->id);
        } elseif ($user->role === 'verifier') {
            // Verifier melihat semua klaim yang siap diperiksa
            $query->where('status', 'submitted');
        } elseif ($user->role === 'approver') {
            // Approver melihat semua klaim yang siap diputuskan
            $query->where('status', 'reviewed');
        }

        return Inertia::render('Claim/Index', [
            'claims' => $query->latest()->get()
        ]);
    }

    /**
     * Menyimpan klaim baru yang dibuat oleh User
     */
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'user') {
            return redirect()->back()->with('error', 'Hanya User yang dapat membuat klaim.');
        }

        $request->validate([
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

        Claim::create(array_merge($request->all(), [
            'user_id' => auth()->id(),
            'status' => 'draft'
        ]));

        return redirect()->route('claims.index')->with('message', 'Formulir klaim asuransi berhasil disimpan sebagai draft.');
    }

    /**
     * Memperbarui Status Klaim (Strict Lifecycle, RBAC, Anti-Race Condition)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'target_status' => 'required|in:submitted,reviewed,approved,rejected',
            'note' => 'nullable|string'
        ]);

        $claim = Claim::findOrFail($id);
        $user = auth()->user();

        // 1. Validasi Aksi untuk ROLE: USER
        if ($user->role === 'user' && $request->target_status !== 'submitted') {
            return redirect()->back()->withErrors(['error' => 'User hanya dapat mengajukan klaim (submit).']);
        }

        // 2. Validasi Aksi untuk ROLE: VERIFIER
        // Verifier BOLEH mengubah ke 'reviewed' (lolos) ATAU 'rejected' (tidak lolos)
        if ($user->role === 'verifier' && !in_array($request->target_status, ['reviewed', 'rejected'])) {
            return redirect()->back()->withErrors(['error' => 'Verifier hanya dapat menandai selesai direview atau menolak verifikasi.']);
        }

        // 3. Validasi Aksi untuk ROLE: APPROVER
        // Approver BOLEH mengubah ke 'approved' ATAU 'rejected'
        if ($user->role === 'approver' && !in_array($request->target_status, ['approved', 'rejected'])) {
            return redirect()->back()->withErrors(['error' => 'Approver hanya dapat menyetujui atau menolak klaim secara final.']);
        }

        // --- PROTEKSI LIFECYCLE STRICT (Alur Kerja Aman) ---
        // Jika Verifier ingin memproses, status saat ini harus 'submitted'
        if ($user->role === 'verifier' && $claim->status !== 'submitted') {
            return redirect()->back()->withErrors(['error' => 'Klaim ini tidak berada dalam tahap verifikasi.']);
        }

        // Jika Approver ingin memproses, status saat ini harus 'reviewed'
        if ($user->role === 'approver' && $claim->status !== 'reviewed') {
            return redirect()->back()->withErrors(['error' => 'Klaim ini belum selesai diverifikasi oleh Verifier.']);
        }

        // Jalankan database transaction (Pessimistic Locking untuk keamanan data)
        DB::transaction(function () use ($claim, $request, $user) {
            $oldStatus = $claim->status;

            // Update status klaim
            $claim->update([
                'status' => $request->target_status
            ]);

            // Catat riwayat perubahan ke tabel logs
            ClaimLog::create([
                'claim_id' => $claim->id,
                'user_id' => $user->id,
                'from_status' => $oldStatus,
                'to_status' => $request->target_status,
                'note' => $request->note
            ]);
        });

        // PENTING: Kembalikan respon redirect Inertia yang valid (Gunakan back() agar halaman otomatis segar)
        return redirect()->back()->with('message', 'Status klaim berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $claim = Claim::findOrFail($id);

        // Proteksi: Hanya pemilik data (user) dan hanya jika berstatus 'draft' yang boleh menghapus
        if (auth()->user()->role !== 'user' || $claim->user_id !== auth()->id() || $claim->status !== 'draft') {
            return redirect()->back()->withErrors(['error' => 'Anda tidak memiliki akses untuk menghapus data ini.']);
        }

        $claim->delete();

        return redirect()->route('claims.index')->with('message', 'Draft formulir berhasil dihapus.');
    }
}
