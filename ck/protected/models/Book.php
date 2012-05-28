<?php

class Book extends ActiveRecord
{
	protected $_name = null;
	protected $_prevTitle = null;
	public $isbnPart = '';

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return '{{book}}';
	}

	public function scopes()
	{
		return array_merge(parent::scopes(), array(
			'accepted'=>array(
				'condition'=>'t.status=0',
			),
			'rejected'=>array(
				'condition'=>'(t.status IS NULL OR t.status>0)',
			),
			'selected'=>array(
				'condition'=>'t.selected=1',
			),
			'notSelected'=>array(
				'condition'=>'t.selected=0',
			),
			'unSelected'=>array(
				'condition'=>'t.selected IS NULL',
			),
			'thisYear'=>array(
				'condition'=>'t.project_year='.param('projectYear'),
			),
		));
	}

	public function rules()
	{
		$rules = array(
			array('author, title, editor, illustrator, isbn, isbnPart, preamble, epilogue, binding, annotation, comment', 'filter', 'filter'=>'strip_tags'),
			array('author, title', 'required'),
			array('isbnPart', 'isbnValid'),
			array('isbnPart', 'isbnLength'),
			array('isbn', 'filter', 'filter'=>array($this, 'createISBN')),
			array('format_height, format_width, binding, available, pages_printed, pages_other, issue_year, retail_price, offer_price, annotation', 'required'),
			array('available, pages_printed, pages_other', 'numerical', 'integerOnly'=>true),
			array('issue_year', 'numerical', 'integerOnly'=>true, 'min'=>(param('projectYear')-1), 'max'=>param('projectYear')),
			array('format_height, format_width', 'numerical', 'integerOnly'=>true, 'min'=>1, 'max'=>999),
			array('retail_price, offer_price', 'filter', 'filter'=>array($this, 'numerize')),
			array('retail_price, offer_price', 'numerical'),
			array('offer_price', 'compare', 'compareAttribute'=>'retail_price', 'operator'=>'<'),
			array('author, title, editor, illustrator, preamble, epilogue, binding', 'length', 'max'=>255),
			array('annotation', 'length', 'max'=>2000),
			array('comment', 'safe'),
		);

		if (user()->checkAccess('BackOffice'))
		{
			$rules = array_merge($rules,
			array(
				array('council_comment', 'filter', 'filter'=>'strip_tags'),
				array('status', 'numerical', 'integerOnly'=>true, 'allowEmpty'=>true),
				array('publisher_id, project_price', 'required'),
				array('project_price', 'filter', 'filter'=>array($this, 'numerize')),
				array('project_price', 'numerical'),
				array('publisher_id, votes_yes, votes_no, votes_withheld', 'numerical', 'integerOnly'=>true),
				array('council_comment', 'safe'),
				array('name, project_year, offered, status', 'safe', 'on'=>'search'),
			));
		}

		if (user()->checkAccess('BackOffice'))
		{
			$rules = array_merge($rules,
			array(
				array('votes_yes, votes_no, votes_withheld, selected', 'required', 'on'=>'poll'),
				array('votes_yes, votes_no, votes_withheld, selected', 'numerical', 'integerOnly'=>true, 'on'=>'poll'),
				array('publisher_id, author, title, isbn, isbnPart, editor, illustrator, format_width, format_height, available, pages_printed, pages_other, issue_year, retail_price, offer_price, project_price, binding, preamble, epilogue, annotation, council_comment, comment, offered', 'unsafe', 'on'=>'poll'),
			));
		}

		if (user()->checkAccess('BackOffice'))
		{
			$rules = array_merge($rules,
			array(
				array('selected', 'required', 'on'=>'poll_select'),
				array('selected', 'numerical', 'integerOnly'=>true, 'on'=>'poll_select'),
				array('publisher_id, author, title, isbn, isbnPart, editor, illustrator, format_width, format_height, available, pages_printed, pages_other, issue_year, retail_price, offer_price, project_price, binding, preamble, epilogue, annotation, council_comment, comment, offered, votes_yes, votes_no, votes_withheld', 'unsafe', 'on'=>'poll_select'),
			));
		}

		if (user()->checkAccess('PublisherRole'))
		{
			$rules = array_merge($rules,
			array(
				array('status', 'safe', 'on'=>'search'),
			));
		}

		return $rules;
	}

	public function isbnValid($attribute, $params)
	{
		if ($this->isbnPart != '')
		{
			$n = str_replace('-', '', param('isbnPrefix').$this->isbnPart);
			if (is_numeric($n))
			{
				$check = 0;
				for ($i = 0; $i < 13; $i+=2) $check += substr($n, $i, 1);
				for ($i = 1; $i < 12; $i+=2) $check += 3 * substr($n, $i, 1);
				if ($check % 10 != 0) $this->addError($attribute, strtr(Yii::t('yii','The format of {attribute} is invalid.'),array('{attribute}'=>$this->getAttributeLabel($attribute))));
			}
			else $this->addError($attribute, strtr(Yii::t('yii','The format of {attribute} is invalid.'),array('{attribute}'=>$this->getAttributeLabel($attribute))));
		}
	}

	public function isbnLength($attribute, $params)
	{
		if (mb_strlen(param('isbnPrefix').$this->isbnPart, 'UTF-8') > 32) $this->addError($attribute, strtr(Yii::t('yii','{attribute} is too long (maximum is {max} characters).'),array('{attribute}'=>$this->getAttributeLabel($attribute), '{max}'=>32)));
	}

	public function createISBN($value)
	{
		if ($this->isbnPart != '')
			return param('isbnPrefix').$this->isbnPart;
		else
			return '';
	}

	public function numerize($value)
	{
		return str_replace(',', '.', $value);
	}

	public function relations()
	{
		return array(
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'publisher' => array(self::BELONGS_TO, 'Publisher', 'publisher_id'),
			'libraries' => array(self::MANY_MANY, 'Library', '{{lib_order}}(book_id, library_id)'),
			'votings' => array(self::HAS_MANY, 'Voting', 'book_id'),
			'rating' => array(self::HAS_ONE, 'Voting', 'book_id', 'on'=>'rating.user_id='.user()->id.' AND rating.type=\''.Voting::RATING.'\''),
			'ratings' => array(self::HAS_MANY, 'Voting', 'book_id', 'on'=>'rating.type=\''.Voting::RATING.'\''),
			'sum_ratings' => array(self::STAT, 'Voting', 'book_id', 'select'=>'SUM(points)', 'condition'=>'type=\''.Voting::RATING.'\''),
			'poll' => array(self::HAS_ONE, 'Voting', 'book_id', 'on'=>'poll.user_id='.user()->id.' AND poll.type=\''.Voting::POLL.'\''),
			'polls' => array(self::HAS_MANY, 'Voting', 'book_id', 'on'=>'poll.type=\''.Voting::POLL.'\''),
			'lib_orders' => array(self::HAS_MANY, 'LibOrder', 'book_id'),
			'pub_orders' => array(self::HAS_MANY, 'PubOrder', 'book_id'),
			'basic' => array(self::HAS_ONE, 'LibOrder', 'book_id', 'on'=>'basic.user_id='.user()->id.' AND basic.type=\''.LibOrder::BASIC.'\''),
			'basics' => array(self::HAS_MANY, 'LibOrder', 'book_id', 'on'=>'basic.type=\''.LibOrder::BASIC.'\''),
			'reserve' => array(self::HAS_ONE, 'LibOrder', 'book_id', 'on'=>'reserve.user_id='.user()->id.' AND reserve.type=\''.LibOrder::RESERVE.'\''),
			'reserves' => array(self::HAS_MANY, 'LibOrder', 'book_id', 'on'=>'reserve.type=\''.LibOrder::RESERVE.'\''),
			'book_titles' => array(self::HAS_MANY, 'BookTitle', 'book_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' =>  Yii::t('app', 'ID'),
			'create_time' =>  Yii::t('app', 'Create Time'),
			'modify_time' =>  Yii::t('app', 'Modify Time'),
			'ip_address' =>  Yii::t('app', 'Ip Address'),
			'user_id' =>  Yii::t('app', 'User'),
			'publisher_id' =>  Yii::t('app', 'Publisher'),
			'project_year' =>  Yii::t('app', 'Project Year'),
			'author' =>  Yii::t('app', 'Author'),
			'title' =>  Yii::t('app', 'Title'),
			'editor' =>  Yii::t('app', 'Editor'),
			'illustrator' =>  Yii::t('app', 'Illustrator'),
			'preamble' =>  Yii::t('app', 'Preamble'),
			'epilogue' =>  Yii::t('app', 'Epilogue'),
			'issue_year' =>  Yii::t('app', 'Issue Year'),
			'available' =>  Yii::t('app', 'Available'),
			'pages_printed' =>  Yii::t('app', 'Pages Printed'),
			'pages_other' =>  Yii::t('app', 'Pages Other'),
			'format_width' =>  Yii::t('app', 'Format Width'),
			'format_height' =>  Yii::t('app', 'Format Height'),
			'binding' =>  Yii::t('app', 'Binding'),
			'retail_price' =>  Yii::t('app', 'Retail Price'),
			'offer_price' =>  Yii::t('app', 'Offer Price'),
			'project_price' =>  Yii::t('app', 'Project Price'),
			'isbn' =>  Yii::t('app', 'ISBN'),
			'isbnPart' =>  Yii::t('app', 'ISBN'),
			'annotation' =>  Yii::t('app', 'Annotation'),
			'council_comment' =>  Yii::t('app', 'Council Comment'),
			'comment' =>  Yii::t('app', 'Comment'),
			'offered' =>  Yii::t('app', 'Offered'),
			'status' =>  Yii::t('app', 'Status'),
			'points' =>  Yii::t('app', 'Points'),
			'votes_yes' =>  Yii::t('app', 'Votes Yes'),
			'votes_no' =>  Yii::t('app', 'Votes No'),
			'votes_withheld' =>  Yii::t('app', 'Votes Withheld'),
			'selected' =>  Yii::t('app', 'Selected'),
			'selected_order' =>  Yii::t('app', 'Order nr.'),
			'name' =>  Yii::t('app', 'Publisher'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		if (user()->publisher_id)
			$criteria->compare('t.project_year',param('projectYear'));

		$criteria->compare('t.isbn',$this->isbn,true);
		$criteria->compare('t.author',$this->author,true);
		$criteria->compare('t.title',$this->title,true);
		$criteria->compare('organisation.name',$this->name, true);
		$criteria->compare('t.issue_year',$this->issue_year);
		$criteria->compare('t.project_year',$this->project_year);
		$criteria->compare('t.offered',$this->offered);
		if ($this->status == -1) $criteria->addCondition('t.status IS NULL');
		else $criteria->compare('t.status',$this->status);
		$criteria->with = array('publisher','publisher.organisation');

		return new CActiveDataProvider($this->my(), array(
			'criteria'=>$criteria,
			'sort'=>array(
				'defaultOrder'=>'t.id DESC',
				'attributes'=>array(
					'*',
					'name'=>array(
						'asc'=>'organisation.name',
						'desc'=>'organisation.name desc',
						),
					),
				'multiSort'=>true,
				),
			'pagination'=>array('pageSize'=>20,),
		));
	}

	protected function afterFind()
	{
		parent::afterFind();
		$this->isbnPart = preg_replace('/^'.param('isbnPrefix').'/', '', $this->isbn);
		$this->_prevTitle = $this->title;
	}

	protected function afterSave()
	{
		parent::afterSave();

		if (!$this->isNewRecord)
		{
			if ($this->_prevTitle != $this->title)
			{
				$bt = new BookTitle;
				$bt->user_id = user()->id;
				$bt->book_id = $this->id;
				$bt->title = $this->_prevTitle;
				$bt->save(false);
			}
		}
	}

	public function getName()
	{
		if ($this->_name === null && $this->publisher !== null && $this->publisher->organisation !== null)
		{
			$this->_name = $this->publisher->organisation->name;
		}
		return $this->_name;
	}

	public function setName($value)
	{
		$this->_name = $value;
	}

	public function getStatus_c()
	{
		return DropDownItem::item('Book.status', $this->status);
	}

	public function getCanView()
	{
		return true;
	}

	public function getCanUpdate()
	{
		return (!user()->publisher_id || (!$this->offered && param('pubBookDate') >= DT::isoToday()));
	}

	public function getCanDelete()
	{
		return (!user()->publisher_id || (!user()->publisher_offer_id && param('pubBookDate') >= DT::isoToday()));
	}

	public function getSumLibBasics()
	{
		$result = db()->createCommand("SELECT SUM(count) AS cnt FROM {{lib_order}} LEFT JOIN {{library}} ON {{lib_order}}.library_id={{library}}.id WHERE {{lib_order}}.book_id=".$this->id." AND {{lib_order}}.type='".LibOrder::BASIC."' AND {{library}}.order_placed=1 AND {{library}}.order_date>'0000-00-00'")->queryScalar();
		if ($result)
			return $result;
		else
			return 0;
	}

	public function getSumLibReserves()
	{
		$result = db()->createCommand("SELECT SUM(count) AS cnt FROM {{lib_order}} LEFT JOIN {{library}} ON {{lib_order}}.library_id={{library}}.id WHERE {{lib_order}}.book_id=".$this->id." AND {{lib_order}}.type='".LibOrder::RESERVE."' AND {{library}}.order_placed=1 AND {{library}}.order_date>'0000-00-00'")->queryScalar();
		if ($result)
			return $result;
		else
			return 0;
	}
}