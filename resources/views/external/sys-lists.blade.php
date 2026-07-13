@php
    $lang = request()->lang;
    $dir = $lang == "Ar" ? "rtl" : "ltr";
@endphp

<!DOCTYPE html>
<html lang="{{ $lang }}">

<head>
    @include('links')
    <title>System Lists Management</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --bg: #f0f2f5;
            --surface: #ffffff;
            --surface-2: #f8f9fa;
            --surface-3: #eef0f3;
            --primary: #4361ee;
            --primary-soft: rgba(67, 97, 238, .10);
            --success: #2dc653;
            --danger: #ef233c;
            --warning: #f77f00;
            --text: #1a1d23;
            --text-muted: #6c757d;
            --border: #e2e6ea;
            --shadow-sm: 0 1px 4px rgba(0, 0, 0, .07);
            --shadow-md: 0 4px 18px rgba(0, 0, 0, .10);
            --radius: 14px;
            --radius-sm: 8px;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            padding-bottom: 60px;
        }

        /* ── Header ─────────────────────────────── */
        .page-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 900;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
        }

        .page-header h1 {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-header h1 .badge-count {
            background: var(--primary-soft);
            color: var(--primary);
            font-size: 13px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 20px;
        }

        /* ── Page body ───────────────────────────── */
        .page-body {
            max-width: 1100px;
            margin: 28px auto;
            padding: 0 20px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ── Flash alerts ────────────────────────── */
        .flash {
            border-radius: var(--radius-sm);
            padding: 12px 18px;
            font-weight: 500;
            margin-bottom: 4px;
        }

        /* ── SysList Card (Level 1) ──────────────── */
        .sl-card {
            background: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid var(--border);
        }

        .sl-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 20px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .sl-card-header .sl-icon {
            width: 36px;
            height: 36px;
            background: var(--primary-soft);
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 16px;
            flex-shrink: 0;
        }

        .sl-name-input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 17px;
            font-weight: 700;
            color: var(--text);
            outline: none;
            min-width: 0;
        }

        .sl-name-input:focus {
            background: var(--surface-2);
            border-radius: 6px;
            padding: 2px 8px;
        }

        .meta-badge {
            font-size: 12px;
            font-weight: 500;
            color: var(--text-muted);
            white-space: nowrap;
        }

        /* ── Icon buttons ────────────────────────── */
        .icon-btn {
            border: none;
            background: none;
            cursor: pointer;
            border-radius: var(--radius-sm);
            padding: 6px 10px;
            font-size: 15px;
            transition: background .15s, color .15s;
            line-height: 1;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .icon-btn-primary {
            color: var(--primary);
        }

        .icon-btn-primary:hover {
            background: var(--primary-soft);
        }

        .icon-btn-success {
            color: var(--success);
        }

        .icon-btn-success:hover {
            background: rgba(45, 198, 83, .10);
        }

        .icon-btn-danger {
            color: var(--danger);
        }

        .icon-btn-danger:hover {
            background: rgba(239, 35, 60, .10);
        }

        .icon-btn-warning {
            color: var(--warning);
        }

        .icon-btn-warning:hover {
            background: rgba(247, 127, 0, .10);
        }

        /* ── List Items Table (Level 2) ──────────── */
        .li-table {
            width: 100%;
            border-collapse: collapse;
        }

        .li-table thead th {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-muted);
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            background: var(--surface-2);
            white-space: nowrap;
        }

        .li-table tbody tr {
            border-bottom: 1px solid var(--border);
        }

        .li-table tbody tr:last-child {
            border-bottom: none;
        }

        .li-table tbody td {
            padding: 10px 14px;
            vertical-align: middle;
        }

        .li-table tbody tr:hover td {
            background: var(--surface-2);
        }

        /* thumbnail */
        .item-thumb {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 1px solid var(--border);
            background: var(--surface-3);
        }

        /* inline input inside table cell */
        .cell-input {
            border: 1px solid transparent;
            background: transparent;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 14px;
            width: 100%;
            outline: none;
            transition: border .15s, background .15s;
        }

        .cell-input:focus {
            border-color: var(--primary);
            background: var(--surface);
        }

        /* expand button cell */
        .expand-btn {
            border: none;
            background: none;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }

        .expand-btn:hover {
            background: var(--surface-3);
            color: var(--primary);
        }

        .expand-btn .arrow {
            transition: transform .2s;
            display: inline-block;
        }

        .expand-btn.open .arrow {
            transform: rotate(90deg);
        }

        /* ── Sub-list panel (Level 3) ────────────── */
        .sub-panel {
            display: none;
            background: var(--surface-2);
        }

        .sub-panel.open {
            display: table-row-group;
        }

        .sub-panel-inner {
            padding: 16px 20px 20px 60px;
        }

        .sub-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .sub-table thead th {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--text-muted);
            padding: 8px 10px;
            border-bottom: 1px solid var(--border);
            background: var(--surface-3);
        }

        .sub-table tbody tr {
            border-bottom: 1px solid var(--border);
        }

        .sub-table tbody tr:last-child {
            border-bottom: none;
        }

        .sub-table tbody td {
            padding: 8px 10px;
            vertical-align: middle;
        }

        .sub-table tbody tr:hover td {
            background: rgba(255, 255, 255, .7);
        }

        .sub-thumb {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid var(--border);
        }

        .sub-cell-input {
            border: 1px solid transparent;
            background: transparent;
            border-radius: 5px;
            padding: 3px 7px;
            font-size: 13px;
            width: 100%;
            outline: none;
            transition: border .15s, background .15s;
        }

        .sub-cell-input:focus {
            border-color: var(--primary);
            background: var(--surface);
        }

        /* Add row */
        .add-row td {
            background: rgba(67, 97, 238, .03);
        }

        .add-placeholder {
            font-style: italic;
            color: var(--text-muted);
        }

        /* ── Add card ────────────────────────────── */
        .add-sl-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 2px dashed var(--border);
            border-radius: var(--radius);
            padding: 36px;
            cursor: pointer;
            transition: border-color .2s, background .2s, color .2s;
            color: var(--text-muted);
        }

        .add-sl-card:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-soft);
        }

        /* ── Pill btn ────────────────────────────── */
        .pill-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            border-radius: 20px;
            padding: 7px 18px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity .2s, transform .1s;
            text-decoration: none;
        }

        .pill-btn:active {
            transform: scale(.97);
        }

        .pill-btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .pill-btn-primary:hover {
            opacity: .88;
            color: #fff;
        }

        .pill-btn-outline {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
        }

        .pill-btn-outline:hover {
            background: var(--surface-3);
        }

        /* ── Modal tweaks ────────────────────────── */
        .modal-content {
            border-radius: var(--radius);
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
        }

        .modal-footer {
            border-top: 1px solid var(--border);
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .form-control {
            border-radius: var(--radius-sm);
            border: 1px solid var(--border);
            padding: 10px 12px;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-soft);
        }

        /* img preview */
        .img-preview-wrap {
            position: relative;
            display: inline-block;
        }

        .img-preview {
            width: 72px;
            height: 72px;
            border-radius: var(--radius-sm);
            object-fit: cover;
            border: 1px solid var(--border);
            display: block;
            cursor: pointer;
        }

        .img-preview-label {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .35);
            color: #fff;
            font-size: 20px;
            border-radius: var(--radius-sm);
            opacity: 0;
            cursor: pointer;
            transition: opacity .2s;
        }

        .img-preview-wrap:hover .img-preview-label {
            opacity: 1;
        }

        /* price badge */
        .price-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: rgba(247, 127, 0, .12);
            color: var(--warning);
            font-size: 12px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
            white-space: nowrap;
        }

        /* responsive */
        @media (max-width: 768px) {
            .li-table thead {
                display: none;
            }

            .li-table,
            .li-table tbody,
            .li-table tr,
            .li-table td {
                display: block;
                width: 100%;
            }

            .li-table td {
                padding: 6px 14px;
            }

            .sub-panel-inner {
                padding-left: 16px;
            }
        }

        /* ── iOS-style Toggle Switch ─────────────── */
        .toggle-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
        }

        .toggle-wrap .toggle-label {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .ios-toggle {
            position: relative;
            display: inline-block;
            width: 38px;
            height: 22px;
            flex-shrink: 0;
        }

        .ios-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .ios-toggle .track {
            position: absolute;
            inset: 0;
            background: var(--border);
            border-radius: 22px;
            transition: background .25s;
            cursor: pointer;
        }

        .ios-toggle .track::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 16px;
            height: 16px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .25);
            transition: transform .25s;
        }

        .ios-toggle input:checked+.track {
            background: var(--success);
        }

        .ios-toggle input:checked+.track::after {
            transform: translateX(16px);
        }
    </style>
</head>

<body dir="{{ $dir }}">
    @include('nav-aside')

    <!-- Page Header -->
    <header class="page-header">
        <h1>
            <i class="bi bi-list-task" style="color:var(--primary)"></i>
            @lang('lang.SystemLists')
            <span class="badge-count">{{ $sysLists->count() }}</span>
        </h1>
        <button class="pill-btn pill-btn-primary" data-bs-toggle="modal" data-bs-target="#addMainListModal">
            <i class="bi bi-plus-lg"></i> New List
        </button>
    </header>

    <div class="page-body">

        <!-- Flash Messages -->
        @if(session('status'))
            <div class="alert {{ session('stype') === 'success' ? 'alert-success' : 'alert-danger' }} flash alert-dismissible fade show"
                role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @foreach($sysLists as $list)
            @php $optCount = $list->options->count(); @endphp

            <!-- ═══ LEVEL 1 : sysLists Card ════════════════════════════════ -->
            <div class="sl-card">

                <!-- Card Header — editable sysLists name + titles -->
                <form action="{{ url('/updateSysList') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $list->id }}">
                    <div class="sl-card-header">
                        <div class="sl-icon"><i class="bi bi-folder2"></i></div>

                        <!-- Name (internal key) -->
                        <!-- <div style="flex:1;min-width:0">
                                            <small
                                                style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);display:block;margin-bottom:2px">Name
                                                (key)</small>

                                        </div> -->
                        <input type="hidden" name="name" class="sl-name-input" value="{{ $list->name }}"
                            placeholder="Internal key" readonly>

                        <!-- Title EN -->
                        <div style="flex:1;min-width:0">
                            <small
                                style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);display:block;margin-bottom:2px">English
                                Title</small>
                            <input type="text" name="title_en" class="sl-name-input" style="font-weight:500"
                                value="{{ $list->title_en }}" placeholder="English title...">
                        </div>

                        <!-- Title AR -->
                        <div style="flex:1;min-width:0">
                            <small
                                style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);display:block;margin-bottom:2px">
                                Arabic
                                Title</small>
                            <input type="text" name="title_ar" class="sl-name-input text-start" style="font-weight:500"
                                value="{{ $list->title_ar }}" placeholder="العنوان بالعربية..." dir="rtl">
                        </div>

                        <span class="meta-badge">{{ $optCount }} {{ Str::plural('item', $optCount) }}</span>
                        <button type="submit" class="icon-btn icon-btn-primary" title="Save">
                            <i class="bi bi-check2"></i>
                        </button>
                        <a href="{{ url('/deleteSysList/' . $list->id) }}" class="icon-btn icon-btn-danger"
                            onclick="return confirm('Delete this list and all its items?')" title="Delete list">
                            <i class="bi bi-trash3"></i>
                        </a>
                    </div>
                </form>

                <!-- ═══ LEVEL 2 : lists items table ══════════════════════════ -->
                <div class="table-responsive">
                    <table class="li-table">
                        <thead>
                            <tr>
                                <th style="width:52px">Img</th>
                                <th>Value</th>
                                <th>English</th>
                                <th>Arabic</th>
                                <th style="width:100px">Sub-lists</th>
                                <th style="width:110px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($list->options as $option)
                                @php $subCount = $option->subLists->count(); @endphp

                                <!-- Edit Row -->
                                <tr id="row-li-{{ $option->id }}">
                                    <td>
                                        <label class="img-preview-wrap" title="Click to change image"
                                            for="li-img-{{ $option->id }}" style="cursor:pointer">
                                            <img src="{{ $option->imgUrl }}" class="item-thumb" id="li-thumb-{{ $option->id }}"
                                                alt="{{ $option->en }}">
                                            <span class="img-preview-label"><i class="bi bi-camera"></i></span>
                                        </label>
                                    </td>
                                    <td>
                                        <form action="{{ url('/updateListItem') }}" method="POST" enctype="multipart/form-data"
                                            id="form-li-{{ $option->id }}" style="display:none">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $option->id }}">
                                            <input type="file" name="img" id="li-img-{{ $option->id }}" accept="image/*"
                                                style="display:none" onchange="previewImg(this,'li-thumb-{{ $option->id }}')">
                                        </form>
                                        <input type="text" name="value" form="form-li-{{ $option->id }}"
                                            class="cell-input fw-600" value="{{ $option->value }}" placeholder="Value">
                                    </td>
                                    <td>
                                        <input type="text" name="en" form="form-li-{{ $option->id }}" class="cell-input"
                                            value="{{ $option->en }}" placeholder="English">
                                    </td>
                                    <td>
                                        <input type="text" name="ar" form="form-li-{{ $option->id }}" class="cell-input"
                                            value="{{ $option->ar }}" placeholder="Arabic" dir="rtl">
                                    </td>
                                    <td>
                                        {{-- Toggle switch: instantly flips has_sub via GET --}}
                                        <div class="toggle-wrap">
                                            <span class="toggle-label">{{ $option->has_sub ? 'On' : 'Off' }}</span>
                                            <label class="ios-toggle" title="Toggle sub-lists on/off">
                                                <input type="checkbox" {{ $option->has_sub ? 'checked' : '' }}
                                                    onchange="window.location='{{ url('/toggleListSub/' . $option->id) }}'">
                                                <span class="track"></span>
                                            </label>
                                            @if($option->has_sub)
                                                <button type="button" class="expand-btn" id="expand-{{ $option->id }}"
                                                    onclick="toggleSub({{ $option->id }})">
                                                    <span class="arrow">▶</span>
                                                    <span>{{ $subCount }}</span>
                                                    <i class="bi bi-chevron-right" style="font-size:11px"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:4px;align-items:center">
                                            <button type="submit" form="form-li-{{ $option->id }}"
                                                class="icon-btn icon-btn-success" title="Save">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                            <a href="{{ url('/deleteListItem/' . $option->id) }}"
                                                class="icon-btn icon-btn-danger"
                                                onclick="return confirm('Delete this item and its sub-items?')" title="Delete">
                                                <i class="bi bi-x-circle"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                                {{-- ═══ LEVEL 3 : subLists panel — only when has_sub is ON ══════ --}}
                                @if($option->has_sub)
                                    <tr>
                                        <td colspan="6" style="padding:0;border:none">
                                            <div id="sub-panel-{{ $option->id }}" style="display:none;background:var(--surface-2)">
                                                <div style="padding:14px 16px 18px 60px">

                                                    <table class="sub-table">
                                                        <thead>
                                                            <tr>
                                                                <th style="width:46px">Img</th>
                                                                <th>Value</th>
                                                                <th>English</th>
                                                                <th>Arabic</th>
                                                                <th style="width:90px">Price</th>
                                                                <th style="width:90px">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($option->subLists as $sub)
                                                                <tr>
                                                                    <td>
                                                                        <label class="img-preview-wrap" for="sub-img-{{ $sub->id }}"
                                                                            style="cursor:pointer" title="Change image">
                                                                            <img src="{{ $sub->imgUrl }}" class="sub-thumb"
                                                                                id="sub-thumb-{{ $sub->id }}" alt="{{ $sub->en }}">
                                                                            <span class="img-preview-label" style="border-radius:6px">
                                                                                <i class="bi bi-camera" style="font-size:14px"></i>
                                                                            </span>
                                                                        </label>
                                                                    </td>
                                                                    <td>
                                                                        <form action="{{ url('/updateSubListItem') }}" method="POST"
                                                                            enctype="multipart/form-data" id="form-sub-{{ $sub->id }}"
                                                                            style="display:none">
                                                                            @csrf
                                                                            <input type="hidden" name="id" value="{{ $sub->id }}">
                                                                            <input type="file" name="img" id="sub-img-{{ $sub->id }}"
                                                                                accept="image/*" style="display:none"
                                                                                onchange="previewImg(this,'sub-thumb-{{ $sub->id }}')">
                                                                        </form>
                                                                        <input type="text" name="value" form="form-sub-{{ $sub->id }}"
                                                                            class="sub-cell-input fw-600" value="{{ $sub->value }}"
                                                                            placeholder="Value">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="en" form="form-sub-{{ $sub->id }}"
                                                                            class="sub-cell-input" value="{{ $sub->en }}"
                                                                            placeholder="English">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" name="ar" form="form-sub-{{ $sub->id }}"
                                                                            class="sub-cell-input" value="{{ $sub->ar }}"
                                                                            placeholder="Arabic" dir="rtl">
                                                                    </td>
                                                                    <td>
                                                                        <input type="number" name="price" form="form-sub-{{ $sub->id }}"
                                                                            class="sub-cell-input" value="{{ $sub->price }}"
                                                                            placeholder="0.00" step="0.01" min="0">
                                                                    </td>
                                                                    <td>
                                                                        <div style="display:flex;gap:3px">
                                                                            <button type="submit" form="form-sub-{{ $sub->id }}"
                                                                                class="icon-btn icon-btn-success" title="Save"
                                                                                style="padding:4px 7px">
                                                                                <i class="bi bi-check2"></i>
                                                                            </button>
                                                                            <a href="{{ url('/deleteSubListItem/' . $sub->id) }}"
                                                                                class="icon-btn icon-btn-danger"
                                                                                onclick="return confirm('Delete sub-item?')"
                                                                                style="padding:4px 7px" title="Delete">
                                                                                <i class="bi bi-x"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                            <!-- Add sub-list item row -->
                                                            <tr class="add-row">
                                                                <td>
                                                                    <label class="img-preview-wrap"
                                                                        for="new-sub-img-{{ $option->id }}" style="cursor:pointer"
                                                                        title="Add image">
                                                                        <img src="{{ asset('imgs/box_def.jpg') }}" class="sub-thumb"
                                                                            id="new-sub-thumb-{{ $option->id }}" alt="new">
                                                                        <span class="img-preview-label" style="border-radius:6px">
                                                                            <i class="bi bi-plus" style="font-size:16px"></i>
                                                                        </span>
                                                                    </label>
                                                                </td>
                                                                <td>
                                                                    <form action="{{ url('/storeSubListItem') }}" method="POST"
                                                                        enctype="multipart/form-data"
                                                                        id="form-new-sub-{{ $option->id }}">
                                                                        @csrf
                                                                        <input type="hidden" name="list_id"
                                                                            value="{{ $option->id }}">
                                                                        <input type="file" name="img"
                                                                            id="new-sub-img-{{ $option->id }}" accept="image/*"
                                                                            style="display:none"
                                                                            onchange="previewImg(this,'new-sub-thumb-{{ $option->id }}')">
                                                                        <input type="text" name="value"
                                                                            form="form-new-sub-{{ $option->id }}"
                                                                            class="sub-cell-input add-placeholder"
                                                                            placeholder="Value...">
                                                                    </form>
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="en"
                                                                        form="form-new-sub-{{ $option->id }}"
                                                                        class="sub-cell-input add-placeholder"
                                                                        placeholder="English...">
                                                                </td>
                                                                <td>
                                                                    <input type="text" name="ar"
                                                                        form="form-new-sub-{{ $option->id }}"
                                                                        class="sub-cell-input add-placeholder"
                                                                        placeholder="Arabic..." dir="rtl">
                                                                </td>
                                                                <td>
                                                                    <input type="number" name="price"
                                                                        form="form-new-sub-{{ $option->id }}"
                                                                        class="sub-cell-input add-placeholder" placeholder="0.00"
                                                                        step="0.01" min="0">
                                                                </td>
                                                                <td>
                                                                    <button type="submit" form="form-new-sub-{{ $option->id }}"
                                                                        class="icon-btn icon-btn-primary" title="Add sub-item"
                                                                        style="padding:4px 9px">
                                                                        <i class="bi bi-plus-circle"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </div><!-- /sub-panel-inner -->
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                            @endforeach

                            <!-- Add list-item row -->
                            <tr class="add-row">
                                <td>
                                    <label class="img-preview-wrap" for="new-li-img-{{ $list->id }}" style="cursor:pointer"
                                        title="Add image">
                                        <img src="{{ asset('imgs/box_def.jpg') }}" class="item-thumb"
                                            id="new-li-thumb-{{ $list->id }}" alt="new">
                                        <span class="img-preview-label"><i class="bi bi-plus"
                                                style="font-size:18px"></i></span>
                                    </label>
                                </td>
                                <td>
                                    <form action="{{ url('/storeListItem') }}" method="POST" enctype="multipart/form-data"
                                        id="form-new-li-{{ $list->id }}">
                                        @csrf
                                        <input type="hidden" name="lid" value="{{ $list->id }}">
                                        <input type="file" name="img" id="new-li-img-{{ $list->id }}" accept="image/*"
                                            style="display:none" onchange="previewImg(this,'new-li-thumb-{{ $list->id }}')">
                                        <input type="text" name="value" form="form-new-li-{{ $list->id }}"
                                            class="cell-input add-placeholder" placeholder="Value...">
                                    </form>
                                </td>
                                <td>
                                    <input type="text" name="en" form="form-new-li-{{ $list->id }}"
                                        class="cell-input add-placeholder" placeholder="English label...">
                                </td>
                                <td>
                                    <input type="text" name="ar" form="form-new-li-{{ $list->id }}"
                                        class="cell-input add-placeholder" placeholder="Arabic label..." dir="rtl">
                                </td>
                                <td></td>
                                <td>
                                    <button type="submit" form="form-new-li-{{ $list->id }}"
                                        class="icon-btn icon-btn-primary" title="Add item">
                                        <i class="bi bi-plus-circle"></i> Add
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div><!-- /table-responsive -->

            </div><!-- /sl-card -->
        @endforeach

        <!-- Add new sysLists card trigger -->
        <div class="add-sl-card" data-bs-toggle="modal" data-bs-target="#addMainListModal">
            <i class="bi bi-plus-circle" style="font-size:28px"></i>
            <span style="font-weight:600">Add New List</span>
        </div>

    </div><!-- /page-body -->

    <!-- ── Add SysList Modal ──────────────────────────────────────────────── -->
    <div class="modal fade" id="addMainListModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-700">
                        <i class="bi bi-folder-plus me-2" style="color:var(--primary)"></i>Create New List
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('/storeSysList') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <label class="form-label">Name <span style="color:var(--text-muted);font-weight:400">(internal
                                key)</span></label>
                        <input type="text" name="name" class="form-control mb-3" placeholder="e.g., shipping_methods"
                            required autofocus>

                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">English Title</label>
                                <input type="text" name="title_en" class="form-control"
                                    placeholder="e.g., Shipping Methods">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Arabic Title</label>
                                <input type="text" name="title_ar" class="form-control" placeholder="طرق الشحن"
                                    dir="rtl">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="pill-btn pill-btn-outline" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="pill-btn pill-btn-primary">
                            <i class="bi bi-plus-lg"></i> Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('scripts')

    <script>
        // Toggle sub-list panel open/closed
        function toggleSub(id) {
            const panel = document.getElementById('sub-panel-' + id);
            const btn = document.getElementById('expand-' + id);
            const open = panel.style.display === 'block';
            panel.style.display = open ? 'none' : 'block';
            btn.classList.toggle('open', !open);
        }

        // Live image preview before upload
        function previewImg(input, thumbId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById(thumbId).src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>