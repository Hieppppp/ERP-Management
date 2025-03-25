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
                        @if ($log['action'] == ActionLogEnum::CREATED)
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.inventory.messageLog.create') }}:
                            </h5>
                            <ul style="line-height: 1.7; padding-top: 10px;">
                                <li> {{ __('translation.inventory.messageLog.createMessage', [
                                    'shelve' => $log['productLocation']['shelve']['code'],
                                ]) }}:
                                    {{ $log['before_quantity'] }}
                                    <i class="fa fa-long-arrow-right"></i>
                                    {{ $log['after_quantity'] }}
                                </li>
                            </ul>
                        @elseif ($log['action'] == ActionLogEnum::UPDATED)
                            <h5 class="mb-0 font-size-create">
                                {{ __('translation.inventory.messageLog.update') }}:
                            </h5>
                            <ul style="line-height: 1.7; padding-top: 10px;">
                                <li> {{ __('translation.inventory.messageLog.createMessage', [
                                    'shelve' => $log['productLocation']['shelve']['code'],
                                ]) }}:
                                    {{ $log['before_quantity'] }}
                                    <i class="fa fa-long-arrow-right"></i>
                                    {{ $log['after_quantity'] }}
                                </li>
                                <li> {{ __('translation.inventory.note') }}:
                                    <br>
                                    {!! $log['note'] !!}
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
