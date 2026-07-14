@extends('layouts.app')

@section('title', 'Verifikasi Akun Pelanggan')
@section('page-title', 'Verifikasi Akun')

@section('content')

{{-- Filter --}}
<div class="mb-5 flex gap-2 flex-wrap">
    <a href="/admin/users" class="btn-nude text-xs py-2 px-4 {{ !request('status') ? 'btn-nude-filled' : '' }}">Semua</a>
    <a href="/admin/users?status=pending" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'pending' ? 'btn-nude-filled' : '' }}">Pending</a>
    <a href="/admin/users?status=verified" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'verified' ? 'btn-nude-filled' : '' }}">Verified</a>
    <a href="/admin/users?status=rejected" class="btn-nude text-xs py-2 px-4 {{ request('status') === 'rejected' ? 'btn-nude-filled' : '' }}">Rejected</a>
</div>

<div class="border border-graymedium" style="background:var(--color-white);">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Daftar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="text-charcoal-light">{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td class="font-medium">{{ $user->name }}</td>
                        <td class="text-charcoal-light">{{ $user->email }}</td>
                        <td class="text-charcoal-light">{{ $user->phone ?? '—' }}</td>
                        <td class="text-charcoal-light">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            @if($user->account_status === 'pending')
                                <span class="badge-pending">Pending</span>
                            @elseif($user->account_status === 'verified')
                                <span class="badge-approved">Verified</span>
                            @else
                                <span class="badge-rejected">Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if($user->account_status === 'pending')
                                {{-- Masih pending: tampilkan tombol aksi --}}
                                <div class="flex gap-2">
                                    <form id="approve-user-{{ $user->id }}" method="POST" action="/admin/users/{{ $user->id }}/verify">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="approve">
                                    </form>
                                    <button type="button" class="btn-nude text-xs py-1.5 px-3"
                                            style="border-color:#059669; color:#059669;"
                                            onclick="confirmAction('approve-user-{{ $user->id }}', 'approve', '{{ $user->name }}')">
                                        Setujui
                                    </button>

                                    <form id="reject-user-{{ $user->id }}" method="POST" action="/admin/users/{{ $user->id }}/verify">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="reject">
                                    </form>
                                    <button type="button" class="btn-nude btn-danger text-xs py-1.5 px-3"
                                            onclick="confirmAction('reject-user-{{ $user->id }}', 'reject', '{{ $user->name }}')">
                                        Tolak
                                    </button>
                                </div>

                            @elseif($user->account_status === 'verified')
                                {{-- Sudah disetujui: histori, tidak bisa diubah --}}
                                <div class="flex items-center gap-1.5 px-3 py-1.5 border border-emerald-200 bg-emerald-50 w-fit">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-xs font-medium text-emerald-700 tracking-wide">Disetujui</span>
                                </div>

                            @else
                                {{-- Sudah ditolak: histori, tidak bisa diubah --}}
                                <div class="flex items-center gap-1.5 px-3 py-1.5 border border-red-200 bg-red-50 w-fit">
                                    <svg class="w-3.5 h-3.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="text-xs font-medium text-red-600 tracking-wide">Ditolak</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-charcoal-light text-sm">Tidak ada data pelanggan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $users->withQueryString()->links() }}</div>

@endsection
