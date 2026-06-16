@include('admin.inc.admin_header')
<div class="content-header">
        <h2>{{ isset($member) ? '회원 수정' : '회원 등록' }}</h2>
    </div>
    <div class="admin-contents">

                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ isset($member) ? route('admin.member.update', $member->id) : route('admin.member.store') }}" method="POST">
                    @csrf
                    @if(isset($member)) @method('PUT') @endif

                    <div style="margin:20px 0 10px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444;">기본 정보</h4>
                    </div>
                    <table class="admin-form-table">
                    {{-- 아이디 로그인일 때만 표시 --}}
                    @if($login_type === 'id')
                    <tr>
                        <th>아이디 <span class="required">*</span></th>
                        <td>
                            <input type="text" name="user_id" value="{{ old('user_id', $member->user_id ?? '') }}"
                                placeholder="영문+숫자 20자 이내" {{ isset($member) ? 'readonly' : '' }}
                                style="{{ isset($member) ? 'background:#f5f5f5;' : '' }}">
                            @if(isset($member))
                                <span style="font-size:12px; color:#999; margin-left:8px;">아이디는 변경 불가합니다</span>
                            @endif
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <th>이메일 <span class="required">*</span></th>
                        <td>
                            <input type="email" name="email" value="{{ old('email', $member->email ?? '') }}"
                                placeholder="이메일 입력"
                                {{ ($login_type === 'email' && isset($member)) ? 'readonly' : '' }}
                                style="{{ ($login_type === 'email' && isset($member)) ? 'background:#f5f5f5;' : '' }}">
                            @if($login_type === 'email' && isset($member))
                                <span style="font-size:12px; color:#999; margin-left:8px;">이메일은 변경 불가합니다</span>
                            @endif
                        </td>
                    </tr>        
                        <tr>
                            <th>비밀번호</th>
                            <td>
                                <input type="password" name="password" placeholder="{{ isset($member) ? '변경시에만 입력' : '비밀번호 입력' }}">
                                @if(isset($member))
                                    <span style="font-size:12px; color:#999; margin-left:8px;">입력하지 않으면 기존 비밀번호 유지</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>이름 <span class="required">*</span></th>
                            <td><input type="text" name="name" value="{{ old('name', $member->name ?? '') }}" placeholder="이름 입력"></td>
                        </tr>
                        <tr>
                            <th>닉네임 <span class="required">*</span></th>
                            <td><input type="text" name="nick" value="{{ old('nick', $member->nick ?? '') }}" placeholder="닉네임 입력"></td>
                        </tr>
                        @if($login_type === 'id')
                        <tr>
                            <th>이메일 <span class="required">*</span></th>
                            <td>
                                <input type="email" name="email" value="{{ old('email', $member->email ?? '') }}"
                                    placeholder="이메일 입력">
                            </td>
                        </tr>
                        @endif
                        @if($login_type === 'email')
                        @endif
                        <tr>
                            <th>휴대폰</th>
                            <td><input type="text" name="hp" value="{{ old('hp', $member->hp ?? '') }}" placeholder="010-0000-0000"></td>
                        </tr>
                        <tr>
                            <th>생년월일</th>
                            <td><input type="text" name="birth" value="{{ old('birth', $member->birth ?? '') }}" placeholder="YYYY-MM-DD"></td>
                        </tr>
                        <tr>
                            <th>성별</th>
                            <td>
                                <select name="sex">
                                    <option value="">선택</option>
                                    <option value="M" {{ old('sex', $member->sex ?? '') == 'M' ? 'selected' : '' }}>남성</option>
                                    <option value="F" {{ old('sex', $member->sex ?? '') == 'F' ? 'selected' : '' }}>여성</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <div style="margin:20px 0 10px; border-top:2px solid #dee2e6; padding-top:15px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444;">주소 정보</h4>
                    </div>
                    <table class="admin-form-table">
                        <tr>
                            <th>우편번호</th>
                            <td><input type="text" name="zip" value="{{ old('zip', $member->zip ?? '') }}" placeholder="우편번호" style="width:120px;"></td>
                        </tr>
                        <tr>
                            <th>기본주소</th>
                            <td><input type="text" name="addr1" value="{{ old('addr1', $member->addr1 ?? '') }}" placeholder="기본주소"></td>
                        </tr>
                        <tr>
                            <th>상세주소</th>
                            <td><input type="text" name="addr2" value="{{ old('addr2', $member->addr2 ?? '') }}" placeholder="상세주소"></td>
                        </tr>
                    </table>

                    <div style="margin:20px 0 10px; border-top:2px solid #dee2e6; padding-top:15px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444;">권한 설정</h4>
                    </div>
                    <table class="admin-form-table">
                        <tr>
                            <th>회원 레벨</th>
                            <td>
                                <select name="level">
                                    <option value="1"  {{ old('level', $member->level ?? 1) == 1  ? 'selected' : '' }}>LV.1 준회원</option>
                                    <option value="2"  {{ old('level', $member->level ?? 1) == 2  ? 'selected' : '' }}>LV.2 정회원</option>
                                    <option value="3"  {{ old('level', $member->level ?? 1) == 3  ? 'selected' : '' }}>LV.3 우수회원</option>
                                    <option value="4"  {{ old('level', $member->level ?? 1) == 4  ? 'selected' : '' }}>LV.4 특별회원</option>
                                    <option value="5"  {{ old('level', $member->level ?? 1) == 5  ? 'selected' : '' }}>LV.5</option>
                                    <option value="6"  {{ old('level', $member->level ?? 1) == 6  ? 'selected' : '' }}>LV.6</option>
                                    <option value="7"  {{ old('level', $member->level ?? 1) == 7  ? 'selected' : '' }}>LV.7</option>
                                    <option value="8"  {{ old('level', $member->level ?? 1) == 8  ? 'selected' : '' }}>LV.8</option>
                                    <option value="9"  {{ old('level', $member->level ?? 1) == 9  ? 'selected' : '' }}>LV.9 매니저</option>
                                    <option value="10" {{ old('level', $member->level ?? 1) == 10 ? 'selected' : '' }}>LV.10 관리자</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>관리자 여부</th>
                            <td>
                                <select name="is_admin">
                                    <option value="N" {{ old('is_admin', $member->is_admin ?? 'N') == 'N' ? 'selected' : '' }}>일반회원</option>
                                    <option value="Y" {{ old('is_admin', $member->is_admin ?? 'N') == 'Y' ? 'selected' : '' }}>최고관리자</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>매니저 여부</th>
                            <td>
                                <select name="is_manager">
                                    <option value="N" {{ old('is_manager', $member->is_manager ?? 'N') == 'N' ? 'selected' : '' }}>일반회원</option>
                                    <option value="Y" {{ old('is_manager', $member->is_manager ?? 'N') == 'Y' ? 'selected' : '' }}>매니저</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>상태</th>
                            <td>
                                <select name="status">
                                    <option value="Y" {{ old('status', $member->status ?? 'Y') == 'Y' ? 'selected' : '' }}>정상</option>
                                    <option value="N" {{ old('status', $member->status ?? 'Y') == 'N' ? 'selected' : '' }}>탈퇴</option>
                                    <option value="B" {{ old('status', $member->status ?? 'Y') == 'B' ? 'selected' : '' }}>정지</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>정지 사유</th>
                            <td><textarea name="block_memo" rows="3" style="width:400px;">{{ old('block_memo', $member->block_memo ?? '') }}</textarea></td>
                        </tr>
                        <tr>
                            <th>메일 수신</th>
                            <td>
                                <select name="mailer_opt">
                                    <option value="N" {{ old('mailer_opt', $member->mailer_opt ?? 'N') == 'N' ? 'selected' : '' }}>미동의</option>
                                    <option value="Y" {{ old('mailer_opt', $member->mailer_opt ?? 'N') == 'Y' ? 'selected' : '' }}>동의</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    @if(isset($member))
                    <div style="margin:20px 0 10px; border-top:2px solid #dee2e6; padding-top:15px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444;">접속 정보</h4>
                    </div>
                    <table class="admin-form-table">
                        <tr>
                            <th>마지막 로그인</th>
                            <td style="font-size:13px;">{{ $member->login_at ? $member->login_at->format('Y-m-d H:i:s') : '-' }}</td>
                        </tr>
                        <tr>
                            <th>마지막 IP</th>
                            <td style="font-size:13px;">{{ $member->login_ip ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>가입일</th>
                            <td style="font-size:13px;">{{ $member->created_at ? $member->created_at->format('Y-m-d H:i:s') : '-' }}</td>
                        </tr>
                    </table>
                    @endif

                    <div class="form-buttons">
                        <a href="{{ route('admin.member.index') }}" class="btn-back">← 목록으로</a>
                        @if(isset($member))
                            <button type="button" class="btn-cancel" onclick="deleteMember({{ $member->id }}, '{{ $member->user_id }}')">삭제</button>
                        @endif
                        <button type="submit" class="btn-submit">{{ isset($member) ? '수정 완료' : '등록 완료' }}</button>
                    </div>
                </form>

                @if(isset($member))
                <div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
                    <div style="background:#fff; border-radius:8px; padding:30px; width:400px;">
                        <h4 style="margin-bottom:15px;">회원 삭제</h4>
                        <p><strong id="delete_member_id"></strong></p>
                        <p style="color:#d70000; font-size:13px;">삭제하면 복구가 불가능합니다.<br>정말 삭제하시겠습니까?</p>
                        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                            <button onclick="closeModal()" style="padding:8px 20px; background:#f0f0f0; border:0; border-radius:4px; cursor:pointer;">취소</button>
                            <form id="delete_form" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" style="padding:8px 20px; background:#d70000; color:#fff; border:0; border-radius:4px; cursor:pointer;">삭제</button>
                            </form>
                        </div>
                    </div>
                </div>
                <script>
                function deleteMember(id, userId) {
                    document.getElementById('delete_member_id').textContent = '「' + userId + '」';
                    document.getElementById('delete_form').action = '/admin/member/' + id;
                    document.getElementById('deleteModal').style.display = 'flex';
                }
                function closeModal() {
                    document.getElementById('deleteModal').style.display = 'none';
                }
                </script>
                @endif

            </div>
        </div>
    </div>
</div>
@include('admin.inc.admin_footer')
