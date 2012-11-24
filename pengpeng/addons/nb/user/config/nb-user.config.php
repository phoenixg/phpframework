<?php
$config['nb-mvc/defaultModule'] = 'userPrivilege';

/* csUserTable */
$config['nb-table/item']['csUserTable']['columns']['id'] = array(
);
$config['nb-table/item']['csUserTable']['columns']['username'] = array(
  'function' => array(
    'nbWidget::truncate' => array('%username%', 100)
  ),
  'sort' => true,
);
$config['nb-table/item']['csUserTable']['columns']['password'] = array(
  'function' => array(
    'nbWidget::truncate' => array('%password%', 100)
  ),
  'sort' => false,
);
$config['nb-table/item']['csUserTable']['columns']['operate'] = array(
  'function' => array(
    'nbTableWidget::operate' => array('%id%')
  ),
);


/* csUserForm */
$config['nb-form/item']['csUserForm']['formAttribute'] = array(
  'name' => 'csUserForm',
  'method' => 'post',
  'action' => array('path' => 'user/submit'),
);

$config['nb-form/item']['csUserForm']['columns']['id'] = array(
  'function' => array(
    'nbFormWidget::hidden' => array('id', '%id%', array('name' => 'id'))
  ),
  'needBox' => false,
);
$config['nb-form/item']['csUserForm']['columns']['username'] = array(
  'function' => array(
    'nbFormWidget::text' => array('username', '%username%')
  ),
  'title' => 'User Name',
  'help' => 'Please input you username here',
);
$config['nb-form/item']['csUserForm']['columns']['oldPassword'] = array(
  'type' => 'edit',
  'function' => array(
    'nbFormWidget::password' => array('oldPassword')
  ),
  'title' => 'Old Password',
);
$config['nb-form/item']['csUserForm']['columns']['newPassword'] = array(
  'function' => array(
    'nbFormWidget::password' => array('newPassword')
  ),
  'title' => 'New Password',
);
$config['nb-form/item']['csUserForm']['columns']['submit'] = array(
  'function' => array(
    'nbFormWidget::submit' => array()
  ),
  'needTitle' => false,
);

//
///* csUserRoleTable */
//$config['nb-table/item']['csUserRoleTable']['columns']['username'] = array(
//  'function' => array(
//    'nbWidget::linkTo' => array('%username%', 'userRole/editUserRoles', array('query' => 'id=%user_id%')),
//  ),
//  'sort' => true,
//);
//
//$config['nb-table/item']['csUserRoleTable']['columns']['role'] = array(
//  'function' => array(
//    'nbTableWidget::display' => array('%role%')
//  ),
//  'sort' => true,
//  'title' => 'Role Key',
//);
//
//$config['nb-table/item']['csUserRoleTable']['columns']['roleName'] = array(
//  'function' => array(
//    'nbUserRoleTableWidget::getUserRole' => array('%role%')
//  ),
//  'title' => 'Role Name',
//);
//
//$config['nb-table/item']['csUserRoleTable']['columns']['operate'] = array(
//  'function' => array(
//    'nbTableWidget::operate' => array('%urid%')
//  ),
//);
//
//
///* csUserRoleForm */
//$config['nb-form/item']['csUserRoleForm']['formAttribute'] = array(
//  'name' => 'csUserRoleForm',
//  'method' => 'post',
//  'action' => array('path' => 'userRole/submit'),
//);
//
//$config['nb-form/item']['csUserRoleForm']['columns']['id'] = array(
//  'type' => 'edit',
//  'function' => array(
//    'nbFormWidget::hidden' => array('id', '%id%', array('name' => 'id'))
//  ),
//  'needBox' => false,
//);
//
//$config['nb-form/item']['csUserRoleForm']['columns']['rolename'] = array(
//  'function' => array(
//    'nbFormWidget::pathSelect' => array('rolename', '%role%', 'role/getRoleName')
//  ),
//  'title' => '组（角色）名',
//);
//$config['nb-form/item']['csUserRoleForm']['columns']['userid'] = array(
//  'function' => array(
//    'nbFormWidget::pathSelect' => array('uid', '%user_id%', 'user/getUser'),
//  ),
//  'title' => '用户',
//);
//$config['nb-form/item']['csUserRoleForm']['columns']['submit'] = array(
//  'function' => array(
//    'nbFormWidget::submit' => array()
//  ),
//  'needTitle' => false,
//);
//
///* csUserRolesForm */
//$config['nb-form/item']['csUserRolesForm']['formAttribute'] = array(
//  'name' => 'csUserRolesForm',
//  'method' => 'post',
//  'action' => array('path' => 'userRole/submitUserRoles'),
//);
//
//$config['nb-form/item']['csUserRolesForm']['columns']['id'] = array(
//  'type' => 'edit',
//  'function' => array(
//    'nbFormWidget::hidden' => array('uid', '%user_id%')
//  ),
//  'needBox' => false,
//);
//$config['nb-form/item']['csUserRolesForm']['columns']['userid'] = array(
//  'function' => array(
//    'nbFormWidget::display' => array('%username%'),
//  ),
//  'title' => '用户',
//);
//$config['nb-form/item']['csUserRolesForm']['columns']['rolename'] = array(
//  'function' => array(
//    'nbFormWidget::pathSelectMany' => array('roles', '%roles%', 'role/getUserRoles'),
//  ),
//  'title' => '组（角色）名',
//);
//
//$config['nb-form/item']['csUserRolesForm']['columns']['submit'] = array(
//  'function' => array(
//    'nbFormWidget::submit' => array()
//  ),
//  'needTitle' => false,
//);
//
//

/* csUserPrivilegeTable */
$config['nb-table/item']['csUserPrivilegeTable']['columns']['username'] = array(
  'function' => array(
    'nbTableWidget::display' => array('%username%'),
  ),
  'sort' => true,
);

$config['nb-table/item']['csUserPrivilegeTable']['columns']['role'] = array(
  'function' => array(
    'nbTableWidget::display' => array('%role%')
  ),
  'title' => 'Role Key',
);

$config['nb-table/item']['csUserPrivilegeTable']['columns']['roleName'] = array(
  'function' => array(
    'nbUserRoleTableWidget::getUserRoleForGroup' => array('%role%')
  ),
  'title' => 'Role Name',
);

$config['nb-table/item']['csUserPrivilegeTable']['columns']['privilege'] = array(
  'function' => array(
    'nbTableWidget::display' => array('%privilege%')
  ),
  'title' => 'Privilege Key',
);

$config['nb-table/item']['csUserPrivilegeTable']['columns']['privilegeName'] = array(
  'function' => array(
    'nbUserPrivilegeTableWidget::getUserPrivilegeForGroup' => array('%privilege%')
  ),
  'title' => 'Privilege Name',
);

$config['nb-table/item']['csUserPrivilegeTable']['columns']['operate'] = array(
  'function' => array(
    'nbWidget::linkTo' => array('编辑用户权限', 'userPrivilege/editUserPrivilege', array('query' => 'id=%uid%')),
  ),
);


/* csUserPrivilegeForm */
$config['nb-form/item']['csUserPrivilegeForm']['formAttribute'] = array(
  'name' => 'csUserRolesForm',
  'method' => 'post',
  'action' => array('path' => 'userRole/submitUserRoles'),
);

$config['nb-form/item']['csUserPrivilegeForm']['columns']['id'] = array(
  'type' => 'edit',
  'function' => array(
    'nbFormWidget::hidden' => array('uid', '%user_id%')
  ),
  'needBox' => false,
);
$config['nb-form/item']['csUserPrivilegeForm']['columns']['userid'] = array(
  'function' => array(
    'nbFormWidget::display' => array('%username%'),
  ),
  'title' => '用户',
);
$config['nb-form/item']['csUserPrivilegeForm']['columns']['roleName'] = array(
  'function' => array(
    'nbFormWidget::pathSelectMany' => array('roles', '%userRoles%', 'role/getUserRoles'),
  ),
  'title' => '组（角色）名',
);
$config['nb-form/item']['csUserPrivilegeForm']['columns']['privilegeName'] = array(
  'function' => array(
    'nbFormWidget::pathSelectMany' => array('privileges', '%userPrivileges%', 'userPrivilege/getUserPrivileges'),
  ),
  'title' => '权限名',
);
$config['nb-form/item']['csUserPrivilegeForm']['columns']['submit'] = array(
  'function' => array(
    'nbFormWidget::submit' => array()
  ),
  'needTitle' => false,
);