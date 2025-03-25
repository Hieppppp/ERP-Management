<style>
    .tab-content ul {
        list-style-type: disc;
        padding-left: 2rem;
    }

    .font-size-create {
        font-size: 13.4px !important;
    }
</style>
<div class="tabs-menu4 d-flex justify-content-between align-items-end">
    <nav id="navId" class="nav d-sm-flex d-block">
        <p class="card-title">
            {{ __('translation.purchaseOrder.logNote') }}
        </p>
    </nav>
</div>
<div class="tab-content">
    @foreach ($logs as $log)
        <div class="media br-5 pe-4 ps-4 pt-4 pb-0 overflow-visible">
            <div class="me-3">
                <a> <img class="media-object thumb-sm img-overlay-light-box" alt="64x64"
                        src="{{ $log['user']['profile_picture'] }}"> </a>
            </div>
            <div class="media-body overflow-visible">
                <div class="br-5">
                    <h5 class="mt-0 font-size-create"><a style="color: var(--primary-bg-color);"
                            href="{{ url('user', $log['user']['id']) }}" target="_blank">
                            {{ $log['user']['first_name'] . ' ' . $log['user']['last_name'] }}</a>
                        <span></span>
                        <span class="text-muted fs-12 ms-1">-
                            {{ TimeHelper::timeSince($log['created_at']) }}
                        </span>
                    </h5>
                    <div>
                        @if ($log['event'] == ActionLogEnum::CREATED)
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.product.messageLog.productCreated') }}
                            </h5>
                        @elseif ($log['event'] == ActionLogEnum::UPDATED)
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.product.messageLog.productUpdated') }}
                            </h5>
                            @if ($log['change'])
                                <ul style="line-height: 1.7; padding-top: 10px;">
                                    @php
                                        $changeData = $log['change'];
                                        $oldValue = $log['properties']['old'];
                                        $newValue = $log['properties']['attributes'];
                                    @endphp
                                    @foreach ($changeData as $key => $value)
                                        @if ($key == 'name')
                                            <li>{{ __('translation.product.name') }}: {{ $value['old'] }}
                                                <i class="fa fa-long-arrow-right"></i>
                                                {{ $value['new'] }}
                                            </li>
                                        @endif
                                        @if ($key == 'unit_id')
                                            <li>{{ __('translation.product.unit') }}: {{ $oldValue['unit']['name'] }}
                                                <i class="fa fa-long-arrow-right"></i>
                                                {{ $newValue['unit']['name'] }}
                                            </li>
                                        @endif
                                        @if ($key == 'category_id')
                                            <li>{{ __('translation.product.category') }}:
                                                {{ $oldValue['category']['name'] }}
                                                <i class="fa fa-long-arrow-right"></i>
                                                {{ $newValue['category']['name'] }}
                                            </li>
                                        @endif
                                        @if ($key == 'unit_price')
                                            <li>{{ __('translation.product.unitPrice') }}: {{ $value['old'] }}
                                                <i class="fa fa-long-arrow-right"></i>
                                                {{ $value['new'] }}
                                            </li>
                                        @endif
                                        @if ($key == 'description')
                                            <li>{{ __('translation.product.descriptionUpdated') }}</li>
                                        @endif
                                        @if ($key == 'max_quantity')
                                            <li>{{ __('translation.product.maxQuantity') }}: {{ $value['old'] }}
                                                <i class="fa fa-long-arrow-right"></i>
                                                {{ $value['new'] }}
                                            </li>
                                        @endif
                                        @if ($key == 'min_quantity')
                                            <li>{{ __('translation.product.minQuantity') }}: {{ $value['old'] }}
                                                <i class="fa fa-long-arrow-right"></i>
                                                {{ $value['new'] }}
                                            </li>
                                        @endif
                                        @if ($key == 'parent_products')
                                            @foreach ($value['created'] as $createdItem)
                                                <li>{{ __('translation.product.parentProduct') }}:
                                                    {{ __('message.createProduct', ['product' => $createdItem['name']]) }}
                                                </li>
                                            @endforeach
                                            @foreach ($value['deleted'] as $deletedItem)
                                                <li>{{ __('translation.product.parentProduct') }}:
                                                    {{ __('message.deleteProduct', ['product' => $deletedItem['name']]) }}
                                                </li>
                                            @endforeach
                                        @endif
                                        @if ($key == 'suppliers')
                                            @foreach ($value['created'] as $createdItem)
                                                <li>{{ __('translation.product.supplier') }}:
                                                    {{ __('message.createProduct', ['product' => $createdItem['name']]) }}
                                                </li>
                                            @endforeach
                                            @foreach ($value['updated'] as $updatedItem)
                                                <li>{{ __('translation.product.supplier') }} -
                                                    {{ $updatedItem['old']['name'] }}:
                                                    {{ $updatedItem['old']['pivot']['unit_cost'] }}
                                                    <i class="fa fa-long-arrow-right"></i>
                                                    {{ $updatedItem['new']['pivot']['unit_cost'] }}
                                                </li>
                                            @endforeach
                                            @foreach ($value['deleted'] as $deletedItem)
                                                <li>{{ __('translation.product.supplier') }}:
                                                    {{ __('message.deleteProduct', ['product' => $deletedItem['name']]) }}
                                                </li>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>