@component('admin.layouts.content', ['title'=>'ویژگی‌های اشتراک'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item active">ویژگی‌های اشتراک</li>
    @endslot

    <!-- Main content -->
    <section class="content">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">لیست ویژگی‌های اشتراک</h3>

                    <div class="card-tools d-flex">
                        <form
                            hx-get="{{ route('admin.subfeatures.index') }}"
                            hx-target="#table-section"
                            hx-swap="outerHTML"
                            hx-trigger="change, keyup from:input"
                            class="d-flex"
                        >
                            <button class="btn btn-light btn-sm" name="refresh" id="refresh" hx-get="{{ route('admin.subfeatures.index') }}" hx-trigger="click" hx-target="#table-section" hx-swap="outerHTML">
                                <i class="ri-reset-left-line"></i>
                            </button>
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="جستجو"
                                       autocomplete="off"
                                >

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="ri-search-line ri-lg"></i></button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover" lang="en">
                        @fragment('table-section')
                        <tbody id="table-section" style="font-family: 'Vazir', sans-serif !important;">
                        <tr>
                            <th>شناسه</th>
                            <th>نام</th>
                            <th>کلید</th>
                            <th>توضیحات</th>
                            <th>عملیات</th>
                        </tr>
                        @foreach ($subFeatures as $subFeature)
                            <tr id="row-{{ $subFeature->id }}">
                                <td>{{ $subFeature->id }}</td>
                                <td>{{ $subFeature->name }}</td>
                                <td>{{ $subFeature->key }}</td>
                                <td>{{ $subFeature->description }}</td>
                                <td>
                                    <a href="{{ route('admin.subfeatures.edit', $subFeature) }}">
                                        <button class="btn btn-light btn-sm text-primary">
                                            <i class="ri-edit-2-line ri-1x"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        @endfragment
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            صفحه <span class="font-weight-bold text-dark">{{ $subFeatures->currentPage() }} از {{ $subFeatures->lastPage() }}</span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ $subFeatures->previousPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $subFeatures->onFirstPage() ? 'disabled opacity-50' : '' }}">
                                <i class="ri-arrow-right-s-line"></i>
                                <span>صفحه قبل</span>
                            </a>

                            <a href="{{ $subFeatures->nextPageUrl() }}"
                               class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1 {{ $subFeatures->onLastPage() ? 'disabled opacity-50' : '' }}">
                                <span>صفحه بعد</span>
                                <i class="ri-arrow-left-s-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </section>
    <!-- /.content -->
@endcomponent
