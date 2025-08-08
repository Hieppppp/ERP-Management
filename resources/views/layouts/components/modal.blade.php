<div class="modal fade" id="country-selector">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content country-select-modal">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fa fa-comments-o fa-2"></i> {{ __('translation.chooseLanguage') }}</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <ul class="row row-sm p-3">
                    <li class="col-lg-4 mb-2">
                        <a class="btn btn-country btn-lg btn-block <?= app()->getLocale() == 'en' ? 'active' : '' ?>"
                            href="<?= route('lang', 'en') ?>">
                            <span class="country-selector">{{ __('translation.en') }} </span>
                        </a>
                    </li>
                    <li class="col-lg-4 mb-2">
                        <a class="btn btn-country btn-lg btn-block <?= app()->getLocale() == 'fr' ? 'active' : '' ?>"
                            href="<?= route('lang', 'fr') ?>">
                            <span class="country-selector">{{ __('translation.fr') }}</span>
                        </a>
                    </li>
                    <li class="col-lg-4 mb-2">
                        <a class="btn btn-country btn-lg btn-block <?= app()->getLocale() == 'vi' ? 'active' : '' ?>"
                            href="<?= route('lang', 'vi') ?>">
                            <span class="country-selector">{{ __('translation.vi') }}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="about-modal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content country-select-modal">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fa fa-question-circle-o fa-2"></i> About <sup>Xem</sup>/CRM</h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row row-sm p-3">
                    <p>About the <sup>Xem</sup>/CRM</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exchange-selector">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content country-select-modal">
            <div class="modal-header">
                <h6 class="modal-title"><i class="fa fa-money fa-2"></i> Exchange Rate <sup>Xem</sup>/CRM</h6><button
                    aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row row-sm p-3">
                    <p>Exchange rate tool here</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="smallModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content country-select-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="smallModal-header"></h5>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="smallModal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="largeModal">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content country-select-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="largeModal-header"></h5>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="largeModal-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="largeModal_2">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content country-select-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="largeModal_2-header"></h5>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="largeModal_2-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade delete-modal" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('translation.modal.confirmDelete') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('message.confirmDelete') }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="deleteModalYes">{{ __('translation.yes') }}</button>
                <button class="btn btn-info" data-bs-dismiss="modal">{{ __('translation.no') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="smallModal_v2">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content country-select-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="smallModal_v2-header"></h5>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="smallModal_v2-body">
            </div>
        </div>
    </div>
</div>

<div class="modal fade delete-modal" id="modalConfirmBack">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('translation.modal.confirmBack') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('message.confirmBack') }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">{{ __('translation.no') }}</button>
                <a class="btn btn-primary " id="redirectUrl">{{ __('translation.yes') }}</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImage" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-centered d-flex justify-content-center" role="document">
        <div class="modal-content align-items-end modal-content-light-box">
            <button class="btn-close btn-close-light-box" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="color:white;">×</span>
            </button>
            <img src="/assets/images/no-image.png" class="img-light-box" id="img-light-box">
        </div>
    </div>
</div>
<div class="modal fade delete-modal" id="modalConfirm">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModal-title">{{ __('translation.modal.confirmBack') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="confirmModal-body">
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-bs-dismiss="modal">{{ __('translation.no') }}</button>
                <button class="btn btn-primary" id="submitConfirm">{{ __('translation.yes') }}</button>
            </div>
        </div>
    </div>
</div>
