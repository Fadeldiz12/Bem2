@extends('layouts.public')
@section('title','Jadwal Peminjaman Tempat')
@section('meta_description','Kalender jadwal peminjaman tempat dan kegiatan organisasi mahasiswa Politeknik Negeri Medan beserta status persetujuannya dari BEM Polmed.')
@section('content')
<section class="container py-5">
  <div class="text-center mb-4"><h1 class="page-pill">Jadwal Peminjaman Tempat</h1></div>
  <div class="d-flex justify-content-center gap-3 mb-4 small flex-wrap">
    <span><span class="legend-dot" style="background:#f59e0b"></span> Menunggu</span>
    <span><span class="legend-dot" style="background:#10b981"></span> Disetujui</span>
    <span><span class="legend-dot" style="background:#ef4444"></span> Ditolak</span>
  </div>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="d-flex align-items-center gap-2">
      <button class="btn btn-light rounded-circle" id="prevMonth"><i class="bi bi-chevron-left"></i></button>
      <h5 class="fw-bold mb-0" id="monthLabel" style="color:var(--purple-deep)"></h5>
      <button class="btn btn-light rounded-circle" id="nextMonth"><i class="bi bi-chevron-right"></i></button>
    </div>
    <div class="btn-group">
      <button class="btn btn-purple btn-sm" id="viewMonthBtn"><i class="bi bi-grid-3x3"></i> Month</button>
      <button class="btn btn-outline-secondary btn-sm" id="viewListBtn"><i class="bi bi-list-ul"></i> List</button>
    </div>
  </div>
  <div id="monthView" class="card-soft p-3">
    <div class="calendar-header-row">
      @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $d)<div class="calendar-header-cell">{{ $d }}</div>@endforeach
    </div>
    <div id="calendarGrid"></div>
  </div>
  <div id="listView" class="card-soft p-3 d-none"><div id="listContainer"></div></div>
</section>
@endsection
@section('scripts')
<script>
let current = new Date();
const monthNames = ["January","February","March","April","May","June","July","August","September","October","November","December"];
async function fetchActivities(year, month) {
  const res = await fetch(`/api/activities?year=${year}&month=${month}`);
  const json = await res.json();
  return json.data || [];
}
function renderCalendar(activities) {
  const year = current.getFullYear(), month = current.getMonth();
  document.getElementById('monthLabel').innerText = monthNames[month] + ' ' + year;
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const grid = document.getElementById('calendarGrid');
  grid.innerHTML = '';
  const byDate = {};
  activities.forEach(a => { const d = parseInt(a.activity_date.split('-')[2]); if (!byDate[d]) byDate[d] = []; byDate[d].push(a); });
  const cells = [];
  for (let i = 0; i < firstDay; i++) cells.push(null);
  for (let d = 1; d <= daysInMonth; d++) cells.push(d);
  while (cells.length % 7 !== 0) cells.push(null);
  for (let i = 0; i < cells.length; i += 7) {
    const week = cells.slice(i, i + 7);
    const row = document.createElement('div'); row.className = 'calendar-week-row';
    week.forEach(d => {
      const cell = document.createElement('div');
      if (d === null) { cell.className = 'calendar-day-cell empty'; }
      else {
        cell.className = 'calendar-day-cell';
        const items = byDate[d] || [];
        const badges = items.map(a => `<div class="calendar-event-badge" style="background:${a.status_color}" title="${a.title} (${a.status_label})">${a.title}</div>`).join('');
        cell.innerHTML = `<div class="calendar-day-num">${d}</div>${badges}`;
      }
      row.appendChild(cell);
    });
    grid.appendChild(row);
  }
}
function renderList(activities) {
  const c = document.getElementById('listContainer');
  if (!activities.length) { c.innerHTML = '<p class="text-muted text-center py-4">Tidak ada jadwal bulan ini.</p>'; return; }
  c.innerHTML = activities.map(a => `<div class="rounded-3 px-3 py-2 mb-2 fw-semibold text-white" style="background:${a.status_color}">${a.title} <span class="badge bg-white text-dark ms-1">${a.status_label}</span><span class="float-end small fw-normal">${a.activity_date}${a.start_time ? ' · ' + a.start_time.substring(0,5) : ''} @ ${a.location ?? ''}</span></div>`).join('');
}
async function loadAndRender() {
  const acts = await fetchActivities(current.getFullYear(), current.getMonth() + 1);
  renderCalendar(acts); renderList(acts);
}
document.getElementById('prevMonth').onclick = () => { current.setMonth(current.getMonth() - 1); loadAndRender(); };
document.getElementById('nextMonth').onclick = () => { current.setMonth(current.getMonth() + 1); loadAndRender(); };
document.getElementById('viewMonthBtn').onclick = () => { document.getElementById('monthView').classList.remove('d-none'); document.getElementById('listView').classList.add('d-none'); };
document.getElementById('viewListBtn').onclick = () => { document.getElementById('monthView').classList.add('d-none'); document.getElementById('listView').classList.remove('d-none'); };
loadAndRender();
</script>
@endsection
