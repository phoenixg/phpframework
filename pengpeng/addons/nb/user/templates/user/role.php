<select onchange="changeUserRole()" id="user">
<?php foreach ($users as $user): ?>
<option></option>
<option value="<?php echo $user['id'] ?>"><?php echo $user['username'] ?></option>
<?php endforeach; ?>
</select>

<script>
changeUserRole = function()
{
  if($('#user').val())
  {
    $.ajax({
      url: '<?php echo nbMvcWidget::url('user/changeUserRole') ?>?id=' + $('#user').val(),
      success: function(response) {
        $('#roleList').html(response);
      }
    });
  }
}
</script>

<div id="roleList">

</div>