@include('admin.inc.admin_header')

<div class="content-header">
    <h2>대시보드</h2>
</div>

<div class="admin-contents dashboard">
<div class="dash-row">
    <div class="dash-card" style="flex:3;">
        <div class="dash-card-title">최근 7일 방문자</div>
        <div class="chart-wrap">
            @php $max_visit = max(array_column($visit_chart, 'count')) ?: 1; @endphp
            @foreach($visit_chart as $v)
            <div class="chart-col">
                <div class="chart-bar-wrap">
                <div class="chart-bar" style="--bar-h:{{ round(($v['count'] / $max_visit) * 100) }}%; height:{{ round(($v['count'] / $max_visit) * 100) }}%">
                        <span class="chart-val">{{ $v['count'] }}</span>
                    </div>
                </div>
                <div class="chart-label">{{ $v['date'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
    <div style="flex:2; display:flex; flex-direction:column; gap:16px;">
        <div style="display:flex; gap:16px;">
        <div class="dash-card visit-stat" style="flex:1;">
            <div class="dash-card-title">오늘 방문자</div>
            <div class="dash-card-num" data-target="{{ $visit_today }}"><span class="num-val">0</span><span>명</span></div>
        </div>
        <div class="dash-card visit-stat" style="flex:1;">
            <div class="dash-card-title">최대 방문자</div>
            <div class="dash-card-num" data-target="{{ $visit_max }}"><span class="num-val">0</span><span>명</span></div>
        </div>
        </div>
        <div style="display:flex; gap:16px;">
        <div class="dash-card visit-stat" style="flex:1;">
        <div class="dash-card-title">전체 회원수</div>
        <div class="dash-card-num blue" data-target="{{ $total_members }}"><span class="num-val">0</span><span>명</span></div>
        </div>
        <div class="dash-card visit-stat" style="flex:1;">
            <div class="dash-card-title">전체 게시물</div>
            <div class="dash-card-num blue" data-target="{{ $total_posts }}"><span class="num-val">0</span><span>개</span></div>
        </div>
        </div>
    </div>
</div>
<div class="dash-row">
    <div class="dash-card half">
        <div class="dash-card-title">최근 등록 게시물</div>
        <table class="dash-table">
            <thead>
                <tr>
                    <th>제목</th>
                    <th>작성자</th>
                    <th>등록일</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_posts as $post)
                <tr>
                    <td class="ellipsis">{{ $post->title }}</td>
                    <td>{{ $post->writer_name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($post->created_at)->format('m/d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="3">게시물이 없습니다.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="dash-card half">
        <div class="dash-card-title">최근 가입 회원</div>
        <table class="dash-table">
            <thead>
                <tr>                        
                    <th>이름</th>
                    <th>아이디</th>
                    <th>가입일</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_members as $member)
                <tr>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->user_id ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($member->created_at)->format('m/d H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="3">회원이 없습니다.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="dash-row">
    <div class="dash-card wide">
        <div class="dash-card-title">서버 상태</div>
        <div class="server-grid">
            <div class="server-item">
                <div class="server-label">CPU 사용량</div>
                <div class="server-bar-wrap">
                    <div class="server-bar {{ $cpu_usage > 80 ? 'danger' : ($cpu_usage > 50 ? 'warning' : '') }}" style="--bar-w:{{ $cpu_usage }}%; width:{{ $cpu_usage }}%"></div>
                </div>
                <div class="server-val">{{ $cpu_usage }}%</div>
            </div>
            <div class="server-item">
                <div class="server-label">RAM 사용량</div>
                <div class="server-bar-wrap">
                    <div class="server-bar {{ $ram_usage > 80 ? 'danger' : ($ram_usage > 50 ? 'warning' : '') }}" style="--bar-w:{{ $ram_usage }}%; width:{{ $ram_usage }}%"></div>
                </div>
                <div class="server-val">{{ $ram_used }}GB / {{ $ram_total }}GB ({{ $ram_usage }}%)</div>
            </div>
            <div class="server-item">
                <div class="server-label">HDD 사용량</div>
                <div class="server-bar-wrap">
                    <div class="server-bar {{ $hdd_usage > 80 ? 'danger' : ($hdd_usage > 50 ? 'warning' : '') }}" style="--bar-w:{{ $hdd_usage }}%; width:{{ $hdd_usage }}%"></div>
                </div>
                <div class="server-val">{{ $hdd_used }}GB / {{ $hdd_total }}GB ({{ $hdd_usage }}%)</div>
            </div>
            <div class="server-item">
                <div class="server-label">MySQL</div>
                <div class="server-bar-wrap">
                <div class="server-bar" style="--bar-w:{{ min($mysql_usage, 100) }}%; width:{{ min($mysql_usage, 100) }}%"></div>
                 </div>
                <div class="server-val">{{ $mysql_usage }} MB</div>
            </div>
        </div>
    </div>
</div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[data-target]').forEach(function(el, index) {
        const target = parseInt(el.dataset.target);
        if (isNaN(target) || target === 0) { el.querySelector('.num-val').textContent = '0'; return; }
        let current = 0;
        const duration = 1000;
        const delay = index * 150;
        setTimeout(function() {
            const step = Math.ceil(target / (duration / 16));
            const timer = setInterval(function() {
                current += step;
                if (current >= target) { current = target; clearInterval(timer); }
                el.querySelector('.num-val').textContent = current.toLocaleString();
            }, 16);
        }, delay);
    });
});
</script>
@include('admin.inc.admin_footer')