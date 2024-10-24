

<div class="container-fluid">
  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3 class="Trafico"></h3>
          <p>Trafico diario cadena</p>
        </div>
        <div class="icon">
          <i class="fas fa-traffic-light"></i>
        </div>
        <a href="#" id="trafico" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>53<sup style="font-size: 20px">%</sup></h3>

          <p>Conversion Cadena</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>44</h3>

          <p>Meta Cadena</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>65</h3>

          <p>Venta Cadena</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>
  <!-- /.row -->
  <!-- Main row -->
  <div class="row">


    <!-- Left col -->
    <section class="col-lg-6">

      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-chart-pie mr-1"></i>
              Comparativo trimestral cadena <?php echo date('Y') ?>
          </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 250px;">
              <canvas id="popChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>

      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-chart-pie mr-1"></i>
            Rds Region Semana: <?php echo date('W') ?>
          </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="tab-content p-0">
            <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 250px;">
              <canvas id="popChart1" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
      </div>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="ion ion-clipboard mr-1"></i>
            To Do List
          </h3>

          <div class="card-tools">
            <ul class="pagination pagination-sm">
              <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
              <li class="page-item"><a href="#" class="page-link">1</a></li>
              <li class="page-item"><a href="#" class="page-link">2</a></li>
              <li class="page-item"><a href="#" class="page-link">3</a></li>
              <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
            </ul>
          </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <ul class="todo-list" data-widget="todo-list">
            <li>
              <!-- drag handle -->
              <span class="handle">
                <i class="fas fa-ellipsis-v"></i>
                <i class="fas fa-ellipsis-v"></i>
              </span>
              <!-- checkbox -->
              <div class="icheck-primary d-inline ml-2">
                <input type="checkbox" value="" name="todo1" id="todoCheck1">
                <label for="todoCheck1"></label>
              </div>
              <!-- todo text -->
              <span class="text">Design a nice theme</span>
              <!-- Emphasis label -->
              <small class="badge badge-danger"><i class="far fa-clock"></i> 2 mins</small>
              <!-- General tools such as edit or delete-->
              <div class="tools">
                <i class="fas fa-edit"></i>
                <i class="fas fa-trash-o"></i>
              </div>
            </li>
            <li>
              <span class="handle">
                <i class="fas fa-ellipsis-v"></i>
                <i class="fas fa-ellipsis-v"></i>
              </span>
              <div class="icheck-primary d-inline ml-2">
                <input type="checkbox" value="" name="todo2" id="todoCheck2" checked>
                <label for="todoCheck2"></label>
              </div>
              <span class="text">Make the theme responsive</span>
              <small class="badge badge-info"><i class="far fa-clock"></i> 4 hours</small>
              <div class="tools">
                <i class="fas fa-edit"></i>
                <i class="fas fa-trash-o"></i>
              </div>
            </li>
            <li>
              <span class="handle">
                <i class="fas fa-ellipsis-v"></i>
                <i class="fas fa-ellipsis-v"></i>
              </span>
              <div class="icheck-primary d-inline ml-2">
                <input type="checkbox" value="" name="todo3" id="todoCheck3">
                <label for="todoCheck3"></label>
              </div>
              <span class="text">Let theme shine like a star</span>
              <small class="badge badge-warning"><i class="far fa-clock"></i> 1 day</small>
              <div class="tools">
                <i class="fas fa-edit"></i>
                <i class="fas fa-trash-o"></i>
              </div>
            </li>
            <li>
              <span class="handle">
                <i class="fas fa-ellipsis-v"></i>
                <i class="fas fa-ellipsis-v"></i>
              </span>
              <div class="icheck-primary d-inline ml-2">
                <input type="checkbox" value="" name="todo4" id="todoCheck4">
                <label for="todoCheck4"></label>
              </div>
              <span class="text">Let theme shine like a star</span>
              <small class="badge badge-success"><i class="far fa-clock"></i> 3 days</small>
              <div class="tools">
                <i class="fas fa-edit"></i>
                <i class="fas fa-trash-o"></i>
              </div>
            </li>
            <li>
              <span class="handle">
                <i class="fas fa-ellipsis-v"></i>
                <i class="fas fa-ellipsis-v"></i>
              </span>
              <div class="icheck-primary d-inline ml-2">
                <input type="checkbox" value="" name="todo5" id="todoCheck5">
                <label for="todoCheck5"></label>
              </div>
              <span class="text">Check your messages and notifications</span>
              <small class="badge badge-primary"><i class="far fa-clock"></i> 1 week</small>
              <div class="tools">
                <i class="fas fa-edit"></i>
                <i class="fas fa-trash-o"></i>
              </div>
            </li>
            <li>
              <span class="handle">
                <i class="fas fa-ellipsis-v"></i>
                <i class="fas fa-ellipsis-v"></i>
              </span>
              <div class="icheck-primary d-inline ml-2">
                <input type="checkbox" value="" name="todo6" id="todoCheck6">
                <label for="todoCheck6"></label>
              </div>
              <span class="text">Let theme shine like a star</span>
              <small class="badge badge-secondary"><i class="far fa-clock"></i> 1 month</small>
              <div class="tools">
                <i class="fas fa-edit"></i>
                <i class="fas fa-trash-o"></i>
              </div>
            </li>
          </ul>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          <button type="button" class="btn btn-info float-right"><i class="fas fa-plus"></i> Add item</button>
        </div>
      </div>

    </section>

    <!-- Right col -->
    <section class="col-lg-6">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">Regiones <?php echo date('Y') ?> </h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
        <!-- /.card-body -->
      </div>

      <div class="card card-danger">
        <div class="card-header">
          <h3 class="card-title"><?php echo "Top 5 venta semana ". date('W'). " por proveedor ". date('Y') ?></h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
          <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
        <!-- /.card-body -->
      </div>



    </section>

  </div>
  <!-- /.row (main row) -->
</div>


<div class="modal fade" id="modalTablero" tabindex="-1" aria-labelledby="modalDepositoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-foot">
      </div>
    </div>
  </div>
</div>

<script>
  var url = "../Js/tablero/dashboard.js";
  $.getScript(url);
</script>