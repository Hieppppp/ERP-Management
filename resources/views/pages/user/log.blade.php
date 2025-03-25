<style>
    .tab-content ul {
        list-style-type: disc;
        padding-left: 2rem;
    }

    .tab-content-links {
        color: var(--primary-bg-color);
    }

    .font-size-create {
        font-size: 13.4px !important;
    }
</style>
<div class="tabs-menu4 d-flex justify-content-between align-items-end">
    <nav id="navId" class="nav d-sm-flex d-block">
        <p class="card-title">
            {{ __('translation.user.logNote') }}
        </p>
    </nav>
</div>
<div class="tab-content">
    @foreach ($logs as $log)
        <div class="media br-5 pe-4 ps-4 pt-4 pb-0 overflow-visible">
            <div class="me-3">
                <a> <img class="media-object thumb-sm img-overlay-light-box" alt="64x64"
                        src="{{ $log['causer']['profile_picture'] }}"> </a>
            </div>
            <div class="media-body overflow-visible">
                <div class="br-5">
                    <h5 class="mt-0 font-size-create">
                        <a class="tab-content-links" href="{{ url('user', $log['causer']['id']) }}" target="_blank">
                            {{ $log['causer']['first_name'] . ' ' . $log['causer']['last_name'] }}
                        </a>
                        <span class="text-muted fs-12 ms-1">-
                            {{ TimeHelper::timeSince($log['created_at']) }}
                        </span>
                    </h5>
                    <div>
                        @if ($log['event'] == 'created')
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.user.messageLog.userCreated') }}
                            </h5>
                        @elseif ($log['event'] == 'updated')
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.user.messageLog.userUpdated') }}
                            </h5>
                            <ul style="line-height: 1.7; padding-top: 10px;">
                                @php
                                    $oldData = $log['properties']['old'] ?? [];
                                    $newData = $log['properties']['attributes'] ?? [];
                                @endphp
                                @php
                                    $fieldTranslations = [
                                        'username' => __('translation.user.userName'),
                                        'last_name' => __('translation.user.lastName'),
                                        'first_name' => __('translation.user.firstName'),
                                        'email' => __('translation.user.email'),
                                        'role' => __('translation.user.role'),
                                    ];
                                @endphp
                                @foreach ($fieldTranslations as $field => $translation)
                                    @if (array_key_exists($field, $oldData) || array_key_exists($field, $newData))
                                        <li>
                                            {{ $translation }}:
                                            {{ $oldData[$field] }}
                                            <i class="fa fa-long-arrow-right"></i>
                                            {{ $newData[$field] }}
                                        </li>
                                    @endif
                                @endforeach
                                @if (!empty($oldData['permission_ids']) || !empty($newData['permission_ids']))
                                    <li>
                                        {{ __('translation.user.messageLog.rolePermissionUpdated') }}
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
