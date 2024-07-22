<div>
    <input type='hidden' name='baseurl' value='<?php echo base_url();?>' />
    <input type='hidden' name=<?php echo $this->security->get_csrf_token_name()?> value=<?php echo $this->security->get_csrf_hash();?> />    
    <?php if($add) {
        ?>
        <button class='btn btn-primary' data-toggle='modal' data-target='#addstudent'>+ Add </button>
        <?php
    }
    ?>
    <?php $errors = $this->session->flashdata('error');
    if(!empty($errors))
    {
        print_r($errors['error']);
    }
    if(!empty($this->session->flashdata('success'))) {
        echo $this->session->flashdata('success');
    }
 ?>
<div class='error hidden' id="resp"></div>
    <table id='dynamic-student-table'>
    <input type='hidden' name='baseurl' value='<?php echo base_url();?>' />
    <input type='hidden' name=<?php echo $this->security->get_csrf_token_name()?> value=<?php echo $this->security->get_csrf_hash();?> />
    <thead>
        <tr>
            <th>Slno</th>
            <th>Name</th>
            <th>Rollno</th>
            <th>Address</th>
            <th>Class</th>
            <th>Subject1</th>
            <th>Subject2</th>
            <th>Subject3</th>
            <th>Grade</th>
            <th>Created on</th>
            <th></th>
        </tr>
        <tbody></tbody>
    </thead>
</table>

<!-- modal edit starts-->

<div class='editModal hidden' id='editstudent' >
    <div class='modal-dialog' role="document">
    <div class='modal-content'>
    <div class='modal-header'>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
        <?php 
        $attributes = ['id' => 'editstudentfrm', 'method'=>'post'];
        echo form_open('dashcntl/savestudent',$attributes);?>
        <h4><b>Edit Student</b></h4>
        <input type='hidden' name='slno' id='slno' />
    </div>
    <div class='modal-body'>
        <div class="row">
            <div class="col-md-6">
                <div class='form-group'>
                    <label class='label-control'>Name:</label>
                    <input type='text' name='name' id='name' class='form-control' />
                </div>
            </div>
            <div class="col-md-6">
                <div class='form-group'>
                    <label class='label-control'>Class:</label>
                    <input type='text' name='rollno' id='rollno' class='form-control' />
                </div>
            </div>      
        </div>
      
        <div class='row'>
            <div class='col-md-6'>
                <div class='form-group'>
                    <label class='label-control'>Address:</label>
                    <textarea class='form-control' name='address' id='address' placeholder='Enter address'></textarea>
                </div>
            </div>
            <div class='col-md-6'>
                <div class='form-group'>
                    <label class='label-control'>Grade:</label>
                    <input type='text' name='grade' id='grade' class='form-control' />
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-4'>
                <div class='form-group'>
                    <label class='label-control'>subject1:</label>
                    <input type='number' name='sub1' id='sub1' class='form-control subfield' />
                </div>
            </div>
            <div class='col-md-4'>
                <div class='form-group'>
                    <label class='label-control'>Subject2:</label>
                    <input type='number' name='sub2' id='sub2' class='form-control subfield' />
                </div>
            </div>
            <div class='col-md-4'>
                <div class='form-group'>
                    <label class='label-control'>Subject3:</label>
                    <input type='number' name='sub3' id='sub3' class='form-control subfield'  />
                </div>
            </div>
        </div>
</div>

<div class='modal-footer'>
    <button class='btn btn-warning class' data-dismiss='modal'>Cancel</button>
    <button class='btn btn-primary savestudentbtn' type='submit'>Save</button>
    <?php echo form_close();?>
</div>
</div>
</div>
</div>
<!-- modal edit ends -->
<!-- modal add starts -->
<div class='modal' id='addstudent'>
    <div class='modal-dialog' role="document">
    <div class='modal-content'>
    <div class='modal-header'>
        <?php 
        $attributes = ['id' => 'addstudentfrm', 'method'=>'post'];
        echo form_open('dashcntl/savestudent',$attributes);?>
        <h4><b>Add Student</b></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">X</button>
    </div>
    <div class='modal-body'>
        <div class='form-group'>
        <label class='label-control'>Name:</label>
        <input type='text' name='name' id='name' value="<?php echo set_value('name'); ?>" class='form-control' placeholder='Enter name' />
    </div>

    <div class='form-group'>
        <label class='label-control'>Rollno:</label>
        <input type='text' name='rollno' id='rollno' class='form-control' value="<?php echo set_value('rollno'); ?>" placeholder='Enter rollno' />
    </div>

    <div class='form-group'>
        <label class='label-control'>Class:</label>
        <input type='text' name='stuclass' id='stuclass' class='form-control' value="<?php echo set_value('stuclass'); ?>" placeholder='Enter Class' />
    </div>

    <div class='form-group'>
        <label class='label-control'>Address:</label>
        <textarea class='form-control' name='address' id='address'  value="<?php echo set_value('address'); ?>" placeholder='Enter address'></textarea>
    </div>
    <h4>Marks</h4>
    <hr/>
    <div class='form-group'>
        <label class='label-control'>subject1:</label>
        <input type='number' name='sub1' id='sub1' class='form-control subfield' value="<?php echo set_value('sub1'); ?>" placeholder='Enter mark' />
    </div>

    <div class='form-group'>
        <label class='label-control'>Subject2:</label>
        <input type='number' name='sub2' id='sub2' class='form-control subfield' value="<?php echo set_value('sub2'); ?>" placeholder='Enter mark'  />
    </div>


    <div class='form-group'>
        <label class='label-control'>Subject3:</label>
        <input type='number' name='sub3' id='sub3' class='form-control subfield' value="<?php echo set_value('sub3'); ?>" placeholder='Enter mark'  />
    </div>


    <div class='form-group'>
        <label class='label-control'>Grade:</label>
        <input type='text' name='grade' id='grade' value="<?php echo set_value('grade'); ?>" class='form-control' />
    </div>

</div>

<div class='modal-footer'>
    <button class='btn btn-warning class' data-dismiss='modal'>Cancel</button>
    <button class='btn btn-primary savestudentbtn' type='submit'>Save</button>
    <?php echo form_close();?>
</div>
</div>
</div>
</div>

<!-- modal add ends -->
</div>