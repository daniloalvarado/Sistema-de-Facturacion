<h1 class="text-white px-2 name"><?php echo $_settings->info('name') ?></h1>
<hr>
<section class="content">
    <div class="container-fluid">
    <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-invoice"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Facturas</span>
                <span class="info-box-number">
                  <?php echo number_format($conn->query("SELECT * FROM invoice_list")->num_rows) ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-th-list"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Categorías</span>
                <span class="info-box-number"> <?php echo number_format($conn->query("SELECT * FROM category_list")->num_rows) ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-box"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Productos</span>
                <span class="info-box-number"><?php echo number_format($conn->query("SELECT * FROM product_list")->num_rows) ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hands-helping"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Servicios</span>
                <span class="info-box-number"><?php echo number_format($conn->query("SELECT * FROM service_list")->num_rows) ?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
    </div>
</section>
