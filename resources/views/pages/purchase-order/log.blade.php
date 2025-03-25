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
                            href="{{ url('/user/' . $log['user']['id']) }}"
                            target="_blank">{{ $log['user']['first_name'] . ' ' . $log['user']['last_name'] }}</a>
                        <span></span>
                        <span class="text-muted fs-12 ms-1">-
                            {{ TimeHelper::timeSince($log['created_at']) }}
                        </span>
                    </h5>
                    <div>
                        @if ($log['event'] == ActionLogEnum::CREATED)
                            <h5 class="mb-0 font-size-create">
                                @if ($log['description'] == ActionLogEnum::CREATED_MANUALLY) 
                                {{ __('translation.purchaseOrder.messageLog.purchaseOrderCreatedManually') }}
                                @else
                                {{ __('translation.purchaseOrder.messageLog.purchaseOrderCreated') }}
                                @endif
                            </h5>
                        @elseif ($log['event'] == ActionLogEnum::UPDATED)
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.purchaseOrder.messageLog.purchaseOrderUpdated') }}
                            </h5>
                            <ul style="line-height: 1.7; padding-top: 10px;">
                                @if ($log['properties']['old']['supplier_id'] != $log['properties']['attributes']['supplier_id'])
                                    <li>{{ $log['properties']['old']['supplier']['name'] }} <i
                                            class="fa fa-long-arrow-right"> </i>
                                        {{ $log['properties']['attributes']['supplier']['name'] }}</li>
                                @endif
                                @if ($log['properties']['old']['warehouse_id'] != $log['properties']['attributes']['warehouse_id'])
                                    <li>{{ implode(', ', [
                                        $log['properties']['old']['warehouse']['detail_address'],
                                        $log['properties']['old']['warehouse']['city'],
                                        $log['properties']['old']['warehouse']['province'],
                                        $log['properties']['old']['warehouse']['country'],
                                    ]) }}
                                        ->
                                        {{ implode(', ', [
                                            $log['properties']['attributes']['warehouse']['detail_address'],
                                            $log['properties']['attributes']['warehouse']['city'],
                                            $log['properties']['attributes']['warehouse']['province'],
                                            $log['properties']['attributes']['warehouse']['country'],
                                        ]) }}
                                    </li>
                                @endif
                                @if (date('Y-m-d', strtotime($log['properties']['old']['scheduled_date'])) !=
                                        date('Y-m-d', strtotime($log['properties']['attributes']['scheduled_date'])))
                                    <li>{{ date('Y-m-d', strtotime($log['properties']['old']['scheduled_date'])) }} ->
                                        {{ date('Y-m-d', strtotime($log['properties']['attributes']['scheduled_date'])) }}
                                    </li>
                                @endif
                                @if (!empty($log['productDifference']['create']))
                                    @foreach ($log['productDifference']['create'] as $product)
                                        <li>{{ __('message.createProduct', ['product' => $product['name']]) }}</li>
                                    @endforeach
                                @endif
                                @if (!empty($log['productDifference']['delete']))
                                    @foreach ($log['productDifference']['delete'] as $product)
                                        <li>{{ __('message.deleteProduct', ['product' => $product['name']]) }}</li>
                                    @endforeach
                                @endif
                                @if (!empty($log['productDifference']['update']))
                                    @foreach ($log['productDifference']['update'] as $product)
                                        <li>{{ $product['name'] . ' : ' . number_format($product['old_quantity'], 2) }}
                                            <i class="fa fa-long-arrow-right"> </i>
                                            {{ number_format($product['new_quantity'], 2) }}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        @elseif ($log['event'] == ActionLogEnum::SENDED)
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.purchaseOrder.messageLog.purchaseOrderSent') }}</h5>
                            <ul style="line-height: 1.7; padding-top: 10px;">
                                <li>{{ __('translation.purchaseOrder.draft') }}
                                    <i class="fa fa-long-arrow-right"> </i>
                                    {{ __('translation.purchaseOrder.pending') }}
                                </li>
                            </ul>
                        @elseif ($log['event'] == ActionLogEnum::RECEIVED)
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.purchaseOrder.messageLog.productReceived') }}</h5>
                            <ul style="line-height: 1.7; padding-top: 10px;">
                                <li>{{ __('translation.purchaseOrder.pending') }}
                                    <i class="fa fa-long-arrow-right"> </i>
                                    {{ __('translation.purchaseOrder.shelvePending') }}
                                </li>
                                @if (array_key_exists('batchCode', $log['properties']))
                                    <li>{{ $log['properties']['batchCode'] }}
                                        {{ __('translation.actionLog.created') }}
                                    </li>
                                @endif
                            </ul>
                        @elseif ($log['event'] == ActionLogEnum::CANCELED)
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.purchaseOrder.messageLog.purchaseOrderCancelled') }}</h5>
                            <ul style="line-height: 1.7; padding-top: 10px;">
                                <li>{{ __('translation.purchaseOrder.pending') }}
                                    <i class="fa fa-long-arrow-right"> </i>
                                    {{ __('translation.purchaseOrder.cancel') }}
                                </li>
                            </ul>
                        @elseif ($log['event'] == ActionLogEnum::DONE)
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.purchaseOrder.messageLog.putProduct') }}</h5>
                            <ul style="line-height: 1.7; padding-top: 10px;">
                                <li>{{ __('translation.purchaseOrder.shelvePending') }}
                                    <i class="fa fa-long-arrow-right"> </i>
                                    {{ __('translation.purchaseOrder.done') }}
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
