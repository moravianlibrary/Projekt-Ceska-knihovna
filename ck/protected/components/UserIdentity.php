<?
class UserIdentity extends CUserIdentity
{
	private $_id;

    public function authenticate()
    {
		$username=strtolower($this->username);
        $user=User::model()->find('username=?', array($username));
        if ($user === null) $this->errorCode=self::ERROR_USERNAME_INVALID;
        elseif (!$user->validatePassword($this->password)) $this->errorCode=self::ERROR_PASSWORD_INVALID;
			else
			{
				$this->_id=$user->id;
				$this->username = $user->username;
				$this->errorCode = self::ERROR_NONE;
				$this->setState('active_tab', '');
				
				$this->setState('organisation_id', 0);
				$organisation = Organisation::model()->findByAttributes(array('user_id'=>$user->id));
				if ($organisation !== null)
				{
					$this->setState('organisation_id', $organisation->id);
				}			
				
				$this->setState('publisher_id', 0);
				$this->setState('publisher_offer_id', 0);
				$this->setState('publisher_order_placed', 0);
				if (am()->checkAccess('PublisherRole', $user->id))
				{
					$publisher = Publisher::model()->findByAttributes(array('user_id'=>$user->id));
					if ($publisher !== null)
					{
						$this->setState('publisher_id', $publisher->id);
						$this->setState('publisher_offer_id', (int) $publisher->offer_id);
						$this->setState('publisher_order_placed', $publisher->order_placed);
					}
				}
					
				$this->setState('library_id', 0);
				$this->setState('library_order_placed', 0);
				if (am()->checkAccess('LibraryRole', $user->id))
				{
					$library = Library::model()->findByAttributes(array('user_id'=>$user->id));
					if ($library !== null)
					{
						$this->setState('library_id', $library->id);
						$this->setState('library_order_placed', $library->order_placed);
					}
				}
			}
        return $this->errorCode==self::ERROR_NONE;
    }

    public function getId()
    {
        return $this->_id;
    }
}
