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
            {{ __('translation.supplier.logNote') }}
        </p>
    </nav>
</div>
<div class="tab-content">
    @foreach ($logs as $log)
    <div class="media br-5 pe-4 ps-4 pt-4 pb-0 overflow-visible">
        <div class="me-3">
            <a> <img class="media-object thumb-sm img-overlay-light-box" alt="64x64" src="{{ $log['causer']['profile_picture'] }}"> </a>
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
                        {{ __('translation.supplier.messageLog.supplierCreated') }}
                    </h5>
                    @elseif ($log['event'] == 'updated')
                    <h5 class="mb-0 font-size-create">
                        {{ __('translation.supplier.messageLog.supplierUpdated') }}
                    </h5>
                    <ul style="line-height: 1.7; padding-top: 10px;">
                        @php
                            $oldData = $log['properties']['old'];
                            $newData = $log['properties']['attributes'];
                        @endphp
                        @php
                            $fieldTranslations = [
                                'name' => __('translation.supplier.name'),
                                'email' => __('translation.supplier.email'),
                                'phone' => __('translation.supplier.phone'),
                                'country' => __('translation.supplier.country'),
                                'province' => __('translation.supplier.province'),
                                'city' => __('translation.supplier.city'),
                                'logo' => __('translation.supplier.messageLog.logoUpdated'),
                                'site' => __('translation.supplier.contactURL')
                            ];
                        @endphp
                        @foreach ($fieldTranslations as $field => $translation)
                            @if ($field === 'logo')
                                @if (array_key_exists('logo', $oldData) || array_key_exists('logo', $newData))
                                    <li>
                                        {{ $translation }}
                                    </li>
                                @endif
                            @else
                                @if (array_key_exists($field, $oldData) || array_key_exists($field, $newData))
                                    <li>
                                        {{ $translation }}: {{ $oldData[$field] }}
                                        <i class="fa fa-long-arrow-right"></i>
                                        {{ $newData[$field] }}
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>