@component('admin.layouts.content', ['title'=>'داشبورد'])
    @slot('breadcrumb')
        <li class="breadcrumb-item active">داشبورد </li>
    @endslot
      
          <!-- Main content -->
          <section class="content">
            <div class="container-fluid">
              <!-- Small boxes (Stat box) -->
              <div class="row">
                <div class="col-lg-3 col-6">
                  <!-- small box -->
                  <div class="small-box bg-info">
                    <div class="inner">
                      <h3>150</h3>
      
                      <p>سفارشات جدید</p>
                    </div>
                    <div class="icon">
                        <i class="ri-briefcase-line"></i>
                    </div>
                    <a href="#" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                  </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                  <!-- small box -->
                  <div class="small-box bg-success">
                    <div class="inner">
                      <h3>53<sup style="font-size: 20px">%</sup></h3>
      
                      <p>افزایش امتیاز</p>
                    </div>
                    <div class="icon">
                        <i class="ri-line-chart-line"></i>
                    </div>
                    <a href="#" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                  </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                  <!-- small box -->
                  <div class="small-box bg-warning">
                    <div class="inner">
                      <h3>44</h3>
      
                      <p>کاربران ثبت شده</p>
                    </div>
                    <div class="icon">
                        <i class="ri-user-add-fill"></i>
                    </div>
                    <a href="#" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                  </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                  <!-- small box -->
                  <div class="small-box bg-danger">
                    <div class="inner">
                      <h3>65</h3>
      
                      <p>بازدید جدید</p>
                    </div>
                    <div class="icon">
                        <i class="ri-pie-chart-2-fill"></i>
                    </div>
                    <a href="#" class="small-box-footer">اطلاعات بیشتر <i class="fa fa-arrow-circle-left"></i></a>
                  </div>
                </div>
                <!-- ./col -->
              </div>
              <!-- /.row -->
              <!-- Main row -->
              <div class="row">
                <!-- Left col -->
                 
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
      
                
                <!-- right col -->
              </div>
              <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
          </section>
          <!-- /.content -->
@endcomponent