<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="min-width:600px;">
        <thead style="background:#FFF7EE;">
            <tr>
                <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">#</th>
                <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">{{ @trans('portal.user_details') }}</th>
                <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">{{ @trans('portal.role') }}</th>
                <th class="text-uppercase fw-bold" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">{{ @trans('portal.website') }}</th>
                <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">{{ @trans('portal.date') }}</th>
                <th class="text-uppercase fw-bold text-center" style="font-size:12px; color:#92400e; white-space:nowrap; padding: 15px 20px;">{{ @trans('portal.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if($users->isEmpty())
                <tr>
                    <td colspan="6" class="text-center py-5" style="color:#a8a29e; font-size:0.9rem;">
                        <div class="mb-2"><i class="fa fa-search" style="font-size: 2rem; opacity: 0.5;"></i></div>
                        No users found matching your criteria.
                    </td>
                </tr>
            @else
                @foreach($users as $index => $user)
                <tr>
                    <td style="font-size:13px; color:#78716c; font-weight:600; padding: 15px 20px;">
                        {{ $users->firstItem() + $index }}
                    </td>
                    <td style="padding: 15px 20px;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 35px; height: 35px; border-radius: 8px; background: #FF9933; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:13px; font-weight:600; color:#1c1917;">{{ $user->name }}</div>
                                <div style="font-size:12px; color:#78716c;">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center" style="padding: 15px 20px;">
                        @if($user->role === 'super_admin')
                            <span class="badge" style="background:#dbeafe; color:#1e40af; font-size:11px; padding: 5px 10px; border-radius: 6px;">Super Admin</span>
                        @else
                            <span class="badge" style="background:#d1fae5; color:#065f46; font-size:11px; padding: 5px 10px; border-radius: 6px;">Admin</span>
                        @endif
                    </td>
                    <td style="padding: 15px 20px;">
                        @if($user->webpage_url)
                            <a href="{{ $user->webpage_url }}" target="_blank" style="font-size:13px; color:#d97706; text-decoration: none; font-weight: 500;">
                                <i class="fa fa-link me-1"></i> {{ str_replace(['http://', 'https://'], '', $user->webpage_url) }}
                            </a>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="text-center" style="font-size:12px; color:#78716c; white-space:nowrap; padding: 15px 20px;">
                        {{ $user->created_at->format('d-m-Y') }}
                    </td>
                    <td class="text-center" style="padding: 15px 20px;">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.user.edit', $user->id) }}"
                               class="btn btn-sm" style="background:#FFF7EE; color:#d97706; border:1px solid #fde68a; padding:4px 10px;">
                                <i class="fa fa-edit"></i>
                            </a>
                            @if(auth()->id() !== $user->id)
                            <button type="button" class="btn btn-sm" style="background:#fff0f0; color:#ef4444; border:1px solid #fecaca; padding:4px 10px;"
                                    data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                <i class="fa fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                            <div class="modal-header border-bottom-0 pb-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-body text-center pt-0">
                                    <div class="mb-3">
                                        <i class="fa fa-exclamation-circle text-danger" style="font-size: 3.5rem;"></i>
                                    </div>
                                    <h4 class="fw-bold mb-2" style="color: #1c1917;">Delete User</h4>
                                    <p class="text-muted px-3">Are you sure you want to delete <strong>{{ $user->name }}</strong>? This action is permanent and cannot be undone.</p>
                                </div>
                                <div class="modal-footer border-top-0 justify-content-center pb-4">
                                    <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                                    <button type="submit" class="btn btn-danger px-4 fw-bold" style="border-radius: 8px;">Delete Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $users->links('admin.parts.pagination') }}
</div>
