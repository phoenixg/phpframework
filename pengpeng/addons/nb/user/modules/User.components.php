<?php
class UserComponents extends nbAction
{
  /**
   * use section here called "includeSection"
   *
   * @return multitype:number
   */
  public function getUserAction()
  {
    $result = $this->service->select('id, username', 'cs_user');
    foreach ($result as $user)
    {
      $options[$user['id']] = $user['username'];
    }

    return $options;
  }
}