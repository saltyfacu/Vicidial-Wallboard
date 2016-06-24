<?php require_once('Connections/wallboard.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

// Live Calls
mysql_select_db($database_wallboard, $wallboard);
$query_active = "SELECT Count(vicidial_auto_calls.`status`) FROM vicidial_auto_calls";
$active = mysql_query($query_active, $wallboard) or die(mysql_error());
$row_active = mysql_fetch_assoc($active);
$totalRows_active = mysql_num_rows($active);

// Calls in IVR OK
mysql_select_db($database_wallboard, $wallboard);
$query_ivr_calls = "SELECT Count(vicidial_auto_calls.`status`) FROM vicidial_auto_calls WHERE vicidial_auto_calls.`status` = 'LIVE'";
$ivr_calls = mysql_query($query_ivr_calls, $wallboard) or die(mysql_error());
$row_ivr_calls = mysql_fetch_assoc($ivr_calls);
$totalRows_ivr_calls = mysql_num_rows($ivr_calls);

// Calls Waiting OK
mysql_select_db($database_wallboard, $wallboard);
$query_waiting_call = "SELECT Count(vicidial_auto_calls.`status`) FROM vicidial_auto_calls WHERE vicidial_auto_calls.`status` = 'IVR'";
$waiting_call = mysql_query($query_waiting_call, $wallboard) or die(mysql_error());
$row_waiting_call = mysql_fetch_assoc($waiting_call);
$totalRows_waiting_call = mysql_num_rows($waiting_call);

// Calls Ringing
mysql_select_db($database_wallboard, $wallboard);
$query_calling = "SELECT Count(vicidial_auto_calls.call_type) FROM vicidial_auto_calls WHERE vicidial_auto_calls.stage = 'START'";
$calling = mysql_query($query_calling, $wallboard) or die(mysql_error());
$row_calling = mysql_fetch_assoc($calling);
$totalRows_calling = mysql_num_rows($calling);

// Agents on Call OK
mysql_select_db($database_wallboard, $wallboard);
$query_agent_in_call = "SELECT Count(vicidial_live_agents.`user`) FROM vicidial_live_agents WHERE vicidial_live_agents.`status` = 'INCALL'";
$agent_in_call = mysql_query($query_agent_in_call, $wallboard) or die(mysql_error());
$row_agent_in_call = mysql_fetch_assoc($agent_in_call);
$totalRows_agent_in_call = mysql_num_rows($agent_in_call);

// Agents Available OK
mysql_select_db($database_wallboard, $wallboard);
$query_agent_waiting = "SELECT Count(vicidial_live_agents.`user`) FROM vicidial_live_agents WHERE vicidial_live_agents.`status` = 'READY'";
$agent_waiting = mysql_query($query_agent_waiting, $wallboard) or die(mysql_error());
$row_agent_waiting = mysql_fetch_assoc($agent_waiting);
$totalRows_agent_waiting = mysql_num_rows($agent_waiting);

// Agents on Pause OK
mysql_select_db($database_wallboard, $wallboard);
$query_paused_agents = "SELECT Count(vicidial_live_agents.`user`) FROM vicidial_live_agents WHERE vicidial_live_agents.`status` = 'PAUSED'";
$paused_agents = mysql_query($query_paused_agents, $wallboard) or die(mysql_error());
$row_paused_agents = mysql_fetch_assoc($paused_agents);
$totalRows_paused_agents = mysql_num_rows($paused_agents);

// Inbound Total Calls
mysql_select_db($database_wallboard, $wallboard);
$query_total_inbound = "SELECT Count(vicidial_list.`status`) FROM `vicidial_list` WHERE vicidial_list.entry_date >= '".date("Y-m-d")."'";
$total_inbound = mysql_query($query_total_inbound, $wallboard) or die(mysql_error());
$row_total_inbound = mysql_fetch_assoc($total_inbound);
$totalRows_total_inbound = mysql_num_rows($total_inbound);

// Inbound Answered Calls
mysql_select_db($database_wallboard, $wallboard);
$query_answered_inbound = "SELECT Count(vicidial_list.`status`) FROM `vicidial_list` WHERE vicidial_list.entry_date >= '".date("Y-m-d")."' AND vicidial_list.`status` NOT LIKE 'DROP' AND vicidial_list.`status` NOT LIKE 'TIMEOT' AND vicidial_list.`status` NOT LIKE 'INBND'";
$answered_inbound = mysql_query($query_answered_inbound, $wallboard) or die(mysql_error());
$row_answered_inbound = mysql_fetch_assoc($answered_inbound);
$totalRows_answered_inbound = mysql_num_rows($answered_inbound);

// Inbound Drop Calls
mysql_select_db($database_wallboard, $wallboard);
$query_drop_inbound = "SELECT Count(vicidial_list.`status`) FROM `vicidial_list` WHERE vicidial_list.entry_date >= '".date("Y-m-d")."' AND (vicidial_list.`status` = 'DROP' OR vicidial_list.`status` = 'TIMEOT' OR vicidial_list.`status` = 'INBND') ";
$drop_inbound = mysql_query($query_drop_inbound, $wallboard) or die(mysql_error());
$row_drop_inbound = mysql_fetch_assoc($drop_inbound);
$totalRows_drop_inbound = mysql_num_rows($drop_inbound);

// Outbound Total Calls OK
mysql_select_db($database_wallboard, $wallboard);
$query_calls_today = "SELECT Count(vicidial_log.uniqueid) AS total_calls FROM `vicidial_log` WHERE `call_date` > '".date("Y-m-d")."'AND vicidial_log.`status` NOT LIKE 'CANCEL' AND vicidial_log.`status` NOT LIKE 'DOCCOM' AND vicidial_log.`status` NOT LIKE 'CALLBK' AND vicidial_log.`status` NOT LIKE 'WSD' AND vicidial_log.`status` NOT LIKE 'DCMX' AND vicidial_log.`status` NOT LIKE 'ADC'";
$calls_today = mysql_query($query_calls_today, $wallboard) or die(mysql_error());
$row_calls_today = mysql_fetch_assoc($calls_today);
$totalRows_calls_today = mysql_num_rows($calls_today);

// Outbound Answered Calls
mysql_select_db($database_wallboard, $wallboard);
$query_answered_calls = "SELECT Count(vicidial_log.`status`) FROM `vicidial_log` WHERE vicidial_log.call_date >= '".date("Y-m-d")."' AND `user` <> 'VDAD'";
$answered_calls = mysql_query($query_answered_calls, $wallboard) or die(mysql_error());
$row_answered_calls = mysql_fetch_assoc($answered_calls);
$totalRows_answered_calls = mysql_num_rows($answered_calls);

// Outbound Drop Calls Today
mysql_select_db($database_wallboard, $wallboard);
$query_drop_calls_today = "SELECT Count(vicidial_log.uniqueid) AS total_calls FROM `vicidial_log` WHERE `call_date` >= '".date("Y-m-d")."' AND `status` = 'DROP'";
$drop_calls_today = mysql_query($query_drop_calls_today, $wallboard) or die(mysql_error());
$row_drop_calls_today = mysql_fetch_assoc($drop_calls_today);
$totalRows_drop_calls_today = mysql_num_rows($drop_calls_today);

mysql_select_db($database_wallboard, $wallboard);
$query_dead_agent = "SELECT Count(vicidial_live_agents.`user`) as `dead agent` FROM vicidial_live_agents WHERE vicidial_live_agents.callerid IS NOT NULL AND vicidial_live_agents.last_update_time > vicidial_live_agents.last_call_finish";
$dead_agent = mysql_query($query_dead_agent, $wallboard) or die(mysql_error());
$row_dead_agent = mysql_fetch_assoc($dead_agent);
$totalRows_dead_agent = mysql_num_rows($dead_agent);
?>
<style>
  html, body{
	  overflow:hidden;  
  }
  .col-xs-15 {
    width: 20%;
    float: left;
	}
	@media (min-width: 768px) {
	.col-sm-15 {
			width: 20%;
			float: left;
		}
	}
	@media (min-width: 992px) {
		.col-md-15 {
			width: 20%;
			float: left;
		}
	}
	@media (min-width: 1200px) {
		.col-lg-15 {
			width: 20%;
			float: left;
		}
	}
  
  .row div{
	  height:230px;
	  border: solid 2px;
	  color: #FFF;
	  font-weight:bold;
	  text-align:center;
	  padding-top: 45px;
	  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.80);
  }
  
  .counter{
	  font-size:80px;
	  display:block;
	  margin-bottom: -15px;
  }
  
  .label{
	  font-size:28px;
	  font-weight:lighter;
  }
  
  .info{
	  background: #0499d1; /*00c2ed*/
	  /*background: linear-gradient(141deg, #0fb8ad 0%, #1fc8db 51%, #2cb5e8 75%);*/
  }
  
  .hold{
	  background: #ff6501;
	  /*background: linear-gradient(141deg, #B8800F 0%, #DB951F 51%, #E8A02C 75%);*/
	  
  }
  
  .drop{
	  background: #b50005;
	  /*background: linear-gradient(141deg, #B80F49 0%, #DB3F1F 51%, #D22525 75%);*/
	  
  }
  
  .dead{
	  background: #000000;
	  /*background: linear-gradient(141deg, #B80F49 0%, #DB3F1F 51%, #D22525 75%);*/
	  
  }
  
  .answer{
	  background: #019c10;
	  /*background: linear-gradient(141deg, #0FB876 0%, #1FDB81 51%, #2CE87B 75%);*/
	  
  }
  
  .glyphicon, .wi{
	  font-size:40px;
	  position: absolute;
	  right: 25px;
	  top: 15px;
	  opacity: 0.3;
  }
  
  </style>
	<div class="row">
      <div class="col-lg-3 hold">
        <span class="counter"><?php echo date("h:i"); ?></span>
        <span class="label"><?php echo date("m/d/Y"); ?></span>
      </div>
      <div class="col-lg-3 answer">
        <span class="counter"><?php echo $row_active['Count(vicidial_auto_calls.`status`)']; ?></span>
        <span class="label">Live Calls</span>
        <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
      </div>
      <div class="col-lg-3 info">
        <span class="counter"><?php echo $row_waiting_call['Count(vicidial_auto_calls.`status`)']; ?></span>
        <span class="label">Calls in IVR</span>              
        <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
      </div>
      <div class="col-lg-3 hold">
      	<span class="glyphicon glyphicon-time" aria-hidden="true"></span>
      	<span class="counter"><?php echo $row_ivr_calls['Count(vicidial_auto_calls.`status`)']; ?></span>
        <span class="label">Calls Waiting</span>
      </div>
      
    </div>
    
    <div class="row">
      <div class="col-lg-3 answer">
      	<span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_calling['Count(vicidial_auto_calls.call_type)']; ?></span>
        <span class="label">Calls Ringing</span>
      </div>
      <div class="col-lg-3 answer">
      	<span class="glyphicon glyphicon-headphones" aria-hidden="true"></span>
      	<span class="counter"><?php echo $row_agent_in_call['Count(vicidial_live_agents.`user`)']; ?></span>
        <span class="label">Agents on Call</span>
      </div>
      <div class="col-lg-3 answer">
      	<span class="glyphicon glyphicon-time" aria-hidden="true"></span>
      	<span class="counter"><?php echo $row_agent_waiting['Count(vicidial_live_agents.`user`)']; ?></span>
        <span class="label">Agents Available</span>
      </div>
      <div class="col-lg-3 hold">
      	<span class="glyphicon glyphicon-pause" aria-hidden="true"></span>
      	<span class="counter"><?php echo $row_paused_agents['Count(vicidial_live_agents.`user`)']; ?></span>
        <span class="label">Agents on Pause</span>
      </div>
      <!--<div class="col-lg-1 dead">
      	<span class="glyphicon glyphicon-pause" aria-hidden="true"></span>
      	<span class="counter"><?php echo $row_dead_agent['dead agent']; ?></span>
        <span class="label">Dead</span>
      </div>-->
    </div>
    
    <div class="row">
      <div class="col-lg-3 answer">
      	<span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
      	<span class="counter"><?php echo $row_total_inbound['Count(vicidial_list.`status`)']; ?></span>
        <span class="label">Inbound Calls</span>
      </div>
      <div class="col-lg-3 answer">
      	<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
      	<span class="counter"><?php echo $row_answered_inbound['Count(vicidial_list.`status`)']; ?></span>
        <span class="label">Inbound Answered Calls</span>
      </div>
      <div class="col-lg-3 drop">
      	<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
      	<span class="counter"><?php echo $row_drop_inbound['Count(vicidial_list.`status`)']; ?></span>
        <span class="label">Inbound Drop Calls</span>
      </div>
      <div class="col-lg-3 drop">
      	<span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
      	<span class="counter"><?php echo round(( $row_drop_inbound['Count(vicidial_list.`status`)']* 100)/$row_total_inbound['Count(vicidial_list.`status`)'], 2); ?>%</span>
        <span class="label">Drop Percent</span>
      </div>
    </div>
    
    <div class="row">
      <div class="col-lg-3 answer">
      	<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_calls_today['total_calls']; ?></span>
        <span class="label">Outbound Calls</span>
      </div>
      <div class="col-lg-3 answer">
      	<span class="glyphicon glyphicon-star" aria-hidden="true"></span>
        <span class="counter"><?php echo $row_answered_calls['Count(vicidial_log.`status`)']; ?></span>
        <span class="label">Outbound Answered Calls</span>
      </div>
      <div class="col-lg-3 drop">
      	<span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
      	<span class="counter"><?php echo $row_drop_calls_today['total_calls']; ?></span>
        <span class="label">Outbound Drop Calls</span>
      </div>
      <div class="col-lg-3 drop">
      	<span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
      	<span class="counter"><?php echo round(($row_drop_calls_today['total_calls'] * 100)/$row_answered_calls['Count(vicidial_log.`status`)'], 2); ?>%</span>
        <span class="label">Drop Percent</span>
      </div>
    </div>
    
    
 
    <?php
mysql_free_result($agent_waiting);

mysql_free_result($paused_agents);

mysql_free_result($agent_in_call);

mysql_free_result($ivr_calls);

mysql_free_result($waiting_call);

mysql_free_result($active);

mysql_free_result($calls_today);

mysql_free_result($drop_calls_today);

mysql_free_result($dead_agent);

mysql_free_result($answered_calls);

mysql_free_result($calling);

mysql_free_result($drop_inbound);

mysql_free_result($total_inbound);

mysql_free_result($answered_inbound);
?>
