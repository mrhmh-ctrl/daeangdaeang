@include('admin.inc.admin_header')
        <div class="content-header">
            <h2>견종 수정</h2>
        </div>
        <div class="admin-contents">

            <form method="POST" action="{{ route('admin.dogdict.update', $dog->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <table class="admin-form-table">
                    <tr>
                        <th>견종명 *</th>
                        <td><input type="text" name="name" value="{{ $dog->name }}" required></td>
                    </tr>
					<tr>
						<th>카테고리</th>
					<td>
						<select name="category_id">
							<option value="">선택</option>
							@foreach($categories as $category)
								<option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
									{{ $category->name }}
								</option>
							@endforeach
						</select>
					</td>
				</tr>
                    <tr>
                        <th>내용 *</th>
                        <td>
                            <textarea name="content" id="content" rows="20" style="width:100%;">{{ old('content', $dog->content) }}</textarea>
                        </td>
                    </tr>
                </table>
                <div class="form-buttons">
                    <a href="{{ route('admin.dogdict.index') }}">목록</a>
                    <button type="submit" class="btn-submit">수정</button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>.cke_notifications_area { display:none !important; }</style>
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
CKEDITOR.replace('content', {
    language: 'ko',
    height: 500,
    allowedContent: true,
    extraPlugins: 'uploadimage,image2',
    toolbar: [
        { name: 'document', items: ['Source'] },
        { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
        { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
        '/',
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
        { name: 'links', items: ['Link', 'Unlink'] },
        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
        '/',
        { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
        { name: 'colors', items: ['TextColor', 'BGColor'] },
    ],
});
</script>
@include('admin.inc.admin_footer')
