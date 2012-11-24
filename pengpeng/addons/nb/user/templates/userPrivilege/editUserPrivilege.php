<?php
//nbWidget::includeComponent('@nb-form', array('name' => 'csUserPrivilegeForm', 'type' => 'edit', 'editValue' => $editValue));
?>
用户：
<?php echo $username; ?><br /><br />
<form action="<?php echo nbWidget::url('userPrivilege/submit', array('uid' => $uid)) ?>" method="post">
<?php foreach ($apps as $app => $appPrivilegeInfo) :?>
<div>
<?php echo $app ?>:<br />
<?php foreach ($allRoles[$app] as $roleKey => $role) :?>
<div id="role_<?php echo str_replace('/', '_', $roleKey) ?>">
<?php if (isset($userRoles[$app]) && in_array($roleKey, $userRoles[$app])): ?>
<?php echo nbFormWidget::checkbox('roles', $roleKey, true, $role['name'], array('onclick' => 'roleCheck(this)')) ?>:
<?php else :?>
<?php echo nbFormWidget::checkbox('roles', $roleKey, false, $role['name'], array('onclick' => 'roleCheck(this)')) ?>:
<?php endif; ?>
&nbsp;&nbsp;&nbsp;&nbsp;

<?php if(isset($allRolePrivileges[$app])) :?>
<?php foreach ($allRolePrivileges[$app][$roleKey] as $privilegeKey => $privilegeInfo) :?>
<?php if (isset($userPrivileges[$app]) && in_array($privilegeKey, $userPrivileges[$app])): ?>
<?php echo nbFormWidget::checkbox('privilege', $privilegeKey, true, $privilegeInfo['name'], array('onclick' => 'privilegeCheck(this)')) ?>,
<?php else :?>
<?php echo nbFormWidget::checkbox('privilege', $privilegeKey, false, $privilegeInfo['name'], array('onclick' => 'privilegeCheck(this)')) ?>,
<?php endif; ?>

<?php endforeach; ?>
<?php endif; ?>
<br />
</div>
<?php endforeach; ?>

</div>
<?php endforeach;?>
<?php echo nbFormWidget::submit(); ?>
</form>
<script>
$('input').each(function(){
  if (this.name == 'roles[]' && this.checked == true)
  {
    $('#role_csUser_manager input').each(function(){
      this.checked = 'checked';
    });
  }
})
roleCheck = function(dom)
{
  $('#' + $(dom).parent().attr('id') + ' input').each(function(){

    if ($(dom).attr('checked') == true)
    {
      this.checked = true;
    }
    else
    {
      this.checked = false;
    }
  })
}

privilegeCheck = function(dom)
{
  value = $(dom).attr('value');
  $('input').each(function()
  {
    if (this.name == 'privilege[]' && this.value == value)
    {
      this.checked = $(dom).attr('checked');
    }
  })
}
</script>