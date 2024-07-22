<?php 
$this->load->view('common/header');
?>
<div class='fluid-container dashboard'>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Welocme <?php echo $this->session->username?></a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="<?php echo base_url('dashcntl')?>">Home</a></li>
      <li><a href="<?php echo base_url('dashcntl/teachers')?>">Teachers</a></li>
      <li><a href="<?php echo base_url('dashcntl/students')?>">Students</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
      <li><a href="<?php echo base_url().'logincntl/disconnect'?>"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
  </div>
</nav>
<div class='dashcontent'>
    <?php 
    switch($page) {
        case 'teachers':
           $this->load->view('portal/pages/teachers');
            break;
        case 'students':
          $this->load->view('portal/pages/students');
          break;
        default:
            $this->load->view('portal/pages/home');
    }
    ?>
</div>
<?php
$this->load->view('common/footer');
?>