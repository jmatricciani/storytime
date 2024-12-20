<?php $this->start('head')?>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.css">
  <script type="text/javascript" src="<?=PROOT?>js/moment.min.js?v=<?=VERSION?>"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<?php $this->end()?>
<?php $this->start('body')?>
<h2>Dashboard</h2><hr />

<div class="row">
  <div class="col-12">
    <div class="form-group col-2 offset-10">
      <select id="dateRangeSelector" class="form-control form-control-sm">
        <option value="last-0">Today</option>
        <option value="last-7">Last 7 Days</option>
        <option value="last-28" selected="selected">Last 28 Days</option>
        <option value="last-90">Last 90 Days</option>
        <option value="last-365">Last 365 Days</option>
      </select>
    </div>
  </div>
  <div class="col-12">
    <canvas id="dailySalesChart" width="400" height="80" class="chartjs"></canvas>
  </div>
</div>
<br>
<?php if($this->txs): ?>
  <h3 class="text-center pb-3">Orders Paid</h3>
  <table class="table table-bordered table-hover table-striped table-sm">
    <thead>
      <th class="col-2">Date</th>
      <th class="col-2">Name/Address</th>
      <th class="col-6">Items</th>
      <th class="col-1">Total</th>
      <th class="col-1">Sent?</th>
    </thead>
    <tbody>
      <?php foreach($this->txs as $tx):
        $address = $tx->shipping_address1.'<br>';
        if($tx->shipping_address2)
          $address .= $tx->shipping_address2.'<br>';
        $address .= $tx->shipping_city.', '.$tx->shipping_state.' '.$tx->shipping_zip;
        $items = '';
        $sent = 0;
        ?>

        <tr data-id="<?=$tx->id?>">
          <td><?=$tx->created_at ?></td>
          <td class="text-center"><?=$tx->name.'<br>'.$address ?></td>
          <td><?=$items?></td>
          <td>$ <?=$tx->amount ?></td>
          <td><?=($sent)? 'Yes' : 'No' ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<script>
  function loadDailySalesChart(){
    var range = jQuery('#dateRangeSelector').val();
    jQuery.ajax({
      url : '<?=PROOT?>admindashboard/getDailySales',
      method : "POST",
      data : {range:range},
      success : function(resp){ console.log(resp);
        var ctx = document.getElementById('dailySalesChart');
        var data = {
          labels: resp.labels,
          datasets: [
            {
              "label":"Daily Sales",
              "data" : resp.data,
              "fill":false,
              "borderColor":"rgb(75, 192, 192)",
              "lineTension":0.1
            }
          ]
        };
        var options = {};
        var myLineChart = new Chart(ctx, {
          type: 'line',
          data: data,
          options: options
        });
      }
    });
  }

  document.getElementById('dateRangeSelector').addEventListener("change",function(){
    loadDailySalesChart();
  });

  $('document').ready(function(){
    loadDailySalesChart();
  });
</script>
<?php $this->end(); ?>
