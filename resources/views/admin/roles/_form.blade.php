@php
  $isEdit = $mode === 'edit';
  $selectedPermissionIds = collect(old('permissions', $selectedPermissionIds ?? []))
      ->map(fn($permissionId) => (int) $permissionId)
      ->all();
@endphp

<div class="card role-form-card border-0 shadow-sm overflow-hidden">
  <div class="card-header role-form-header">
    <div>
      <h4 class="mb-1">
        <span data-i18n="{{ $mode }}">{{ __('messages.' . $mode) }}</span>
        <span data-i18n="roles">{{ __('messages.roles') }}</span>
      </h4>
      <p class="mb-0 role-form-subtitle">Configure the role details and access permissions.</p>
    </div>
  </div>

  <div class="card-body p-4 p-xl-5">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ $isEdit ? route($route . '.update', $model->id) : route($route . '.store') }}"
      id="roleForm" class="role-form">
      @csrf
      @if ($isEdit)
        @method('PUT')
      @endif

      <div class="role-form-section mb-4">
        <label for="role_name" class="form-label required" data-i18n="name">{{ __('messages.name') }}</label>
        <input type="text" id="role_name" name="name" class="form-control @error('name') is-invalid @enderror"
          value="{{ old('name', $model->name) }}" required placeholder="cruds.customer.fields.title">
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="row g-3 role-secondary-fields mb-4">
        <div class="col-lg-6">
          <label for="role_display_name" class="form-label required"
            data-i18n="display_name">{{ __('messages.display_name') }}</label>
          <input type="text" id="role_display_name" name="display_name"
            class="form-control @error('display_name') is-invalid @enderror"
            value="{{ old('display_name', $model->display_name) }}" required placeholder="Super Admin">
          @error('display_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-lg-3">
          <label for="role_status" class="form-label required" data-i18n="status">{{ __('messages.status') }}</label>
          <select id="role_status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="active" {{ old('status', $model->status ?: 'active') === 'active' ? 'selected' : '' }}>
              Active</option>
            <option value="inactive" {{ old('status', $model->status ?: 'active') === 'inactive' ? 'selected' : '' }}>
              Inactive</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-lg-12">
          <label for="role_description" class="form-label"
            data-i18n="description">{{ __('messages.description') }}</label>
          <textarea id="role_description" name="description" class="form-control @error('description') is-invalid @enderror"
            rows="3" placeholder="Optional internal notes for this role">{{ old('description', $model->description) }}</textarea>
          @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="role-form-section">
        <label class="form-label required" data-i18n="permissions">{{ __('messages.permissions') }}</label>

        <div class="table-responsive">
          <table class="table role-permission-table align-middle mb-0">
            <thead>
              <tr>
                <th scope="col" class="role-permission-group-heading">Group</th>
                <th scope="col" class="role-permission-toggle-heading">
                  <div class="form-check d-flex justify-content-center m-0">
                    <input class="form-check-input js-role-master-toggle" type="checkbox" id="toggle_all_permissions">
                  </div>
                </th>
                <th scope="col">Access</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($permissionGroups as $groupKey => $group)
                @php
                  $groupPermissionIds = collect($group['permissions'])
                      ->pluck('id')
                      ->map(fn($permissionId) => (int) $permissionId)
                      ->all();
                  $checkedCount = count(array_intersect($groupPermissionIds, $selectedPermissionIds));
                  $isFullyChecked = $checkedCount > 0 && $checkedCount === count($groupPermissionIds);
                @endphp
                <tr>
                  <th scope="row" class="role-group-cell">
                    <div class="role-group-title">{{ $group['label'] }}</div>
                  </th>
                  <td class="role-group-toggle-cell">
                    <div class="form-check d-flex justify-content-center m-0">
                      <input class="form-check-input js-role-group-toggle" type="checkbox"
                        id="permission_group_{{ $loop->index }}" data-group="{{ $groupKey }}"
                        {{ $isFullyChecked ? 'checked' : '' }}>
                    </div>
                  </td>
                  <td>
                    <div class="role-permission-inline-list">
                      @foreach ($group['permissions'] as $permission)
                        @php
                          $permissionId = (int) $permission['id'];
                          $isChecked = in_array($permissionId, $selectedPermissionIds, true);
                        @endphp
                        <div class="form-check role-permission-inline-item">
                          <input class="form-check-input js-role-permission" type="checkbox" name="permissions[]"
                            value="{{ $permissionId }}" id="permission_{{ $permissionId }}"
                            data-group="{{ $groupKey }}" {{ $isChecked ? 'checked' : '' }}>
                          <label class="form-check-label" for="permission_{{ $permissionId }}"
                            title="{{ $permission['label'] }}">
                            {{ $permission['hint'] }}
                          </label>
                        </div>
                      @endforeach
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @error('permissions')
          <div class="text-danger small mt-3">{{ $message }}</div>
        @enderror
        @error('permissions.*')
          <div class="text-danger small mt-3">{{ $message }}</div>
        @enderror
      </div>

      <div class="d-flex flex-wrap gap-2 mt-4 role-form-actions">
        <button type="submit" class="btn btn-primary px-4">
          <i class="bi bi-check-lg"></i>
          <span data-i18n="save">{{ __('messages.save') }}</span>
        </button>
        <a href="{{ route($route . '.index') }}" class="btn btn-light px-4">
          <i class="bi bi-arrow-left"></i>
          <span data-i18n="cancel">{{ __('messages.cancel') }}</span>
        </a>
      </div>
    </form>
  </div>
</div>

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('roleForm');

      if (!form) {
        return;
      }

      const nameInput = document.getElementById('role_name');
      const displayNameInput = document.getElementById('role_display_name');
      const masterToggle = form.querySelector('.js-role-master-toggle');
      const permissionCheckboxes = Array.from(form.querySelectorAll('.js-role-permission'));
      const groupToggles = Array.from(form.querySelectorAll('.js-role-group-toggle'));

      const humanize = function(value) {
        return value
          .replace(/[._-]+/g, ' ')
          .replace(/\s+/g, ' ')
          .trim()
          .replace(/\b\w/g, function(char) {
            return char.toUpperCase();
          });
      };

      const syncGroupToggle = function(groupName) {
        const groupPermissions = permissionCheckboxes.filter(function(checkbox) {
          return checkbox.dataset.group === groupName;
        });
        const toggle = groupToggles.find(function(checkbox) {
          return checkbox.dataset.group === groupName;
        });

        if (!toggle || groupPermissions.length === 0) {
          return;
        }

        const checkedCount = groupPermissions.filter(function(checkbox) {
          return checkbox.checked;
        }).length;

        toggle.checked = checkedCount === groupPermissions.length;
      };

      const syncMasterToggle = function() {
        if (!masterToggle || permissionCheckboxes.length === 0) {
          return;
        }

        const checkedCount = permissionCheckboxes.filter(function(checkbox) {
          return checkbox.checked;
        }).length;

        masterToggle.checked = checkedCount === permissionCheckboxes.length;
      };

      if (displayNameInput) {
        displayNameInput.dataset.userEdited = displayNameInput.value.trim() !== '' ? 'true' : 'false';
        displayNameInput.addEventListener('input', function() {
          displayNameInput.dataset.userEdited = displayNameInput.value.trim() !== '' ? 'true' : 'false';
        });
      }

      if (nameInput && displayNameInput) {
        nameInput.addEventListener('input', function() {
          if (displayNameInput.dataset.userEdited === 'true') {
            return;
          }

          displayNameInput.value = humanize(nameInput.value);
        });
      }

      permissionCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
          syncGroupToggle(checkbox.dataset.group);
          syncMasterToggle();
        });
      });

      groupToggles.forEach(function(toggle) {
        toggle.addEventListener('change', function() {
          permissionCheckboxes.forEach(function(checkbox) {
            if (checkbox.dataset.group === toggle.dataset.group) {
              checkbox.checked = toggle.checked;
            }
          });

          syncMasterToggle();
        });

        syncGroupToggle(toggle.dataset.group);
      });

      if (masterToggle) {
        masterToggle.addEventListener('change', function() {
          permissionCheckboxes.forEach(function(checkbox) {
            checkbox.checked = masterToggle.checked;
          });

          groupToggles.forEach(function(toggle) {
            toggle.checked = masterToggle.checked;
          });
        });
      }

      syncMasterToggle();
    });
  </script>
@endpush
