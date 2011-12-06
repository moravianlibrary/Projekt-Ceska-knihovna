<?
class Menu
{
	public static $mainMenuItems = array('Organisation', 'Publisher', 'Library', 'Book', 'Voting', 'LibOrder', 'PubOrder', 'Stock');
	
	private $_items = array();
	private $moduleId = '';
	private $controllerId = '';
	private $actionId = '';
	private $route = '';

	function __construct($route = '')
	{
		$this->route = $route;
		
		$a = explode('/', $route);
		if (sizeOf($a) == 1)
		{
			$this->controllerId = $a[0];
		}
			elseif (sizeOf($a) == 2)
			{
				$this->controllerId = $a[0];
				$this->actionId = $a[1];
			}
			elseif (sizeOf($a) == 3)
				{
					$this->moduleId = $a[0];
					$this->controllerId = $a[1];
					$this->actionId = $a[2];
				}
		$this->initItems();
	}

	protected function initItems()
	{
		$sp = Yii::app()->getStatePersister();
		$state = $sp->load();
		
		$accessParams = array();
		$accessParams['Book:Create'] = array('enable'=>(!user()->checkAccess('PublisherRole') || (!user()->publisher_offer_id && param('pubBookDate') >= DT::isoToday())));
		/*
		$accessParams['Book:Update'] = array('can_change'=>(user()->checkAccess('BackOffice') || param('pubBookDate') >= DT::isoToday()));
		$accessParams['Book:Delete'] = array('can_change'=>(user()->checkAccess('BackOffice') || param('pubBookDate') >= DT::isoToday()));
		*/
		$accessParams['Publisher:ClientUpdate'] = array('enable'=>(user()->checkAccess('PublisherRole') && user()->publisher_offer_id == 0));
		$accessParams['Library:ClientUpdate'] = array('enable'=>(user()->checkAccess('LibraryRole') && user()->library_order_placed == 0));

		// System
		$this->_items['_main'] = array();
		if (user()->checkAccess('PublisherRole'))
		{
			$this->insertItem('Publisher:ClientUpdate', '_main', array('/publisher/clientUpdate'), 'Publisher', array(), $accessParams['Publisher:ClientUpdate']);
		}
		if (user()->checkAccess('LibraryRole'))
		{
			$this->insertItem('Library:ClientUpdate', '_main', array('/library/clientUpdate'), 'Library', array(), $accessParams['Library:ClientUpdate']);
		}

		$this->insertItems(self::$mainMenuItems);

		if (user()->checkAccess('CouncilRole'))
		{
			if (!@$state['poll_generated'])
				$this->insertItem('Voting:Rating', '_main', 'rating', 'Rating');
			else
				$this->insertItem('Voting:RatingResults', '_main', 'ratingResults', 'Rating Results');
		}
		if (user()->checkAccess('LibraryRole'))
		{
			$this->insertItem('LibOrder:Sheet', '_main', '/libOrder/sheet', 'Order');
			if (user()->library_order_placed) $this->insertItem('IncNumber:Update', '_main', '/incNumber/update', 'Inc Numbers');
		}

		$this->insertItem('Site:Setting', '_main', 'setting', 'Settings');

		if ($this->route == 'site/setting' || $this->moduleId == 'rbam' || $this->controllerId == 'user')
		{
			$this->insertItem('User', '_setting');
			$this->insertItem('RBAC Manager', '_setting', '/rbam', 'Access permissions');
		}

		if ($this->moduleId == 'rbam' && user()->checkAccess('RBAC Manager'))
		{
			$this->_items['rbam'] = app()->getModule('rbam')->getMenuItems();
		}
				
		// Modules
		if ($this->moduleId != $this->controllerId)
		{
			$actions = $this->getActions($this->controllerId);
			$this->_items[$this->controllerId] = array();
			if (user()->checkAccess(ucfirst($this->controllerId)) || user()->checkAccess(ucfirst($this->controllerId).':Index'))
			{
				foreach ($actions as $actionId=>$actionName)
				{
					if ($actionId != $this->actionId && user()->checkAccess(ucfirst($this->controllerId).':'.ucfirst($actionId), $accessParams[ucfirst($this->controllerId).':'.ucfirst($actionId)]))
					{
						switch ($actionId)
						{
							case 'update': case 'view': case 'delete':
								break;
							/*
							case 'update': case 'view':
								if (isset($_GET['id']) && is_numeric($_GET['id'])) $this->_items[$this->controllerId][] = array('label'=>$actionName, 'url'=>array($actionId, 'id'=>$_GET['id']), 'linkOptions'=>array('id'=>"menu-${actionId}-record"));
								break;
							case 'delete':
								if (isset($_GET['id']) && is_numeric($_GET['id'])) $this->_items[$this->controllerId][] = array('label'=>$actionName, 'url'=>'#', 'linkOptions'=>array('id'=>"menu-${actionId}-record", 'submit'=>array('delete','id'=>$_GET['id']),'confirm'=>'Odstranit záznam?'));
								break;
							*/
							default:
								$this->_items[$this->controllerId][] = array('label'=>$actionName, 'url'=>array($actionId), 'linkOptions'=>array('id'=>"menu-${actionId}-record"));
								break;
						}
					}
				}
			}
		}
		
		if (user()->checkAccess('LibraryRole'))
		{
			$this->insertItem('LibOrder:PlaceOrder', 'libOrder', '/libOrder/placeOrder', 'Place Order', array(), array('order_placed'=>user()->library_order_placed));
			$this->insertItem('LibOrder:PrintOrder', 'libOrder_print', '/libOrder/printOrder', 'Order', array('target'=>'_blank'), array('order_placed'=>user()->library_order_placed));
		}
		if (user()->checkAccess('PublisherRole'))
		{
			$linkOptions = array('target'=>'_blank');
			if (!user()->publisher_offer_id) $linkOptions['confirm'] = t('Po vygenerování žádosti již nebude možné provádět v nabídce bez asistence pracovníků MZK žádné úpravy. Přejete si pokračovat?');
			$this->insertItem('Publisher:PrintRequest', 'book_print', '/publisher/printRequest', 'Print Request', $linkOptions);
		}
		
		if (!@$state['poll_generated'])
			$this->insertItem('Book:GeneratePoll', 'book', '/book/generatePoll', 'Generovat seznam publikací pro hlasování');
		elseif (!@$state['selected_generated'])
			$this->insertItem('Book:GenerateSelected', 'book', '/book/generateSelected', 'Potvrdit seznam vybraných publikací');
			else
			{
				$this->insertItem('Publisher:LetterSelected', 'publisher_print', '/publisher/letterSelected', 'Dopisy vybraným nakladatelům', array('target'=>'_blank'));		
				$this->insertItem('Publisher:LetterUnselected', 'publisher_print', '/publisher/letterUnselected', 'Dopisy nevybraným nakladatelům', array('target'=>'_blank'));
			}
		$this->insertItem('Voting:RatingResults', 'voting', 'ratingResults', 'Rating Results');
		$this->insertItem('Book:Poll', 'voting', 'poll', 'Voting');
		
		$this->insertItem('LibOrder:GetReserves', 'libOrder', '/libOrder/getReserves', 'Reserves');
		
		if (!@$state['puborder_generated'])
			$this->insertItem('PubOrder:PlaceOrder', 'pubOrder', '/pubOrder/placeOrder', 'Place Orders');
		else
		{
			$this->insertItem('PubOrder:Dun', 'pubOrder', '/pubOrder/dun', 'Duns');
			$this->insertItem('PubOrder:PrintOrder', 'pubOrder_print', '/pubOrder/printOrder', 'Orders', array('target'=>'_blank'));
		}
		
		$this->insertItem('StockActivity:PubActivity', 'stock', '/stockActivity/pubActivity', 'Pub Orders');
		$this->insertItem('StockActivity:LibActivity', 'stock', '/stockActivity/libActivity', 'Lib Orders');
		$this->insertItem('Stock:BillOfDelivery', 'stock', '/stock/billOfDelivery', 'Bills of Delivery', array('target'=>'_blank'));

		$this->insertItem('Stock:Admin', 'stockActivity', '/stock/admin', 'Manage Stocks');
		$this->insertItem('Stock:Admin', 'stockActivity', '/stock/index', 'View Stocks');
		$this->insertItem('StockActivity:PubActivity', 'stockActivity', '/stockActivity/pubActivity', 'Pub Orders');
		$this->insertItem('StockActivity:LibActivity', 'stockActivity', '/stockActivity/libActivity', 'Lib Orders');
		$this->insertItem('Stock:BillOfDelivery', 'stockActivity', '/stock/billOfDelivery', 'Bills of Delivery', array('target'=>'_blank'));
	}
	
	public function insertItems($items, $type = '_main')
	{
		foreach ($items as $item) $this->insertItem($item, $type);		
	}
	
	public function insertItem($rights, $type = '_main', $action = 'admin', $title = '', $linkOptions = array(), $accessParams = array())
	{
		$model = explode(':', $rights);
		$modelClass = $model[0];
		
		if ($title == '') $title = $this->pluralize($this->class2name($modelClass));
		if (!is_array($action))
		{
			if ($action != '#')
			{
				if (substr($action, 0, 2) == '//')
				{
					$action = substr($action, 1);
				}
				elseif (substr($action, 0, 1) == '/')
					{
						$action = array($action);
					}
					else
					{
						$action = array('/'.$this->class2var($modelClass).'/'.$action);
					}
			}
		}
		if (user()->checkAccess($rights, $accessParams) || (sizeof($model) == 1 && user()->checkAccess($modelClass.':Index'))) $this->_items[$type][] = array('label'=>t($title), 'url'=>$action, 'linkOptions'=>$linkOptions);
	}

	protected function getActions($name)
	{
		$name = $this->class2name($name);
		$pluralName = $this->pluralize($name);
		return array('admin'=>t('Manage '.$pluralName), 'index'=>t('View '.$pluralName), 'update'=>t('Update '.$name), 'view'=>t('View '.$name), 'delete'=>t('Delete '.$name), 'create'=>t('Create '.$name));
	}
	
	public function items($type = '')
    {
		if ($type == '')
			$type = ($this->moduleId != '' ? $this->moduleId : $this->controllerId);
		
		if (isset($this->_items[$type]))
			return $this->_items[$type];
		else 
			return array();
    }
    
    protected function pluralize($name)
	{
		$rules=array(
				'/(x|ch|ss|sh|us|as|is|os)$/i' => '\1es',
				'/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
				'/(m)an$/i' => '\1en',
				'/(child)$/i' => '\1ren',
				'/(r|t)y$/i' => '\1ies',
				'/s$/' => 's',
		);
		foreach($rules as $rule=>$replacement)
		{
				if(preg_match($rule,$name))
						return preg_replace($rule,$replacement,$name);
		}
		return $name.'s';
	}
	
	protected function class2name($name,$ucwords=true)
	{
		$result=trim(strtolower(str_replace('_',' ',preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name))));
		return $ucwords ? ucwords($result) : $result;
	}

	protected function class2var($name)
	{
			$name[0]=strtolower($name[0]);
			return $name;
	}
	
	public function clearItems()
	{
		$this->_items = array();
	}
}