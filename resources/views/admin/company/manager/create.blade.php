@include('admin.inc.admin_header')
    <div class="content-header">
        <h2>관리자 등록</h2>
    </div>
    <div class="admin-contents">

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.manager.store') }}" method="POST">
            @csrf
            <table class="admin-form-table">
                <tr>
                    <th>이름 <span class="required">*</span></th>
                    <td>
                        <input type="text" name="name"
                            value="{{ old('name') }}"
                            placeholder="이름 입력">
                    </td>
                </tr>
            <tr>
                    <th>직급 <span class="required">*</span></th>
                    <td>
                        <select name="rank" style="height:36px; border:1px solid #ddd; border-radius:4px; padding:0 10px; font-size:13px; width:150px;">
                            <option value="">선택</option>
                            <option value="프리랜서"      {{ old('rank', $manager->rank ?? '') == '프리랜서'      ? 'selected' : '' }}>프리랜서</option>
                            <option value="사원"      {{ old('rank', $manager->rank ?? '') == '사원'      ? 'selected' : '' }}>사원</option>
                            <option value="주임"      {{ old('rank', $manager->rank ?? '') == '주임'      ? 'selected' : '' }}>주임</option>
                            <option value="대리"      {{ old('rank', $manager->rank ?? '') == '대리'      ? 'selected' : '' }}>대리</option>
                            <option value="과장"      {{ old('rank', $manager->rank ?? '') == '과장'      ? 'selected' : '' }}>과장</option>
                            <option value="차장"      {{ old('rank', $manager->rank ?? '') == '차장'      ? 'selected' : '' }}>차장</option>
                            <option value="부장"      {{ old('rank', $manager->rank ?? '') == '부장'      ? 'selected' : '' }}>부장</option>
                            <option value="이사"      {{ old('rank', $manager->rank ?? '') == '이사'      ? 'selected' : '' }}>이사</option>
                            <option value="상무"      {{ old('rank', $manager->rank ?? '') == '상무'      ? 'selected' : '' }}>상무</option>
                            <option value="전무"      {{ old('rank', $manager->rank ?? '') == '전무'      ? 'selected' : '' }}>전무</option>
                            <option value="부사장"    {{ old('rank', $manager->rank ?? '') == '부사장'    ? 'selected' : '' }}>부사장</option>
                            <option value="사장"      {{ old('rank', $manager->rank ?? '') == '사장'      ? 'selected' : '' }}>사장</option>
                            <option value="대표이사"  {{ old('rank', $manager->rank ?? '') == '대표이사'  ? 'selected' : '' }}>대표이사</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>이메일 <span class="required">*</span></th>
                    <td>
                        <input type="email" name="email"
                            value="{{ old('email') }}"
                            placeholder="이메일 입력">
                    </td>
                </tr>
                <tr>
                    <th>연락처 <span class="required">*</span></th>
                    <td>
                        <input type="text" name="hp"
                            value="{{ old('hp') }}"
                            placeholder="010-0000-0000">
                    </td>
                </tr>
                <tr>
                    <th>비밀번호 <span class="required">*</span></th>
                    <td>
                        <input type="password" name="password"
                            placeholder="8자 이상 입력">
                    </td>
                </tr>
                <tr>
                    <th>비밀번호 확인 <span class="required">*</span></th>
                    <td>
                        <input type="password" name="password_confirmation"
                            placeholder="비밀번호 재입력">
                    </td>
                </tr>
                <tr>
                    <th>권한 <span class="required">*</span></th>
                    <td>
                        <label style="margin-right:20px; cursor:pointer;">
                            <input type="radio" name="is_admin" value="0"
                                {{ old('is_admin', '0') == '0' ? 'checked' : '' }}>
                            일반관리자
                        </label>
                        <label style="cursor:pointer;">
                            <input type="radio" name="is_admin" value="1"
                                {{ old('is_admin') == '1' ? 'checked' : '' }}>
                            마스터
                        </label>
                    </td>
                </tr>
            </table>

            <div class="form-buttons">
                <button type="button" class="btn-cancel" onclick="history.back()">취소</button>
                <button type="submit" class="btn-submit">등록</button>
            </div>
        </form>

    </div>
</div>
</div>
</div>
@include('admin.inc.admin_footer')