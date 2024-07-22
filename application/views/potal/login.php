<?php 
$this->load->view('common/header.php');
?>
<div class="container">
<div class="login-section">
    <div>
    <h3 class="heading">Login</h3>
    <div>
        <span id="response"></span>
        <form class="login" id="login">
            <input type="hidden" name="baseurl" value=<?php echo base_url()?> />
            <input type="hidden" name=<?php echo $this->security->get_csrf_token_name();?> value=<?php echo $this->security->get_csrf_hash();?> />
    <div class="row">
        <div class="col-md-10">
            <div class="form-group">
                <label class="label-control">Name:<span class='formfield'>*</span></label>
                <input type="text" name="name" id="name" placeholder="Enter name" class="form-control" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10">
            <div class="form-group">
                <label class="label-control">Password:<span class='formfield'>*</span></label>
                <input type="text" name="password" id="password" placeholder="Enter password" class="form-control" />
            </div>
        </div>
    </div>
    <button class="btn btn-secondary loginbtn" type="submit">Submit</button>
</div>
</div>
<script src=<?php echo base_url('assets/js/login.js');?>></script>
<?php 
$this->load->view('common/footer.php');
?>