<header class="top-header">
  <nav class="navbar navbar-expand gap-3 align-items-center">
    <div class="mobile-toggle-icon fs-3">
      <i class="bi bi-list"></i>
    </div>
    <form class="searchbar d-none d-lg-flex">
      <div class="position-absolute top-50 translate-middle-y search-icon ms-3"><i class="bi bi-search"></i></div>
      <input class="form-control" type="text" placeholder="{{ __('messages.search') }}..."
        data-i18n-placeholder="search">
    </form>
    <div class="top-navbar-right ms-auto">
      <ul class="navbar-nav align-items-center top-header-nav">

        {{-- Branch Switcher --}}
        <li class="nav-item dropdown topbar-dropdown topbar-branch-switcher">
          <a class="nav-link dropdown-toggle dropdown-toggle-nocaret topbar-trigger position-relative"
            href="javascript:;" id="branchSwitcherDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="topbar-trigger-icon">
              <i class="bi bi-buildings"></i>
            </span>
            <span class="topbar-trigger-copy">
              <span class="topbar-trigger-label d-none d-md-inline" id="currentBranchLabel">
                @if ($currentBranch ?? null)
                  {{ $currentBranch->name }}
                @else
                  {{ __('messages.all_branches') }}
                @endif
              </span>
            </span>
            <i class="bi bi-chevron-down topbar-trigger-caret"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end topbar-menu" aria-labelledby="branchSwitcherDropdown">
            <h6 class="dropdown-header" data-i18n="switch_branch">@lang('messages.switch_branch')</h6>
            <a class="dropdown-item js-branch-switch {{ empty($currentBranchId) ? 'active' : '' }}" href="javascript:;"
              data-branch-id="0" data-i18n="all_branches">@lang('messages.all_branches')</a>
            <div class="dropdown-divider"></div>
            @foreach ($availableBranches ?? collect() as $branch)
              <a class="dropdown-item js-branch-switch {{ $currentBranchId == $branch->id ? 'active' : '' }}"
                href="javascript:;" data-branch-id="{{ $branch->id }}">
                <i class="bi bi-shop me-2"></i>{{ $branch->name }}
                @if ($branch->is_default)
                  <span class="badge bg-secondary ms-2" data-i18n="is_default">@lang('messages.is_default')</span>
                @endif
              </a>
            @endforeach
            @auth
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('admin.branches.index') }}" data-i18n="branches">
                <i class="bi bi-gear me-2"></i>@lang('messages.branches')
              </a>
            @endauth
          </div>
        </li>

        {{-- Language Switcher (AJAX, no refresh) --}}
        <li class="nav-item dropdown topbar-dropdown topbar-language-switcher">
          <a class="nav-link dropdown-toggle dropdown-toggle-nocaret topbar-trigger position-relative"
            href="javascript:;" id="langSwitcherDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <span class="topbar-trigger-icon">
              <i class="bi bi-translate"></i>
            </span>
            <span class="topbar-trigger-copy">
              <span class="topbar-trigger-label d-none d-md-inline"
                id="currentLocaleLabel">{{ strtoupper(app()->getLocale()) }}</span>
            </span>
            <i class="bi bi-chevron-down topbar-trigger-caret"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end topbar-menu" aria-labelledby="langSwitcherDropdown">
            <li><a class="dropdown-item js-lang-switch {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                href="javascript:;" data-locale="en"><span class="me-2">🇺🇸</span> English</a></li>
            <li><a class="dropdown-item js-lang-switch {{ app()->getLocale() === 'km' ? 'active' : '' }}"
                href="javascript:;" data-locale="km"><span class="me-2">🇰🇭</span> ខ្មែរ Khmer</a></li>
          </ul>
        </li>

        {{-- User --}}
        <li class="nav-item dropdown dropdown-user-setting topbar-dropdown">
          <a class="nav-link dropdown-toggle dropdown-toggle-nocaret topbar-trigger topbar-user-trigger"
            href="javascript:;" data-bs-toggle="dropdown">
            <div class="user-setting d-flex align-items-center">
              <span class="topbar-trigger-icon topbar-user-icon">
                <i class="bi bi-person-circle"></i>
              </span>
              <span class="topbar-trigger-copy">
                <span class="topbar-trigger-label d-none d-md-inline">{{ auth()->user()->name ?? 'Guest' }}</span>
              </span>
              <i class="bi bi-chevron-down topbar-trigger-caret"></i>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end topbar-menu">
            @auth
              <li><a class="dropdown-item" href="javascript:;" data-i18n="profile"><i
                    class="bi bi-person me-2"></i>@lang('messages.profile')</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                  @csrf
                  <button class="dropdown-item" type="submit" data-i18n="logout"><i
                      class="bi bi-box-arrow-right me-2"></i>@lang('messages.logout')</button>
                </form>
              </li>
            @else
              <li><a class="dropdown-item" href="{{ route('admin.login') }}" data-i18n="login"><i
                    class="bi bi-box-arrow-in-right me-2"></i>@lang('messages.login')</a></li>
            @endauth
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
