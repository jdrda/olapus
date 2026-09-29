<div class="row g-4 mt-1">
    <div class="col-12 col-xl-7">
        <section class="profile-section">
            <div class="profile-section-header">
                <h4 class="h6 mb-0">
                    <i class="bi bi-building me-2"></i>{{ trans('admin_module_user.sections.company') }}
                </h4>
            </div>
            <div class="profile-section-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="company_name" class="form-label">{{ trans('admin_module_user.fields.company_name') }}</label>
                        <input type="text" name="company_name" id="company_name" class="form-control" maxlength="255"
                               value="{{ old('company_name', $results->company_name ?? '') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="contact_name" class="form-label">{{ trans('admin_module_user.fields.contact_name') }}</label>
                        <input type="text" name="contact_name" id="contact_name" class="form-control" maxlength="255"
                               value="{{ old('contact_name', $results->contact_name ?? '') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="contact_email" class="form-label">{{ trans('admin_module_user.fields.contact_email') }}</label>
                        <input type="email" name="contact_email" id="contact_email" class="form-control" maxlength="255"
                               value="{{ old('contact_email', $results->contact_email ?? '') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="contact_phone" class="form-label">{{ trans('admin_module_user.fields.contact_phone') }}</label>
                        <input type="tel" name="contact_phone" id="contact_phone" class="form-control" maxlength="32"
                               value="{{ old('contact_phone', $results->contact_phone ?? '') }}"
                               data-phone-input data-invalid-message="{{ trans('admin_module_user.invalid_phone') }}">
                        <div class="form-text">{{ trans('admin_module_user.help.phone') }}</div>
                    </div>
                    <div class="col-12">
                        <label for="website" class="form-label">{{ trans('admin_module_user.fields.website') }}</label>
                        <input type="url" name="website" id="website" class="form-control" maxlength="2048"
                               placeholder="https://"
                               value="{{ old('website', $results->website ?? '') }}">
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="col-12 col-xl-5">
        <section class="profile-section">
            <div class="profile-section-header">
                <h4 class="h6 mb-0">
                    <i class="bi bi-fingerprint me-2"></i>{{ trans('admin_module_user.sections.identifiers') }}
                </h4>
            </div>
            <div class="profile-section-body">
                <div class="row g-3">
                    <div class="col-12 col-sm-6">
                        <label for="company_id" class="form-label">{{ trans('admin_module_user.fields.company_id') }}</label>
                        <input type="text" name="company_id" id="company_id" class="form-control" maxlength="64"
                               value="{{ old('company_id', $results->company_id ?? '') }}">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="vat_id" class="form-label">{{ trans('admin_module_user.fields.vat_id') }}</label>
                        <input type="text" name="vat_id" id="vat_id" class="form-control" maxlength="64"
                               value="{{ old('vat_id', $results->vat_id ?? '') }}">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="customer_number" class="form-label">{{ trans('admin_module_user.fields.customer_number') }}</label>
                        <input type="text" name="customer_number" id="customer_number" class="form-control" maxlength="64"
                               value="{{ old('customer_number', $results->customer_number ?? '') }}">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="external_id" class="form-label">{{ trans('admin_module_user.fields.external_id') }}</label>
                        <input type="text" name="external_id" id="external_id" class="form-control" maxlength="128"
                               value="{{ old('external_id', $results->external_id ?? '') }}">
                    </div>
                    <div class="col-12">
                        <div class="form-text">{{ trans('admin_module_user.help.external_id') }}</div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="col-12 col-xl-6">
        <section class="profile-section">
            <div class="profile-section-header">
                <h4 class="h6 mb-0">
                    <i class="bi bi-receipt me-2"></i>{{ trans('admin_module_user.sections.billing') }}
                </h4>
            </div>
            <div class="profile-section-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="billing_name" class="form-label">{{ trans('admin_module_user.fields.billing_name') }}</label>
                        <input type="text" name="billing_name" id="billing_name" class="form-control" maxlength="255"
                               value="{{ old('billing_name', $results->billing_name ?? '') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="billing_contact_name" class="form-label">{{ trans('admin_module_user.fields.billing_contact_name') }}</label>
                        <input type="text" name="billing_contact_name" id="billing_contact_name" class="form-control" maxlength="255"
                               value="{{ old('billing_contact_name', $results->billing_contact_name ?? '') }}">
                    </div>
                    <div class="col-12 col-md-8">
                        <label for="billing_street" class="form-label">{{ trans('admin_module_user.fields.billing_street') }}</label>
                        <input type="text" name="billing_street" id="billing_street" class="form-control" maxlength="255"
                               value="{{ old('billing_street', $results->billing_street ?? '') }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="billing_house_number" class="form-label">{{ trans('admin_module_user.fields.billing_house_number') }}</label>
                        <input type="text" name="billing_house_number" id="billing_house_number" class="form-control" maxlength="32"
                               value="{{ old('billing_house_number', $results->billing_house_number ?? '') }}">
                    </div>
                    <div class="col-12 col-md-7">
                        <label for="billing_city" class="form-label">{{ trans('admin_module_user.fields.billing_city') }}</label>
                        <input type="text" name="billing_city" id="billing_city" class="form-control" maxlength="128"
                               value="{{ old('billing_city', $results->billing_city ?? '') }}">
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="billing_zip" class="form-label">{{ trans('admin_module_user.fields.billing_zip') }}</label>
                        <input type="text" name="billing_zip" id="billing_zip" class="form-control" maxlength="32"
                               value="{{ old('billing_zip', $results->billing_zip ?? '') }}">
                    </div>
                    <div class="col-12 col-md-7">
                        <label for="billing_state" class="form-label">{{ trans('admin_module_user.fields.billing_state') }}</label>
                        <input type="text" name="billing_state" id="billing_state" class="form-control" maxlength="128"
                               value="{{ old('billing_state', $results->billing_state ?? '') }}">
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="billing_country" class="form-label">{{ trans('admin_module_user.fields.billing_country') }}</label>
                        <select name="billing_country" id="billing_country" class="form-select" data-enhance="select"
                                data-country-select data-selected="{{ old('billing_country', $results->billing_country ?? '') }}">
                            <option value="">{{ trans('admin_module_user.select_country') }}</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="billing_email" class="form-label">{{ trans('admin_module_user.fields.billing_email') }}</label>
                        <input type="email" name="billing_email" id="billing_email" class="form-control" maxlength="255"
                               value="{{ old('billing_email', $results->billing_email ?? '') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="billing_phone" class="form-label">{{ trans('admin_module_user.fields.billing_phone') }}</label>
                        <input type="tel" name="billing_phone" id="billing_phone" class="form-control" maxlength="32"
                               value="{{ old('billing_phone', $results->billing_phone ?? '') }}"
                               data-phone-input data-invalid-message="{{ trans('admin_module_user.invalid_phone') }}">
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="col-12 col-xl-6">
        <section class="profile-section">
            <div class="profile-section-header d-flex align-items-center justify-content-between gap-2">
                <h4 class="h6 mb-0">
                    <i class="bi bi-truck me-2"></i>{{ trans('admin_module_user.sections.shipping') }}
                </h4>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-copy-billing-address>
                    <i class="bi bi-copy me-1"></i>{{ trans('admin_module_user.copy_billing') }}
                </button>
            </div>
            <div class="profile-section-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="shipping_name" class="form-label">{{ trans('admin_module_user.fields.shipping_name') }}</label>
                        <input type="text" name="shipping_name" id="shipping_name" class="form-control" maxlength="255"
                               value="{{ old('shipping_name', $results->shipping_name ?? '') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="shipping_contact_name" class="form-label">{{ trans('admin_module_user.fields.shipping_contact_name') }}</label>
                        <input type="text" name="shipping_contact_name" id="shipping_contact_name" class="form-control" maxlength="255"
                               value="{{ old('shipping_contact_name', $results->shipping_contact_name ?? '') }}">
                    </div>
                    <div class="col-12 col-md-8">
                        <label for="shipping_street" class="form-label">{{ trans('admin_module_user.fields.shipping_street') }}</label>
                        <input type="text" name="shipping_street" id="shipping_street" class="form-control" maxlength="255"
                               value="{{ old('shipping_street', $results->shipping_street ?? '') }}">
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="shipping_house_number" class="form-label">{{ trans('admin_module_user.fields.shipping_house_number') }}</label>
                        <input type="text" name="shipping_house_number" id="shipping_house_number" class="form-control" maxlength="32"
                               value="{{ old('shipping_house_number', $results->shipping_house_number ?? '') }}">
                    </div>
                    <div class="col-12 col-md-7">
                        <label for="shipping_city" class="form-label">{{ trans('admin_module_user.fields.shipping_city') }}</label>
                        <input type="text" name="shipping_city" id="shipping_city" class="form-control" maxlength="128"
                               value="{{ old('shipping_city', $results->shipping_city ?? '') }}">
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="shipping_zip" class="form-label">{{ trans('admin_module_user.fields.shipping_zip') }}</label>
                        <input type="text" name="shipping_zip" id="shipping_zip" class="form-control" maxlength="32"
                               value="{{ old('shipping_zip', $results->shipping_zip ?? '') }}">
                    </div>
                    <div class="col-12 col-md-7">
                        <label for="shipping_state" class="form-label">{{ trans('admin_module_user.fields.shipping_state') }}</label>
                        <input type="text" name="shipping_state" id="shipping_state" class="form-control" maxlength="128"
                               value="{{ old('shipping_state', $results->shipping_state ?? '') }}">
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="shipping_country" class="form-label">{{ trans('admin_module_user.fields.shipping_country') }}</label>
                        <select name="shipping_country" id="shipping_country" class="form-select" data-enhance="select"
                                data-country-select data-selected="{{ old('shipping_country', $results->shipping_country ?? '') }}">
                            <option value="">{{ trans('admin_module_user.select_country') }}</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="shipping_email" class="form-label">{{ trans('admin_module_user.fields.shipping_email') }}</label>
                        <input type="email" name="shipping_email" id="shipping_email" class="form-control" maxlength="255"
                               value="{{ old('shipping_email', $results->shipping_email ?? '') }}">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="shipping_phone" class="form-label">{{ trans('admin_module_user.fields.shipping_phone') }}</label>
                        <input type="tel" name="shipping_phone" id="shipping_phone" class="form-control" maxlength="32"
                               value="{{ old('shipping_phone', $results->shipping_phone ?? '') }}"
                               data-phone-input data-invalid-message="{{ trans('admin_module_user.invalid_phone') }}">
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="col-12 col-xl-5">
        <section class="profile-section">
            <div class="profile-section-header">
                <h4 class="h6 mb-0">
                    <i class="bi bi-sliders me-2"></i>{{ trans('admin_module_user.sections.preferences') }}
                </h4>
            </div>
            <div class="profile-section-body">
                <div class="row g-3">
                    <div class="col-12 col-md-5 col-xl-12">
                        <label for="locale" class="form-label">{{ trans('admin_module_user.fields.locale') }}</label>
                        <input type="text" name="locale" id="locale" class="form-control" maxlength="16"
                               placeholder="{{ app()->getLocale() }}"
                               value="{{ old('locale', $results->locale ?? '') }}">
                        <div class="form-text">{{ trans('admin_module_user.help.locale') }}</div>
                    </div>
                    <div class="col-12 col-md-7 col-xl-12">
                        <label for="timezone" class="form-label">{{ trans('admin_module_user.fields.timezone') }}</label>
                        <select name="timezone" id="timezone" class="form-select" data-enhance="select">
                            <option value="">{{ trans('admin_module_user.select_timezone') }}</option>
                            @foreach ($timezones ?? [] as $timezone)
                                <option value="{{ $timezone }}" @selected(old('timezone', $results->timezone ?? '') === $timezone)>{{ $timezone }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">{{ trans('admin_module_user.help.timezone') }}</div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="col-12 col-xl-7">
        <section class="profile-section">
            <div class="profile-section-header">
                <h4 class="h6 mb-0">
                    <i class="bi bi-journal-text me-2"></i>{{ trans('admin_module_user.sections.note') }}
                </h4>
            </div>
            <div class="profile-section-body">
                <label for="notice" class="form-label">{{ trans('admin_module_user.fields.notice') }}</label>
                <textarea name="notice" id="notice" class="form-control" rows="7" maxlength="10000">{{ old('notice', $results->notice ?? '') }}</textarea>
            </div>
        </section>
    </div>
</div>
