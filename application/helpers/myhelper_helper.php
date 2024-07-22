<?php 
function getUserStatus($status) {
    switch($status) {
        case 1:
            return 'Active';
        case 0:
            return 'Inactive';
        default:
        return 'NA';
    }
}

function decrypt_password($pwd) {
    $ci =&get_instance();
    $ci->load->library('encryption');
    $ci->encryption->initialize(
     array(
             'cipher' => 'aes-256',
             'mode' => 'ctr',
             'key' => $ci->config->item('encryption_key')
     )
 );
   return $ci->encryption->decrypt($pwd); 
}

function getUsersPermissions($mainmenu=NULL,$submenu=NULL,$userrole=NULL) {
    $ci=&get_instance();
    $users = $ci->load->model('college');
    $menuList=$ci->college->getPermissions();
    $menuPermns=array_filter($menuList, function($item) use($mainmenu)  {
        return $item['t1'] == $mainmenu;
    });
        
    $menu = array_filter($menuPermns, function($item) use ($submenu){
        return $item['t2'] == $submenu;
    });
    $c = array_column($menu,'permissions');
    $permns = explode(',',$c[0]);
    if(in_array($userrole,$permns)) {
        return TRUE;
    }
    return FALSE;
}
?>
