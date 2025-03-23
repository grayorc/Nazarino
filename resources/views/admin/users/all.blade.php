@component('admin.layouts.content', ['title'=>'کاربران'])
    @slot('breadcrumb')
        <li class="breadcrumb-item"><a href="/admin">داشبورد</a></li>
        <li class="breadcrumb-item active">کاربران </li>
    @endslot

          <!-- Main content -->
          <section class="content">
            <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">جدول ریسپانسیو</h3>

                    <div class="card-tools d-flex">
                      <form
                        hx-get="{{ route('users.index') }}"
                        hx-target="#table-section"
                        hx-swap="outerHTML"
                        hx-trigger="change, keyup from:input"
                        class="d-flex"
                      >
                      <button class="btn btn-light btn-sm" name="refresh" id="refresh" hx-get="{{ route('users.index') }}" hx-trigger="click" hx-target="#table-section" hx-swap="outerHTML">
                        <i class="ri-reset-left-line"></i>
                      </button>
                        <div class="input-group input-group-sm" style="width: 150px;">
                          <input type="text" name="search" class="form-control float-right" placeholder="جستجو"

                            autocomplete="off"
                          >

                          <div class="input-group-append">
                            <button type="submit" class="btn btn-default"><i class="ri-user-search-line ri-lg"></i></button>
                          </div>
                        </div>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="admin" id="admin"
                            > نمایش مدیران
                          </label>
                        </div>

                      </form>
                      <div class="btn-group-sm mr-1">
                      </div>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-0">
                    <table class="table table-hover" lang="en">
                    @fragment('table-section')
                    <tbody id="table-section" style="font-family: 'Vazir', sans-serif !important;">
                      <tr>
                          <th>شماره</th>
                          <th>نام</th>
                          <th>تاریخ عضویت</th>
                          <th>ایمیل</th>
                          <th>وضعیت ایمیل</th>
                          <th>عملیات</th>
                      </tr>
                      @foreach ($users as $user)
                          <tr id="row-{{ $user->id }}">
                              <td>{{ $user->id }}</td>
                              <td>{{ $user->name }}</td>
                              <td>{{ verta($user->created_at)->format('Y/m/d') }}</td>
                              <td>{{ $user->email }}</td>
                              <td>
                                  @if(is_null($user->email_verified_at))
                                      <span class="badge badge-danger">تایید نشده</span>
                                  @else
                                      <span class="badge badge-success">تایید شده</span>
                                  @endif
                              </td>
                              <td>
                                  <button class="btn btn-light btn-sm text-danger"
                                          hx-delete="{{ route('users.destroy', $user->id) }}"
                                          hx-trigger="click"
                                          hx-target="#row-{{ $user->id }}"
                                          hx-swap="outerHTML"
                                          hx-confirm="آیا مطمئن هستید که می‌خواهید این کاربر را حذف کنید؟"
                                          hx-headers='{"X-CSRF-TOKEN": "{{ csrf_token() }}"}'
                                          ><i class="ri-delete-bin-2-line ri-fw"></i></button>
                                  <button class="btn btn-light btn-sm text-primary">
                                      <i class="ri-edit-2-line ri-1x"></i>
                                  </button>
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
                    @endfragment
                  </table>
                  </div>
                  <!-- /.card-body -->
                  <div id="pagination-section">
                    {{ $users->appends(request()->input())->links() }}
                  </div>
                  {{-- <div class="card-footer">
                    {{ $users->render() }}
                  </div> --}}
                </div>
                <!-- /.card -->
              </div>
          </section>
          <!-- /.content -->
          <script>
            document.getElementById("admin").checked = false;
          </script>
@endcomponent
