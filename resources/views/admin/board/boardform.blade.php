@include('admin.inc.admin_header')
                <div class="content-header">
                    <h2>게시판 추가</h2>
                    <div class="top-dict-buttons">
                        <a href="{{ route('admin.board.create') }}" class="btn-add"><li>+ 게시판 등록</li></a>
                    </div>
                </div>
                <div class="admin-contents">

                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                @endif

                <form action="{{ isset($board) ? route('admin.board.update', $board->id) : route('admin.board.store') }}" method="POST">
                    @csrf
                    @if(isset($board)) @method('PUT') @endif

                    <div style="margin:20px 0 10px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444; border-left:4px solid #0b57d0; padding-left:10px;">기본 설정</h4>
                    </div>
                    <table class="admin-form-table">
                        <tr>
                            <th>게시판ID <span class="required">*</span></th>
                            <td>
                                <input type="text" name="board_id" value="{{ old('board_id', $board->board_id ?? '') }}"
                                    placeholder="영문+숫자 (ex: notice, free)" {{ isset($board) ? 'readonly' : '' }}
                                    style="{{ isset($board) ? 'background:#f5f5f5;' : '' }}">
                                <span style="font-size:12px; color:#999; margin-left:8px;">영문+숫자만 입력 가능</span>
                            </td>
                        </tr>
                        <tr>
                            <th>게시판명 <span class="required">*</span></th>
                            <td><input type="text" name="board_name" value="{{ old('board_name', $board->board_name ?? '') }}" placeholder="게시판명 입력"></td>
                        </tr>
                        <tr>
                        <th>스킨</th>
                        <td>
                            @if(count($skins) > 0)
                                <select name="skin">
                                    @foreach($skins as $skin)
                                        <option value="{{ $skin }}" {{ old('skin', $board->skin ?? '') == $skin ? 'selected' : '' }}>{{ $skin }}</option>
                                    @endforeach
                                </select>
                                <span style="font-size:12px; color:#999; margin-left:8px;">스킨 위치: views/board/template/</span>
                            @else
                                <span style="font-size:12px; color:#999;">등록된 스킨이 없습니다. (views/board/template/ 에 스킨 폴더를 추가하세요)</span>
                                <input type="hidden" name="skin" value="">
                            @endif
                        </td>
                        </tr>
                        <tr>
    <th>상단 파일</th>
    <td>
        <select name="header_inc">
            <option value="">없음</option>
            @foreach($incs as $inc)
                <option value="{{ $inc }}" {{ old('header_inc', $board->header_inc ?? '') == $inc ? 'selected' : '' }}>{{ $inc }}</option>
            @endforeach
        </select>
        <span style="font-size:12px; color:#999; margin-left:8px;">views/inc/ 기준</span>
    </td>
    </tr>
    <tr>
        <th>하단 파일</th>
        <td>
            <select name="footer_inc">
                <option value="">없음</option>
                @foreach($incs as $inc)
                    <option value="{{ $inc }}" {{ old('footer_inc', $board->footer_inc ?? '') == $inc ? 'selected' : '' }}>{{ $inc }}</option>
                @endforeach
            </select>
            <span style="font-size:12px; color:#999; margin-left:8px;">views/inc/ 기준</span>
        </td>
    </tr>
                        <tr>
                            <th>정렬순서</th>
                            <td><input type="number" name="sort_order" value="{{ old('sort_order', $board->sort_order ?? 0) }}" style="width:100px;"></td>
                        </tr>
                        <tr>
                            <th>사용여부</th>
                            <td>
                                <select name="status">
                                    <option value="Y" {{ old('status', $board->status ?? 'Y') == 'Y' ? 'selected' : '' }}>사용</option>
                                    <option value="N" {{ old('status', $board->status ?? 'Y') == 'N' ? 'selected' : '' }}>미사용</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <div style="margin:30px 0 10px; border-top:2px solid #dee2e6; padding-top:20px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444; border-left:4px solid #0b57d0; padding-left:10px;">권한 설정</h4>
                    </div>
                    <table class="admin-form-table">
                        <tr>
                            <th>목록 권한</th>
                            <td>
                                <select name="list_level">
                                    <option value="0" {{ old('list_level', $board->list_level ?? 0) == 0 ? 'selected' : '' }}>전체</option>
                                    <option value="1" {{ old('list_level', $board->list_level ?? 0) == 1 ? 'selected' : '' }}>회원 (LV.1)</option>
                                    <option value="2" {{ old('list_level', $board->list_level ?? 0) == 2 ? 'selected' : '' }}>관리자 (LV.2)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>읽기 권한</th>
                            <td>
                                <select name="read_level">
                                    <option value="0" {{ old('read_level', $board->read_level ?? 0) == 0 ? 'selected' : '' }}>전체</option>
                                    <option value="1" {{ old('read_level', $board->read_level ?? 0) == 1 ? 'selected' : '' }}>회원 (LV.1)</option>
                                    <option value="2" {{ old('read_level', $board->read_level ?? 0) == 2 ? 'selected' : '' }}>관리자 (LV.2)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>쓰기 권한</th>
                            <td>
                                <select name="write_level">
                                    <option value="0" {{ old('write_level', $board->write_level ?? 1) == 0 ? 'selected' : '' }}>전체</option>
                                    <option value="1" {{ old('write_level', $board->write_level ?? 1) == 1 ? 'selected' : '' }}>회원 (LV.1)</option>
                                    <option value="2" {{ old('write_level', $board->write_level ?? 1) == 2 ? 'selected' : '' }}>관리자 (LV.2)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>댓글 권한</th>
                            <td>
                                <select name="comment_level">
                                    <option value="0" {{ old('comment_level', $board->comment_level ?? 1) == 0 ? 'selected' : '' }}>전체</option>
                                    <option value="1" {{ old('comment_level', $board->comment_level ?? 1) == 1 ? 'selected' : '' }}>회원 (LV.1)</option>
                                    <option value="2" {{ old('comment_level', $board->comment_level ?? 1) == 2 ? 'selected' : '' }}>관리자 (LV.2)</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>다운로드 권한</th>
                            <td>
                                <select name="download_level">
                                    <option value="0" {{ old('download_level', $board->download_level ?? 1) == 0 ? 'selected' : '' }}>전체</option>
                                    <option value="1" {{ old('download_level', $board->download_level ?? 1) == 1 ? 'selected' : '' }}>회원 (LV.1)</option>
                                    <option value="2" {{ old('download_level', $board->download_level ?? 1) == 2 ? 'selected' : '' }}>관리자 (LV.2)</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <div style="margin:30px 0 10px; border-top:2px solid #dee2e6; padding-top:20px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444; border-left:4px solid #0b57d0; padding-left:10px;">글쓰기 설정</h4>
                    </div>
                    <table class="admin-form-table">
                        <tr>
                            <th>에디터 사용</th>
                            <td>
                                <select name="use_editor">
                                    <option value="Y" {{ old('use_editor', $board->use_editor ?? 'Y') == 'Y' ? 'selected' : '' }}>CKEditor 사용</option>
                                    <option value="N" {{ old('use_editor', $board->use_editor ?? 'Y') == 'N' ? 'selected' : '' }}>일반 텍스트</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>첨부파일</th>
                            <td>
                                <select name="use_file">
                                    <option value="Y" {{ old('use_file', $board->use_file ?? 'Y') == 'Y' ? 'selected' : '' }}>사용</option>
                                    <option value="N" {{ old('use_file', $board->use_file ?? 'Y') == 'N' ? 'selected' : '' }}>미사용</option>
                                </select>
                                <span style="font-size:12px; color:#999; margin-left:10px;">최대</span>
                                <input type="number" name="file_count" value="{{ old('file_count', $board->file_count ?? 5) }}" style="width:60px; margin:0 5px;">
                                <span style="font-size:12px; color:#999;">개 /</span>
                                <input type="number" name="file_size" value="{{ old('file_size', $board->file_size ?? 10) }}" style="width:60px; margin:0 5px;">
                                <span style="font-size:12px; color:#999;">MB</span>
                            </td>
                        </tr>
                        <tr>
                            <th>비밀글</th>
                            <td>
                                <select name="use_secret">
                                    <option value="N" {{ old('use_secret', $board->use_secret ?? 'N') == 'N' ? 'selected' : '' }}>미사용</option>
                                    <option value="Y" {{ old('use_secret', $board->use_secret ?? 'N') == 'Y' ? 'selected' : '' }}>사용</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>공지</th>
                            <td>
                                <select name="use_notice">
                                    <option value="Y" {{ old('use_notice', $board->use_notice ?? 'Y') == 'Y' ? 'selected' : '' }}>사용</option>
                                    <option value="N" {{ old('use_notice', $board->use_notice ?? 'Y') == 'N' ? 'selected' : '' }}>미사용</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>추천/비추천</th>
                            <td>
                                <select name="use_recommend">
                                    <option value="Y" {{ old('use_recommend', $board->use_recommend ?? 'Y') == 'Y' ? 'selected' : '' }}>사용</option>
                                    <option value="N" {{ old('use_recommend', $board->use_recommend ?? 'Y') == 'N' ? 'selected' : '' }}>미사용</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>카테고리</th>
                            <td>
                                <select name="use_category">
                                    <option value="N" {{ old('use_category', $board->use_category ?? 'N') == 'N' ? 'selected' : '' }}>미사용</option>
                                    <option value="Y" {{ old('use_category', $board->use_category ?? 'N') == 'Y' ? 'selected' : '' }}>사용</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <div style="margin:30px 0 10px; border-top:2px solid #dee2e6; padding-top:20px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444; border-left:4px solid #0b57d0; padding-left:10px;">댓글 설정</h4>
                    </div>
                    <table class="admin-form-table">
                        <tr>
                            <th>댓글 사용</th>
                            <td>
                                <select name="use_comment">
                                    <option value="Y" {{ old('use_comment', $board->use_comment ?? 'Y') == 'Y' ? 'selected' : '' }}>사용</option>
                                    <option value="N" {{ old('use_comment', $board->use_comment ?? 'Y') == 'N' ? 'selected' : '' }}>미사용</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>대댓글</th>
                            <td>
                                <select name="use_comment_reply">
                                    <option value="Y" {{ old('use_comment_reply', $board->use_comment_reply ?? 'Y') == 'Y' ? 'selected' : '' }}>사용</option>
                                    <option value="N" {{ old('use_comment_reply', $board->use_comment_reply ?? 'Y') == 'N' ? 'selected' : '' }}>미사용</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <div style="margin:30px 0 10px; border-top:2px solid #dee2e6; padding-top:20px;">
                        <h4 style="font-size:14px; font-weight:700; color:#444; border-left:4px solid #0b57d0; padding-left:10px;">목록 설정</h4>
                    </div>
                    <table class="admin-form-table">
                        <tr>
                            <th>페이지당 목록수</th>
                            <td>
                                <input type="number" name="list_count" value="{{ old('list_count', $board->list_count ?? 15) }}" style="width:80px;">
                                <span style="font-size:12px; color:#999; margin-left:5px;">개</span>
                            </td>
                        </tr>
                        <tr>
                            <th>페이지당 댓글수</th>
                            <td>
                                <input type="number" name="comment_count" value="{{ old('comment_count', $board->comment_count ?? 10) }}" style="width:80px;">
                                <span style="font-size:12px; color:#999; margin-left:5px;">개</span>
                            </td>
                        </tr>
                        <tr>
                            <th>새글 기준</th>
                            <td>
                                <input type="number" name="new_hour" value="{{ old('new_hour', $board->new_hour ?? 24) }}" style="width:80px;">
                                <span style="font-size:12px; color:#999; margin-left:5px;">시간</span>
                            </td>
                        </tr>
                        <tr>
                            <th>썸네일</th>
                            <td>
                                <select name="use_thumb">
                                    <option value="N" {{ old('use_thumb', $board->use_thumb ?? 'N') == 'N' ? 'selected' : '' }}>미사용</option>
                                    <option value="Y" {{ old('use_thumb', $board->use_thumb ?? 'N') == 'Y' ? 'selected' : '' }}>사용</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                        <th>번호 표시</th>
                        <td>
                            <select name="use_num">
                                <option value="Y" {{ old('use_num', $board->use_num ?? 'Y') == 'Y' ? 'selected' : '' }}>표시</option>
                                <option value="N" {{ old('use_num', $board->use_num ?? 'Y') == 'N' ? 'selected' : '' }}>숨김</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>제목 표시</th>
                        <td>
                            <select name="use_title">
                                <option value="Y" {{ old('use_title', $board->use_title ?? 'Y') == 'Y' ? 'selected' : '' }}>표시</option>
                                <option value="N" {{ old('use_title', $board->use_title ?? 'Y') == 'N' ? 'selected' : '' }}>숨김</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>작성자 표시</th>
                        <td>
                            <select name="use_writer">
                                <option value="Y" {{ old('use_writer', $board->use_writer ?? 'Y') == 'Y' ? 'selected' : '' }}>표시</option>
                                <option value="N" {{ old('use_writer', $board->use_writer ?? 'Y') == 'N' ? 'selected' : '' }}>숨김</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>날짜 표시</th>
                        <td>
                            <select name="use_date">
                                <option value="Y" {{ old('use_date', $board->use_date ?? 'Y') == 'Y' ? 'selected' : '' }}>표시</option>
                                <option value="N" {{ old('use_date', $board->use_date ?? 'Y') == 'N' ? 'selected' : '' }}>숨김</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>조회수 표시</th>
                        <td>
                            <select name="use_view_count">
                                <option value="Y" {{ old('use_view_count', $board->use_view_count ?? 'Y') == 'Y' ? 'selected' : '' }}>표시</option>
                                <option value="N" {{ old('use_view_count', $board->use_view_count ?? 'Y') == 'N' ? 'selected' : '' }}>숨김</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>추천 표시</th>
                        <td>
                            <select name="use_recommend_col">
                                <option value="Y" {{ old('use_recommend_col', $board->use_recommend_col ?? 'Y') == 'Y' ? 'selected' : '' }}>표시</option>
                                <option value="N" {{ old('use_recommend_col', $board->use_recommend_col ?? 'Y') == 'N' ? 'selected' : '' }}>숨김</option>
                            </select>
                        </td>
                    </tr>
                        <tr>
                            <th>조회수 표시</th>
                            <td>
                                <select name="use_view_count">
                                    <option value="Y" {{ old('use_view_count', $board->use_view_count ?? 'Y') == 'Y' ? 'selected' : '' }}>사용</option>
                                    <option value="N" {{ old('use_view_count', $board->use_view_count ?? 'Y') == 'N' ? 'selected' : '' }}>미사용</option>
                                </select>
                            </td>
                        </tr>
                    </table>

                    <div class="form-buttons">
                        <a href="{{ route('admin.board.index') }}" class="btn-back">← 목록으로</a>
                        @if(isset($board))
                            <button type="button" class="btn-cancel" onclick="deleteBoard({{ $board->id }}, '{{ $board->board_name }}')">삭제</button>
                        @endif
                        <button type="submit" class="btn-submit">{{ isset($board) ? '수정 완료' : '등록 완료' }}</button>
                    </div>
                </form>

                @if(isset($board))
                <div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
                    <div style="background:#fff; border-radius:8px; padding:30px; width:400px;">
                        <h4 style="margin-bottom:15px;">게시판 삭제</h4>
                        <p><strong id="delete_board_name"></strong></p>
                        <p style="color:#d70000; font-size:13px;">삭제시 모든 게시글과 댓글이 함께 삭제됩니다.<br>정말 삭제하시겠습니까?</p>
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
                function deleteBoard(id, name) {
                    document.getElementById('delete_board_name').textContent = '「' + name + '」';
                    document.getElementById('delete_form').action = '/admin/board/' + id;
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