<?php /* Smarty version 2.6.6, created on 2014-01-05 18:27:51
         compiled from E:/xampp/htdocs/stf/app_contents/standard/user_home/dashboard.html */ ?>
<!-- HIGHCHARTS FILES-->
<script src="/ext/Highcharts-3.0.7/js/highcharts.js" type="text/javascript"></script>
<script src="/ext/Highcharts-3.0.7/js/modules/exporting.js"></script>
<script language="JavaScript" src="/app_contents/common/js/charts.js"></script>

<script>
    var countryArray         = [];
    var companyTotalArray    = [];
    var monthArray           = [];
    var monthlyTotalArray    = [];
    
    <?php if (count($_from = (array)$this->_tpl_vars['country_list'])):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?> 
        countryArray.push('<?php echo $this->_tpl_vars['key']; ?>
');
        companyTotalArray.push(<?php echo $this->_tpl_vars['item']; ?>
);
    <?php endforeach; unset($_from); endif; ?>
</script>
<!-- body-full start here-->
<div id="body-full"> 
    <div class="wrap">  <!--wrap class start here -->
        <div class="body-block">  <!-- body-block class starts here-->
            <div class="left">   <!-- left class starts here-->
                <form name="userHomeForm" action="<?php echo $this->_tpl_vars['SCRIPT_NAME']; ?>
" method="POST">
                <div class="row padding2 prepand"> <!-- statistics row start here-->
                    <div class="col-sm-3">
                        <div class="box_stat box_pos">
                            <h4><?php echo $this->_tpl_vars['total_applicant']; ?>
</h4>
                            <small>Total Applicant(s)</small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="box_stat box_pos">
                            <h4><?php echo $this->_tpl_vars['gender']->female; ?>
</h4>
                            <small>Total Female</small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="box_stat box_pos">
                            <h4><?php echo $this->_tpl_vars['gender']->male; ?>
</h4>
                            <small>Total Male</small>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="box_stat box_pos">
                            <h4><?php echo $this->_tpl_vars['total_space']; ?>
</h4>
                            <small>Total Payout</small>
                        </div>
                    </div>
                </div> <!-- summary row start here-->
                </form>                
                <h3><span>Gender Ratio</span></h3>
        	<div class="left-body">  <!-- left-body class starts here -->
                    <div id="container" style="width:100%; height: 40%;"></div>
                </div>
                
                
                <h3 class="prepand2"><span>Top 10 Countries</span></h3>
        	<div class="left-body">  <!-- left-body class starts here -->
                    <div id="top_customers" style="width:100%"></div>
                </div>
                
            </div>    <!-- left class ends here-->
        </div><!-- body-block class starts here-->
    </div>
</div>
<script> 
    showPieCharts(<?php echo $this->_tpl_vars['gender']->male; ?>
, <?php echo $this->_tpl_vars['gender']->female; ?>
);
    showTop10Countries();
</script>