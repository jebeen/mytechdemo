<div class=''>
<input type='hidden' name='baseurl' value='<?php echo base_url();?>' />
<table id='user-table'>
    <thead>
        <th>Slno</th>
        <th>Name</th>
        <th>Password</th>
        <th>Status</th>
    </thead>
    <tbody>
        <?php if($add) { ?>
            <button class='btn btn-primary' data-toggle='modal' data-target='#addteacher'>+ Add</button>
        <?php } ?>

    <?php 
    $slno = 1;
    foreach($teachers as $teacher) {
         
        ?>
        <tr>
            <td><?php echo $slno++;?></td>
            <td><?php echo $teacher->username;?></td>
            <td><?php echo decrypt_password($teacher->password);?></td>
            <td><?php echo getUserStatus($teacher->isactive);?></td>
        </tr>
        <?php
    }
        ?>
</tbody>
</table>

<!-- Modal starts --> 

<div class="modal" id='addteacher' tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <?php 
    $attributes = array('id' => 'add-teacher', 'method' => 'post');
    echo form_open('dashcntl/addteacher', $attributes)?>
    <div class="modal-content">
        <input type='hidden' name='baseurl' value='<?php echo base_url();?>' />
        <input type='hidden' name='role' value='1' />
      <div class="modal-header">
        <h4 class="modal-title"><b>Add Teacher</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
          <span aria-hidden="true">&times;</span>
        </button>
        <div id='resp' class='hidden'></div>
      </div>
      <div class="modal-body">
        <div class='form-group'>
            <label class='label-control'>Name<span class='formfield'>*</span></label>
            <input type='text' name='name' id='name' class='form-control' placeholder='Enter name' />
        </div>
        <div class='form-group'>
            <label class='label-control'>Password<span class='formfield'>*</span></label>
            <input type='text' name='password' class='form-control' id='password'  placeholder='Enter password' />
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close()?>
    </div>
  </div>
</div>

<!-- Modal ends -->

</div>

